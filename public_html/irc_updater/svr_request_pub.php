<?php

include("../../common/lib.php");
include("../../common/proxy.php");

/* Dispatch request into handle function */
dispatch_request(array("get_online", "set_online", "set_online_ids", "shutdown", "c_conn"));

/* Getting list of servers */
function handle_get_online()
{
	global $dbcon;

	$result = mysqli_query($dbcon, "
		SELECT 
			id, port, ip, max_conn, num_conn, name, description, 
			minlevel, maxlevel, official
		FROM
			server
		WHERE
			updated > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
			
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
		$data[$row['id']] = $row;
	}

	global $config;
	if($config['isProxy']) {
		$officialdata = get_online_proxy();
		$data = array_merge($officialdata, $data);
	}

	return $data;
}

/* Add a server */
function handle_set_online()
{
	/* Sanitize input */
	$ip = $_SERVER["REMOTE_ADDR"];
	$port = intval(post_input("port"));
	$num_conn = intval(post_input("num_conn"));
	$max_conn = intval(post_input("num_max"));
	$name = post_input("name");
	$desc = post_input("desc");
	$status = post_input("status");
	$minkarma = post_input("minkarma");
	$location = post_input("location");
	$cgt = post_input("cgt");
	$next_map = post_input("next_map");
	$map = post_input("map");
	$login = post_input("login");
	$pass = post_input("pass");
	$minlevel = intval(post_input("minlevel"));
	$maxlevel = intval(post_input("maxlevel"));
	/* authenticate server */
	global $config;
	$data = array();
	$isOfficial = False;
	if($config['isProxy']) {
		// try official server auth method first
		$set_online['ip'] = post_input("ip");
		$set_online['port'] = post_input("port");
		$set_online['num_conn'] = post_input("num_conn");
		$set_online['num_max'] = post_input("num_max");
		$set_online['name'] = post_input("name");
		$set_online['desc'] = post_input("desc");
		$set_online['status'] = post_input("status");
		$set_online['minkarma'] = post_input("minkarma");
		$set_online['location'] = post_input("location");
		$set_online['cgt'] = post_input("cgt");
		$set_online['next_map'] = post_input("next_map");
		$set_online['map'] = post_input("map");
		$set_online['login'] = post_input("login");
		$set_online['pass'] = post_input("pass");
		$set_online['minlevel'] = post_input("minlevel");
		$set_online['maxlevel'] = post_input("maxlevel");
		$data = set_online_proxy($set_online);
file_put_contents("/var/tmp/qwerty", "officialtest\n".$ip."\n".$login."\n".serialize($data)."\n", FILE_APPEND);
		$server_id = 0;
		if(array_key_exists("authenticate", $data)) {
file_put_contents("/var/tmp/qwerty", $ip."\n".serialize($data)."\n", FILE_APPEND);
			// official update method failed
			// try regular non-official user server
			$data = auth_proxy($login, $pass);
			if(!array_key_exists('cookie', $data))
				return array();
		} else {
			$isOfficial = True;
		}

/*
        $r->addPostParameter(array('f' => 'set_online',
                                'ip' => $set_online['ip'],
                                'port' => $set_online['port'],
                                'num_conn' => $set_online['num_conn'],
                                'num_max' => $set_online['num_max'],
                                'name' => $set_online['name'],
                                'desc' => $set_online['desc'],

                                'status' => $set_online['status'],
                                'minkarma' => $set_online['minkarma'],
                                'location' => $set_online['location'],
                                'cgt' => $set_online['cgt'],
                                'next_map' => $set_online['next_map'],
                                'map' => $set_online['map'],
                                'login' => $set_online['login'],
                                'pass' => $set_online['pass'],
                                'minlevel' => $set_online['minlevel']));

*/
	}

	if($isOfficial) {
		// generate server id if not returned by official masterserver
		$res = mysqli_query($dbcon, "SELECT id from server ORDER BY id DESC LIMIT 1");
		$row = mysqli_fetch_assoc($res);
		$data['svr_id'] = str(intval($row['id']) + 1);
		$server_id = $data['svr_id'];
		$official = 'Y';
	} else {
		$server_id = intval($data['account_id']);
		$official = 'N';
	}

	/* Create in database */
	$query = "
		INSERT INTO server SET 
			official = '$official', id = '$server_id', ip = '$ip', port = $port, num_conn = $num_conn, max_conn = $max_conn,
			name = '$name', description = '$desc', minlevel = $minlevel,
			maxlevel = $maxlevel, updated = NOW()
		ON DUPLICATE KEY UPDATE
			official = '$official', id = $server_id, num_conn = $num_conn, max_conn = $max_conn, name = '$name', 
			description = '$desc', minlevel = $minlevel, 
			maxlevel = $maxlevel, updated = NOW()";

	global $dbcon;		
	mysqli_query($dbcon, $query);
	
	if($config['isProxy'] && $isOfficial) {
		return $data;
	}

	/* Send id in answer */
	$id = mysqli_insert_id($dbcon);
	$data = array(
		"acct_id" => $id,
		"svr_id" => $id,
		"set_online" => 3,
		"UPD" => 11,
		"reservation" => -1);

	return $data;
}

/* Save accounts on a server */
function handle_set_online_ids()
{
	/* Update number of connections */
	$num_conn = intval(post_input("num_conn"));	
	$query = "
		UPDATE server SET
			num_conn = $num_conn,
			updated = NOW()
		WHERE
			login = '$login'";
			
	/* Return empty */
	return array();
}

/* Remove a server */
function handle_shutdown()
{
	global $dbcon;

	/* Remove server from list */
	$id = intval(post_input("server_id"));
	$query = "
		DELETE FROM 
			server 
		WHERE
			id = $id";
	mysqli_query($dbcon, $query);
	
	/* Return empty */
	return array();
}

/* User joins a server */
function handle_c_conn() 
{
	global $dbcon;

	$account_id = intval(post_input("account_id"));
	$server_id = intval(post_input("server_id"));

	global $config;
	if($config['isProxy']) {
		$c_conn['account_id'] = post_input("account_id");
		$c_conn['server_id'] = post_input("server_id");
		$c_conn['num_conn'] = post_input("num_conn");
		$c_conn['cookie'] = post_input("cookie");
		$c_conn['ip'] = post_input("ip");
		$data = c_conn_proxy($c_conn);
		if(!array_key_exists('c_conn', $data))
			return;
	}

	$query = "
		INSERT INTO
			player
		SET
			user = {$account_id},
			server = {$server_id},
			updated = NOW(),
			online = 1
		ON DUPLICATE KEY UPDATE
			server = {$server_id},
			updated = NOW(),
			online = 1";
	db_query($query);

	/* Sickened2 - add missing parameters */
	$query = "SELECT username from users where id = $account_id";
	$result = mysqli_query($dbcon, $query);
	$row = mysqli_fetch_assoc($result);
	$client_name = $row['username'];

	return array("account_id" => post_input("account_id"), "client_name" => $client_name, "c_conn" => "OK");
}

/* User disconnects a server */
function handle_c_disc() 
{
	$account_id = intval(post_input("account_id"));
	$server_id = intval(post_input("server_id"));
	
	$query = "
		UPDATE
			player
		SET
			server = {$server_id},
			online = 0,
			updated = NOW()
		WHERE
			user = {$account_id}";
	db_query($query);
	
	return array("c_disk" => "OK");
}

?>

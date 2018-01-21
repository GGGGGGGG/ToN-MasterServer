<?php

include("../../common/lib.php");

/* Dispatch request into handle function */
dispatch_request(array("get_online", "set_online", "set_online_ids", "shutdown", "c_conn", "c_disc", "auth"));

/* Getting list of servers */
function handle_get_online()
{
	global $dbcon;

	$result = mysqli_query($dbcon, "
		SELECT 
			id, port, ip, max_conn, num_conn, name, description, 
			minlevel, maxlevel, official
		FROM
			servers
			WHERE
			online = 1");
			
	$data = array();
	while($row = mysqli_fetch_assoc($result)) {
		$data[$row['id']] = $row;
	}

	return $data;
}

/* Add a server */
function handle_set_online()
{
    global $dbcon;
    $data = array();

    $login = post_input('login');
	$pass = post_input('pass');

	if(server_auth($login, $pass))
	{
        $ip = $_SERVER["REMOTE_ADDR"];
        $port = intval(post_input("port"));
        /* Sanitize input */
        if($port != 0) {
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

            $official = 0;

            /* Create in database */
            $query = "
		INSERT INTO servers SET 
			official = '$official', id = DEFAULT, ip = '$ip', port = $port, num_conn = $num_conn, max_conn = $max_conn,
			name = '$name', login = '{$login}', description = '$desc', minlevel = $minlevel,
			maxlevel = $maxlevel, updated = NOW(), online = 1
		ON DUPLICATE KEY UPDATE
			ip = '$ip', port = $port, num_conn = $num_conn, max_conn = $max_conn, name = '$name', 
			login = '{$login}', description = '$desc', minlevel = $minlevel, 
			maxlevel = $maxlevel, updated = NOW(), online = 1";

            mysqli_query($dbcon, $query);


            /* Send id in answer */
            $id = mysqli_insert_id($dbcon);
            $data = array(
                "acct_id" => $id,
                "svr_id" => $id,
                "set_online" => 3,
                "UPD" => 11,
                "reservation" => -1);
        }
    }

	return $data;
}

/* Save accounts on a server */
function handle_set_online_ids()
{
	global $dbcon;
	$login = post_input('login');
	$pass = post_input('pass');

	if(server_auth($login, $pass))
	{
        /* Update number of connections */
        $num_conn = mysqli_real_escape_string($dbcon, intval(post_input("num_conn")));
        $login = mysqli_real_escape_string($dbcon, post_input("login"));
        $query = "
		UPDATE servers SET
			num_conn = $num_conn,
			updated = NOW()
		WHERE
			login = '$login'";
    }
	/* Return empty */
	return array();
}

/* Remove a server */
function handle_shutdown()
{
	global $dbcon;

	$login = post_input('login');
	$pass = post_input('pass');

	if(server_auth($login, $pass))
	{
        /* Remove server from list */
        $id = intval(post_input("server_id"));
        $query = "
		UPDATE servers SET num_conn = 0, updated = NOW(), online = 0
		WHERE
			id = $id";
        mysqli_query($dbcon, $query);
    }
	/* Return empty */
	return array();
}

/* User joins a server */
function handle_c_conn() 
{
	global $dbcon;

	$account_id = intval(post_input("account_id"));
	$server_id = intval(post_input("server_id"));
	$c_conn['account_id'] = post_input("account_id");
	$c_conn['server_id'] = post_input("server_id");
	$c_conn['num_conn'] = post_input("num_conn");
	$c_conn['cookie'] = post_input("cookie");
	$c_conn['ip'] = post_input("ip");

	$cookie = $c_conn['cookie'];

	/* first attempt to verify cookie on unofficial MS */
	$result = db_query("SELECT username FROM users WHERE id = $account_id AND cookie = '$cookie'");
	$row = mysqli_fetch_assoc($result);
	if(count($row) != 0) {
		$client_name = $row['username'];
	} else {
		return array();
	}

	$query = "
		INSERT INTO
			players
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
			players
		SET
			server = {$server_id},
			online = 0,
			updated = NOW()
		WHERE
			user = {$account_id}";

	db_query($query);

	$data = array("c_disc" => "OK");

	return $data;
}

/* Server start game */
function handle_auth()
{
	$data = array();
	global $dbcon;
	$login = post_input('login');
	$pass = post_input('pass');

	if(server_auth($login, $pass))
	{
		/* temp measure to get server id, it's currently not sent by the game server on post.
		*  it's only sending login, pass, type, port, map and the account ids of the clients on the server.
		*/
		$query = "SELECT id from servers WHERE login = '{$login}'";
		$result = mysqli_query($dbcon, $query);
		$row = mysqli_fetch_assoc($result);
		$data['svr_id'] = $row['id'];

		$query = "SELECT official from servers where id = {$data['svr_id']}";
		$result = mysqli_query($dbcon, $query);
		$check = mysqli_fetch_assoc($result);
		if($check['official'])
		{
			$port = post_input('port');
			$map = post_input('map');

			$query = "INSERT INTO match_summs (port, created_at, map, server_id) VALUES ('{$port}', now(), '{$map}', '{$data['svr_id']}' )";
			mysqli_query($dbcon, $query);
			$data['salt'] = 't3x'; //unused by us. just there since the game server uses it
			$data['match_id'] = mysqli_insert_id($dbcon);
		}
	}

	return $data;

}

?>

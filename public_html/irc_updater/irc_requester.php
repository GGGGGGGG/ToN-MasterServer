<?php

include("../../common/lib.php");
include("../../common/proxy.php");

/* Dispatch request into handle function */
dispatch_request(array("auth", "item_list", "clan_roster", "get_all_stats", "nick2id", "new_buddy", "remove_buddy", "cr_vote"));

/* Authentification */
function handle_auth()
{
	global $config;
	
	$nickname = post_input("email");
	$password = post_input("password");

	$acc_id = 0;
	$cookie = "";
	
	if($config['isProxy']) {
		$data = auth_proxy($nickname, $password);
		if(array_key_exists("cookie", $data)) {
			$acc_id = intval($data['account_id']);
			$nickname = $data['nickname'];
			$cookie = $data['cookie'];
			$pquery = "INSERT INTO users (id, username, cookie) VALUES ({$acc_id}, '{$nickname}', '{$cookie}') ON DUPLICATE KEY UPDATE cookie='{$cookie}'";
			db_query($pquery);
			return $data;
		}
	}

	/* account is invalid on official MS, so now check here */

	$query = "
		SELECT 
			users.username AS nickname,
			users.id AS account_id,
			users.email,
			password AS passwordhash
		FROM 
			users
		WHERE 
			username = '{$nickname}' 
		";//AND 
		//	password = SHA2('{$config['hash']}{$password}', 256)";

	global $dbcon;
	$result = mysqli_query($dbcon, $query);
	if(!mysqli_num_rows($result) == 1) {
		/* No user found, return error */
		return array("error" => "Invalid login. :/");
	} else {
		$data = mysqli_fetch_assoc($result);

		/* verify password */
		$passwordhash = $data['passwordhash'];
		$authSuccess = password_verify($password, $passwordhash);
		if(!$authSuccess)
			return array("error" => "Invalid login. :/");

		/* generate cookie for user and add to DB */
		$cookie = md5(uniqid(rand(), true));
		$data["cookie"] = $cookie; 

		$pquery = "UPDATE users SET cookie='{$cookie}' WHERE username='{$nickname}'";
		db_query($pquery);

		/* Return user data */
		$data["account_type"] = 1;	
		
		/* Buddy list */
		$query = "
			SELECT
				users.username AS nickname,
				users.id AS buddy_id,
				'' AS note,
				'' AS clan_name,
				'' AS clan_tag,
				'' AS clan_img,
				'' AS avatar
			FROM
				buddies
			JOIN
				users
			ON
				buddies.target_id = users.id
			WHERE
				users.source_id = {$data['account_id']}";
		$result = mysqli_query($dbcon, $query);
		
		if(mysqli_num_rows($result) == 0) {
			$data["buddy"] = array("error" => "No buddies found.");
		} else {
			$data["buddy"] = array();
			while($row = mysqli_fetch_assoc($result)) {
				$data["buddy"][$row["buddy_id"]] = $buddy;
			}
		}
		
		/* Stats */
		$data["player_stats"] = array($data['account_id'] => array());
		$data["ranked_stats"] = array($data['account_id'] => array());
		
		return $data;
	}
}

/* Clan roster [empty] */
function handle_clan_roster()
{
	return array();		
}

/* Item list [empty] */
function handle_item_list()
{
	return array();
	$account_id = post_input("account_id");
	global $config;
	$data = array();
	if($config['isProxy']) {
		$data = item_list_proxy($account_id);
	}
	return $data;
}

/* All stats */
function handle_get_all_stats()
{
	$account_ids = $_POST["account_id"];
	global $config;
	$data = array();
	if($config['isProxy']) {
		$data = get_all_stats_proxy($account_ids);
	}
	return $data;
}

/* Get account ID for nickname */
function handle_nick2id()
{
	if(!isset($_POST["nickname"]) or !is_array($_POST["nickname"]))
		return array();
		
	$nicknames = $_POST["nickname"];

	global $config;
	if($config['isProxy']) {
		$data = nick2id_proxy($nicknames);
		return $data;
	}
	
	$data = array();
	foreach($nicknames as $nick) {
		/* TODO: Optimize this by creating a single query for all nicknames */
		$safe_nick = mysql_real_escape_string($nick);

		/* Search nickname in database */
		$query = "
			SELECT 
				id 
			FROM 
				users
			WHERE
				username = '{$safe_nick}'";
		$result = mysql_query($query);

		/* Save in output (nickname -> id) */
		if(mysql_num_rows($result) == 1) {
			$row = mysql_fetch_assoc($result);
			$data[$nick] = "{$row["id"]}";
		}
	}
	
	return $data;
}

/* Add a new buddy */
function handle_new_buddy()
{
	$account_id = intval(post_input("account_id"));
	$buddy_id = intval(post_input("buddy_id"));
	
	global $config;
	if($config['isProxy']) {
		$data = new_buddy_proxy($account_id, $buddy_id);
		return $data;
	}

	/* See if these are two valid accounts */
	$query = "
		SELECT
			id
		FROM
			users
		WHERE
			id IN ($account_id, $buddy_id)";
	$result = mysql_query($query);
	
	if(mysql_num_rows($result) == 2) {
		/* Insert buddy entry */
		$query = "
			INSERT INTO
				buddies
			SET
				source_id = $account_id,
				target_id = $target_id";
		mysql_query($query);
		/* TODO: Find out what notification is */
		return array(
			"new_buddy" => "ok",
			"notification" => array(1,2));
	} else {
		return array("error" => "Invalid request");		
	}
}

/* Remove a buddy */
function handle_remove_buddy()
{
	$account_id = intval(post_input("account_id"));
	$buddy_id = intval(post_input("buddy_id"));
	
	global $config;
	if($config['isProxy']) {
		$data = remove_buddy_proxy($account_id, $buddy_id);
		return $data;
	}

	/* Insert buddy entry */
	$query = "
		DELETE FROM
			buddies
		WHERE
			source_id = $account_id
		AND target_id = $target_id";
	mysql_query($query);
	
	/* TODO: Find out what notification is */
	return array(
		"remove_buddy" => "ok",
		"notification" => array(1,2));
}

function handle_cr_vote()
{
	$account_id = intval(post_input("account_id"));
	$comm_id = intval(post_input("comm_id"));
	$match_id = intval(post_input("match_id"));
	$vote = intval(post_input("vote"));
	$reason = post_input("reason");
	
	/* TODO: Check if user was in this game */
	$query = "
		INSERT INTO
			votes
		SET
			account_id = {$account_id},
			comm_id = {$comm_id},
			match_id = {$match_id},
			vote = {$vote},
			reason = '{$reason}'";
	
	try {
		db_query($query);
		return array("cr" => "OK");
	} catch (Exception $e) {
		return array("cr" => "ERROR");
	}
}

?>

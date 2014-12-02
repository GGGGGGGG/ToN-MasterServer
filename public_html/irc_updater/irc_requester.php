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
	
	if($config['isProxy']) {
		$data = auth_proxy($nickname, $password);
		if(array_key_exists("cookie", $data)) {
			$pquery = "INSERT INTO users (id, username, password, cookie) VALUES ({$data['account_id']}, {$data['nickname']}, 'x', {$data['cookie']})";
			global $dbcon;
			mysqli_query($dbcon, $pquery);
		}
		return $data;
	}

	$query = "
		SELECT 
			users.username AS nickname,
			users.id AS account_id,
			users.email
		FROM 
			users
		WHERE 
			username = '{$nickname}' 
		AND 
			password = MD5('{$config['hash']}{$password}')";
	
	$result = mysql_query($query);
	
	if(!mysql_num_rows($result) == 1) {
		/* No user found, return error */
		return array("error" => "Invalid login. :/");
	} else {
		/* Return user data */
		$data = mysql_fetch_assoc($result);
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
		$result = mysql_query($query);
		
		if(mysql_num_rows($result) == 0) {
			$data["buddy"] = array("error" => "No buddies found.");
		} else {
			$data["buddy"] = array();
			while($row = mysql_fetch_assoc($result)) {
				$data["buddy"][$row["buddy_id"]] = $buddy;
			}
		}
		
		/* Stats */
		$data["player_stats"] = array($data['account_id'] => array());
		$data["ranked_stats"] = array($data['account_id'] => array());
		
		/* Sickened2 */
		/* generate cookie for user */
		$data["cookie"] = md5(uniqid(rand(), true));

		return $data;
	}
}

/* Item list [empty] */
function handle_item_list()
{
	return array();		
}

/* Clan roster [empty] */
function handle_clan_roster()
{
	return array();
}

/* All stats [empty] */
function handle_get_all_stats()
{
	return array();		
}

/* Get account ID for nickname */
function handle_nick2id()
{
	if(!isset($_POST["nickname"]) or !is_array($_POST["nickname"]))
		return array();
		
	$nicknames = $_POST["nickname"];
	
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

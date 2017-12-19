<?php

include("../../common/lib.php");

/* Dispatch request into handle function */
dispatch_request(array("auth", "item_list", "clan_roster", "get_all_stats", "get_stats", "nick2id", "new_buddy", "remove_buddy", "cr_vote", "upd_karma"));

/* Authentification */
function handle_auth()
{
    global $dbcon;

    $nickname = mysqli_real_escape_string($dbcon, post_input("email"));
    $password = mysqli_real_escape_string($dbcon, post_input("password"));

    /* first try verifying account on unofficial ms */

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
		AND 
			CHAR_LENGTH(password) > 1";

    $result = mysqli_query($dbcon, $query);
    $data = mysqli_fetch_assoc($result);

    /* verify password */
    $passwordhash = $data['passwordhash'];
    $authSuccess = password_verify($password, $passwordhash);
    if (!$authSuccess)
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

    if (mysqli_num_rows($result) == 0) {
        $data["buddy"] = array("error" => "No buddies found.");
    } else {
        $data["buddy"] = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data["buddy"][$row["buddy_id"]] = $row;
        }
    }


    // stats
    $query = "SELECT overall_r, sf, lf, level, clans.*, karma FROM playerinfos JOIN clans ON playerinfos.clan_id = clans.id WHERE playerinfos.account_id = {$data['account_id']}";
    $result = db_query($query);
    $data["player_stats"] = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data["player_stats"][$row["account_id"]] = $row;
        }
    }

    /* Stats */
    //$data["player_stats"] = array($data['account_id'] => array());
    $data["ranked_stats"] = array($data['account_id'] => array());

    return $data;

}

/* Clan roster [empty] */
function handle_clan_roster()
{
	return array();		
}

/* Item list */
function handle_item_list()
{
	global $dbcon;
	$account_id = mysqli_real_escape_string($dbcon, post_input("account_id"));
	$data = array();

	/* fetch from unofficial MS if user entry exists */
	$id = intval($account_id);
	$result = db_query("SELECT item_id,type,exp_date FROM items WHERE account_id = $id");
	if(mysqli_num_rows($result) > 0) {
		$data = array();
		while($row = mysqli_fetch_assoc($result)) {
			array_push($data, $row);
		}
		return $data;
	}

	return $data;
}

/* All stats */
function handle_get_all_stats()
{
	global $dbcon;
	$account_ids = mysqli_real_escape_string($dbcon, $_POST["account_id"]);
    $query = "SELECT overall_r, sf, lf, LEVEL, clans.*, karma, playerstats.* FROM playerinfos JOIN playerstats JOIN clans ON playerinfos.clan_id = clans.id WHERE playerinfos.account_id AND playerstats.account_id = {$account_ids}";
	$data = array();
	$result = db_query($query);
	if(mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			array_push($data, $row);
		}
	}
	return $data;
}

function handle_get_stats()
{
    global $dbcon;
    $account_ids = mysqli_real_escape_string($dbcon, $_POST["account_id"]);
    $query = "SELECT overall_r, sf, lf, LEVEL, clans.*, karma, playerstats.* FROM playerinfos JOIN playerstats JOIN clans ON playerinfos.clan_id = clans.id WHERE playerinfos.account_id AND playerstats.account_id = {$account_ids}";
    $data = array();
    $result = db_query($query);
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($data, $row);
        }
    }
    return $data;
}

/* Get account ID for nickname */
function handle_nick2id()
{
	global $dbcon;
	if(!isset($_POST["nickname"]) or !is_array($_POST["nickname"]))
		return array();

	$nicknames = mysqli_real_escape_string($dbcon, $_POST["nickname"]);

	
	$data = array();
	foreach($nicknames as $nick) {
		/* TODO: Optimize this by creating a single query for all nicknames */
		$safe_nick = mysqli_real_escape_string($dbcon, $nick);

		/* Search nickname in database */
		$query = "
			SELECT 
				id 
			FROM 
				users
			WHERE
				username = '{$safe_nick}'";
		$result = mysqli_query($query);

		/* Save in output (nickname -> id) */
		if(mysqli_num_rows($result) == 1) {
			$row = mysqli_fetch_assoc($result);
			$data[$nick] = "{$row["id"]}";
		}
	}
	
	return $data;
}

/* Add a new buddy */
function handle_new_buddy()
{
	global $dbcon;
	$account_id = mysqli_real_escape_string($dbcon, intval(post_input("account_id")));
	$buddy_id = mysqli_real_escape_string($dbcon, intval(post_input("buddy_id")));

	/* See if these are two valid accounts */
	$query = "
		SELECT
			id
		FROM
			users
		WHERE
			id IN ($account_id, $buddy_id)";
	$result = mysqli_query($dbcon, $query);
	
	if(mysqli_num_rows($result) == 2) {
		/* Insert buddy entry */
		$query = "
			INSERT INTO
				buddies
			SET
				source_id = $account_id,
				target_id = $buddy_id";
		mysqli_query($query);
		/* TODO: Find out what notification is */
		/* notification looks like a global value that is returned to confirm addition or removal of buddy, then incremented. Returned as n, n+1 */
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
	global $dbcon;
	$account_id = mysqli_real_escape_string($dbcon, intval(post_input("account_id")));
	$buddy_id = mysqli_real_escape_string($dbcon, intval(post_input("buddy_id")));

	/* Insert buddy entry */
	$query = "
		DELETE FROM
			buddies
		WHERE
			source_id = $account_id
		AND target_id = $buddy_id";
	mysqli_query($dbcon, $query);
	
	/* TODO: Find out what notification is */
	return array(
		"remove_buddy" => "ok",
		"notification" => array(1,2));
}

function handle_cr_vote()
{
	global $dbcon;
	$account_id = mysqli_real_escape_string($dbcon, intval(post_input("account_id")));
	$comm_id = mysqli_real_escape_string($dbcon, intval(post_input("comm_id")));
	$match_id = mysqli_real_escape_string($dbcon, intval(post_input("match_id")));
	$vote = mysqli_real_escape_string($dbcon, intval(post_input("vote")));
	$reason = mysqli_real_escape_string($dbcon, post_input("reason"));
	
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

function handle_upd_karma()
{
	$k['account_id'] = post_input('account_id');
	$k['target_id'] = post_input('target_id');
	$k['match_id'] = post_input('match_id');
	$k['do'] = post_input('do');
	$k['reason'] = post_input('reason');

	global $config;
	if($config['isProxy']) {
		return upd_karma_proxy($k);
	}

	return array('karma' => 'OK');
}
?>

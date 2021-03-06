<?php

include("../../common/lib.php");

/* Dispatch request into handle function */
dispatch_request(array("auth", "item_list", "clan_roster", "get_all_stats", "get_stats", "nick2id", "new_buddy", "remove_buddy", "cr_vote", "upd_karma"));

/* Authentification */
function handle_auth()
{
    global $dbcon;

    $nickname = post_input("email");
    $password = post_input("password");

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
			username = '{$nickname}' ";

    $result = mysqli_query($dbcon, $query);
    $data = mysqli_fetch_assoc($result);


    /* verify password */
    $passwordhash = $data['passwordhash'];
    $authSuccess = password_verify($password, $passwordhash);
    if (!$authSuccess) {
        return array("error" => "Invalid login. :/");
    }

    $data['passwordhash'] = "secret"; //don't need to send back the passwordhash


    // okay so we checked the user and passwords were right, let's check if the user is banned or not.
    $query = "SELECT banneduntil from bans WHERE account_id = {$data['account_id']} AND banneduntil > NOW()";
    $result = mysqli_query($dbcon, $query);

    if(mysqli_num_rows($result) > 0)
    {
        $data = mysqli_fetch_assoc($result);
        return array("error" => "You're banned until ". $data['banneduntil']);
    }

    //everyone is prime
    $data["account_type"] = 1;

    /* generate cookie for user and add to DB */
    $cookie = md5(uniqid(rand(), true));
    $data["cookie"] = $cookie;

    $pquery = "UPDATE users SET cookie='{$cookie}', updated_at = NOW() WHERE username='{$nickname}'";
    db_query($pquery);


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
				buddies.source_id = {$data['account_id']}";
    $result = mysqli_query($dbcon, $query);

    if (mysqli_num_rows($result) == 0) {
        $data["buddy"] = array("error" => "No buddies found.");
    } else {
        $data["buddy_list"] = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data["buddy_list"][$data['account_id']][$row["buddy_id"]]['buddy_id'] = $row['buddy_id'];
            $data["buddy_list"][$data['account_id']][$row["buddy_id"]]['nickname'] = $row['nickname'];
            $data["buddy_list"][$data['account_id']][$row["buddy_id"]]['note'] = $row['note'];
            $data["buddy_list"][$data['account_id']][$row["buddy_id"]]['clan_name'] = $row['clan_name'];
            $data["buddy_list"][$data['account_id']][$row["buddy_id"]]['clan_tag'] = $row['clan_tag'];
            $data["buddy_list"][$data['account_id']][$row["buddy_id"]]['clan_img'] = $row['clan_img'];
            $data["buddy_list"][$data['account_id']][$row["buddy_id"]]['avatar'] = $row['avatar'];
        }
    }



    // stats
    $query = "SELECT overall_r, sf, lf, level, clans.*, karma FROM playerinfos JOIN clans ON playerinfos.clan_id = clans.id WHERE playerinfos.account_id = {$data['account_id']}";
    $result = db_query($query);
    $data["player_stats"] = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data["player_stats"][$data["account_id"]] = $row;
        }
    }

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
    $account_id = post_input("account_id");
    $data = array();

    /* fetch from unofficial MS if user entry exists */
    $id = intval($account_id);
    $query = "SELECT item_id,type,exp_date FROM items WHERE account_id = $id";
    $result = mysqli_query($dbcon, $query);
    if (mysqli_num_rows($result) > 0) {
        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
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
    $account_ids = post_input("account_id");
    $data = array();
    foreach ($account_ids as $account_id) {
        $query = "SELECT 
            overall_r, sf, lf, clans.*, level, karma, playerstats.*
            FROM playerinfos, clans, playerstats
            WHERE playerinfos.account_id = {$account_id} 
            AND playerinfos.clan_id = clans.id 
            AND playerstats.account_id = playerinfos.account_id";
        $result = mysqli_query($dbcon, $query);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data['all_stats'][$row['account_id']] = $row;
            }
        }
    }
    return $data;
}

function handle_get_stats()
{
    global $dbcon;
    $account_id = post_input("account_id");
    $query = "SELECT 
            overall_r, sf, lf, clans.*, level, karma, playerstats.*, commanderstats.* 
            FROM playerinfos, clans, playerstats, commanderstats 
            WHERE playerinfos.account_id = {$account_id} 
            AND playerinfos.clan_id = clans.id 
            AND playerstats.account_id = playerinfos.account_id
            AND playerinfos.account_id = commanderstats.account_id";
    $data = array();
    $result = mysqli_query($dbcon, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data['player_stats'][$row['account_id']] = $row;
        }
    }
    return $data;
}

/* Get account ID for nickname */
function handle_nick2id()
{
    global $dbcon;

    $nicks = post_input("nickname");

    if (!is_array($nicks))
        return array();


    $data = array();

    foreach ($nicks as $nick) {

        /* Search nickname in database */
        $query = "
			SELECT 
				id 
			FROM 
				users
			WHERE
				username = '{$nick}'";
        $result = mysqli_query($dbcon, $query);

        /* Save in output (nickname -> id) */
        if (mysqli_num_rows($result) == 1) {
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
    $result = mysqli_query($dbcon, $query);

    if (mysqli_num_rows($result) == 2) {
        /* Insert buddy entry */
        $query = "
			INSERT INTO
				buddies
			SET
				source_id = $account_id,
				target_id = $buddy_id";
        mysqli_query($dbcon, $query);
        /* TODO: Find out what notification is */
        /* notification looks like a global value that is returned to confirm addition or removal of buddy, then incremented. Returned as n, n+1 */
        return array(
            "new_buddy" => "ok",
            "notification" => array(1, 2));
    } else {
        return array("error" => "Invalid request");
    }
}

/* Remove a buddy */
function handle_remove_buddy()
{
    global $dbcon;
    $account_id = intval(post_input("account_id"));
    $buddy_id = intval(post_input("buddy_id"));

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
        "notification" => array(1, 2));
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

function handle_upd_karma()
{
    $account_id = intval(post_input('account_id'));
    $target_id = intval(post_input('target_id'));
    $match_id = intval(post_input('match_id'));
    $do = post_input('do');
    $reason = post_input('reason');

    /* TODO: Check if user was in this game */
    $query = "
        INSERT INTO
              karmas
        SET
            account_id = {$account_id}
            target_id = {$target_id},
            match_id = {$match_id},
            do = {$do},
            reason = {$reason}";

    try {
        db_query($query);
        return array("karma" => "OK");
    } catch (Exception $e) {
        return array("karma" => "ERROR");
    }
}

?>

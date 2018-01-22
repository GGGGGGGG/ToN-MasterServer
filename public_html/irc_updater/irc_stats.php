<?php

include("../../common/lib.php");

/* Valid stats fields */
$fields = array(
	'action' => array(
		'exp',
		'kills',
		'deaths',
		'assists',
		'souls',
		'razed',
		'pdmg',
		'bdmg',
		'npc',
		'hp_healed',
		'res',
		'gold',
		'hp_repaired',
		'secs',
		'end_status',
		'sf',
		'ip'
	),
	'commander' => array(
		'c_builds' => 'builds',
		'c_exp' => 'exp',
		'c_gold' => 'gold',
		'c_razed' => 'razed',
		'c_hp_healed' => 'hp_healed',
		'c_pdmg' => 'pdmg',
		'c_kills' => 'kills',
		'c_debuffs' => 'debuffs',
		'c_buffs' => 'buffs',
		'c_orders' => 'orders',
		'c_secs' => 'secs',
		'c_end_status' => 'end_status',
		'sf' => 'sf',
		'ip' => 'ip'
	)
);

/* Dispatch request into handle function */
dispatch_request(array("end_game", "replays"));

/* Submit stats */
function handle_end_game()
{
    $login = post_input('login');
    $pass = post_input('pass');
    if(server_auth($login, $pass))
    {
        global $fields;
        global $dbcon;


        mysqli_begin_transaction($dbcon, MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

        $match_id = intval(post_input("match_id"));
        $map = post_input("map");
        $winner = intval(post_input("winner"));
        $duration = post_input("time");
        $player_stats = post_serialized("player_stats");
        $commander_stats = post_serialized("commander_stats");
        $winner_id = 0;

        /* Insert match */
        $query = "
		INSERT INTO
			matches
		SET
			`id` = {$match_id},
			`map` = '{$map}',
			`duration` = '{$duration}',
			`winner` = {$winner}";

        try {
            db_query($query);
        } catch (Exception $e) {
            error_log($e, 3, '/var/tmp/ton.log');
        }


        /* Insert teams */
        $teams = post_serialized("team");
        $team_ids = array();
        foreach ($teams as $index => $team) {
            if ($team['race'] == 'H') {
                $team['race'] = 1;
            } else {
                $team['race'] = 2;
            }
            $query = "
			INSERT INTO
				teams
			SET
				`match` = {$match_id},
				`race` = '{$team['race']}',
				`avg_sf` = {$team['avg_sf']},
				`commander` = {$team['commander']}";
            try {
                db_query($query);
                $team_ids[$index] = mysqli_insert_id($dbcon);
            } catch (Exception $e) {
                error_log($e, 3, '/var/tmp/ton.log');
            }

            if ($team['race'] == $winner) {
                $winner_id = $team_ids[$index];
            }

        }

        /* Insert player stats */
        foreach ($player_stats as $player) {
            $team_id = $team_ids[$player['team']];

            $query = "
			INSERT INTO
				actionplayers
			SET
				`user` = {$player['account_id']},
				`match` = {$match_id},
				`team` = {$team_id}";

            $queryPlayer = "SELECT * FROM
		playerstats
		WHERE account_id = {$player['account_id']}";
            $result = mysqli_query($dbcon, $queryPlayer);
            $playerArray = mysqli_fetch_assoc($result);


            //we have to calculate sf ourselves, client sends back the sf account instead
            $player['sf'] = $player['exp'] / ($player['secs'] / 60);


            // stats fields
            foreach ($fields['action'] as $field) {
                $query .= ", `{$field}` = '{$player[$field]}'";
                //those fields are not part of the playerArray
                if ($field != 'ip' || $field != 'sf' || $field != 'end_status') {
                    $playerArray[$field] = $playerArray[$field] + $player[$field];
                }
            }

            if ($team_id == $winner_id) {
                $playerArray['wins'] = $playerArray['wins'] + 1;
            } else {
                $playerArray['losses'] = $playerArray['losses'] + 1;
            }

            $queryPlayer = "UPDATE
            playerstats
            SET 
            wins = {$playerArray['wins']},
            losses = {$playerArray['losses']},
            exp = {$playerArray['exp']},
            kills = {$playerArray['kills']},
            deaths = {$playerArray['deaths']},
            assists = {$playerArray['assists']},
            souls = {$playerArray['souls']},
            razed = {$playerArray['razed']},
            pdmg = {$playerArray['pdmg']},
            bdmg = {$playerArray['bdmg']},
            npc = {$playerArray['npc']},
            hp_healed = {$playerArray['hp_healed']},
            secs = {$playerArray['secs']}
            WHERE account_id = {$player['account_id']};
		";

            try {
                db_query($query);
                db_query($queryPlayer);
            } catch (Exception $e) {
                error_log($e, 3, '/var/tmp/ton.log');
            }


        }

        /* Insert commander stats */
        foreach ($commander_stats as $commander) {
            $team_id = $team_ids[$commander['c_team']];

            $queryCommander = "SELECT * FROM
		commanderstats
		WHERE account_id = {$commander['account_id']}";
            $result = mysqli_query($dbcon, $queryCommander);
            $commanderArray = mysqli_fetch_assoc($result);

            $query = "
			INSERT INTO
				commanders
			SET
				`user` = {$commander['account_id']},
				`match` = {$match_id},
				`team` = {$team_id}";

            // stats fields
            foreach ($fields['commander'] as $name => $target) {
                $query .= ", `{$target}` = '{$commander[$name]}'";
                if ($target != 'ip' || $target != 'sf' || $target != 'end_status') {
                    $commanderArray[$target] = $commanderArray[$target] + $commander[$name];
                }
            }

            if ($team_id == $winner_id) {
                $commanderArray['wins'] = $commanderArray['wins'] + 1;
            } else {
                $commanderArray['losses'] = $commanderArray['losses'] + 1;
            }
            $queryCommander = "UPDATE
            commanderstats
            SET 
            wins = {$commanderArray['wins']},
            losses = {$commanderArray['losses']},
            exp = {$commanderArray['exp']},
            builds = {$commanderArray['builds']},
            gold = {$commanderArray['gold']},
            razed = {$commanderArray['razed']},
            hp_healed = {$commanderArray['hp_healed']},
            pdmg = {$commanderArray['pdmg']},
            kills = {$commanderArray['kills']},
            assists = {$commanderArray['assists']},
            debuffs = {$commanderArray['debuffs']},
            buffs = {$commanderArray['buffs']},
            orders = {$commanderArray['orders']},
            secs = {$commanderArray['secs']},
            winstreak = {$commanderArray['winstreak']}
            WHERE account_id = {$commander['account_id']};
		";

            try {
                db_query($query);
                db_query($queryCommander);
            } catch (Exception $e) {
                error_log($e, 3, '/var/tmp/ton.log');
            }
        }

        mysqli_commit($dbcon);

        mysqli_close($dbcon);

        sf($player_stats);
    }
		
	return array();
}

function handle_replays()
{
    $post = file_get_contents("php://input");

    $data = json_decode($post);

    foreach($data['replay'] as $replay)
    {
        $login = $replay['login'];
        $pass = $replay['pass'];
        if(server_auth($login, $pass)) {
            $file = fopen($_SERVER['DOCUMENT_ROOT'] . '/replays/' . $replay['id'] . '.s2r', 'w');
            $content = base64_decode($replay['content']);
            fwrite($file, $content);
        }
    }
}

function sf(array $players)
{
    global $dbcon;

    foreach($players as $player)
    {
        $query = "SELECT AVG(sf) as sf_avg from actionplayers where user = {$player['account_id']}";
        $result = mysqli_query($dbcon, $query);
        $sf = mysqli_fetch_assoc($result);

        $query = "UPDATE playerinfos SET sf='{$sf['sf_avg']}' WHERE account_id='{$player['account_id']}'";
        mysqli_query($dbcon, $query);
    }
}
?>
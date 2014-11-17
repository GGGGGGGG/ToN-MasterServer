<?php

include("../common/lib.php");

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
dispatch_request(array("end_game"));

/* Submit stats */
function handle_end_game()
{
	global $fields;
	
	$match_id = intval(post_input("match_id"));
	$map = post_input("map");
	$winner = intval(post_input("winner"));
	$duration = post_input("time");
	$raw = serialize($_POST);
	
	/* Insert match */
	$query = "
		INSERT INTO
			matches
		SET
			`id` = {$match_id},
			`map` = '{$map}',
			`duration` = '{$duration}',
			`winner` = {$winner},
			`raw` = '{$raw}'";
	db_query($query);
	
	/* Insert teams */
	$teams = post_serialized("team");
	$team_ids = array();
	foreach ($teams as $index => $team) {
		$query = "
			INSERT INTO
				teams
			SET
				`match` = {$match_id},
				`race` = '{$team['race']}',
				`avg_sf` = {$team['avg_sf']},
				`commander` = {$team['commander']}";
		db_query($query);
		$team_ids[$index] = mysql_insert_id();
	}

	/* Insert player stats */
	$player_stats = post_serialized("player_stats");
	foreach ($player_stats as $player) {
		$user_id = $player['account_id'];
		$team_id = $team_ids[$player['team']];
		
		$query = "
			INSERT INTO
				actionplayers
			SET
				`user` = {$user_id},
				`match` = {$match_id},
				`team` = {$team_id}";
		
		// stats fields
		foreach ($fields['action'] as $field) {
			$query .= ", `{$field}` = '{$player[$field]}'";
		}
		
		db_query($query);
	}
	
	/* Insert commander stats */
	$commander_stats = post_serialized("commander_stats");
	foreach ($commander_stats as $commander) {
		$user_id = $commander['account_id'];
		$team_id = $team_ids[$commander['c_team']];
		
		$query = "
			INSERT INTO
				commanders
			SET
				`user` = {$user_id},
				`match` = {$match_id},
				`team` = {$team_id}";
		
		// stats fields
		foreach ($fields['commander'] as $name => $target) {
			$query .= ", `{$target}` = '{$commander[$name]}'";
		}
		
		db_query($query);
	}
		
	return array();
}

?>
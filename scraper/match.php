<?php

include("../common/lib.php");

define('COOKIE_FILE_PATH', '/tmp/bla');

$curl_handle = curl_init();
curl_setopt($curl_handle, CURLOPT_COOKIEJAR, COOKIE_FILE_PATH);
curl_setopt($curl_handle, CURLOPT_COOKIEFILE, COOKIE_FILE_PATH);
$matchid = 139300;

/* Load data */
$servers = db_query_array("SELECT name, id FROM servers");
$maps = db_query_array("SELECT name, id FROM maps");
$users = db_query_array("SELECT id, username FROM users");

function get_server($name) {
    global $servers;
    return isset($servers[$name]) ? $servers[$name] : 0;
}

function get_map($name) {
    global $maps;
    $key = strtolower($name);
    return isset($maps[$key]) ? $maps[$key] : 0;
}

function save_user($id, $nickname) {
    global $users;
    if (!isset($users[$id])) {
        db_query("INSERT INTO users SET id = {$id}, username = '{$nickname}'");
        $users[$id] = $nickname;
    }
}

function do_request($url, $post = '') {
    global $curl_handle;
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

    if ($post != '') {
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $post);
    }
    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl_handle, CURLOPT_REFERER, 'http://www.savage2replays.com');
    $result = curl_exec($curl_handle);
    
    return $result;
}

function error_handler($fehlercode, $fehlertext, $fehlerdatei, $fehlerzeile) {
    print "Error: $fehlercode in $fehlerdatei line $fehlerzeile\n";
    print $fehlertext;
    die;
}

set_error_handler("error_handler");

/* Login */
//do_request('http://savage2.com/en/remote_login.php', 'login=ChatBot&password=roboter');

while(true) {
    echo "Fetching match: $matchid ";

    try {    
        /* Fetch match page */
        $matchstats = do_request('http://www.savage2replays.com/match_replay.php?mid=' . $matchid);

        $regexps = array(
            'date' => '/>([0-9]{2}\/[0-9]{2}\/[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2})</',
            'duration' => '/>([0-9]{2}:[0-9]{2}:[0-9]{2})</',
            'winner' => '/><b>Team ([0-9])</',
            'map' => '/<b>Map Name:<\\/b> <span class=my12>([a-zA-Z ]*)<\\/span></'
        );

        $data = array();
        foreach($regexps as $name => $regexp) {            
            preg_match_all($regexp, $matchstats, $matches);
            $data[$name] = $matches[1][0];
        }
        
        $lines = explode("\n", $matchstats);
    
        $nr = 0;
        foreach($lines as $line) {
            if (strstr($line, "mrserver1.gif") !== false)
                break;
            $nr++;
        }
        $nr += 4;
        $servername = trim(substr(trim($lines[$nr]), 0, -5));
        $data['server'] = get_server($servername);
        $data['servername'] = addslashes($servername);
        $data['map'] = get_map($data['map']);

        // read races from match html
        preg_match_all('/mr_race_([a-zA-Z]+).gif/', $matchstats, $races);
        $races[1] = array_unique($races[1]);
        if (count($races[1]) != 2) {
            throw new Exception("Not two races");
        }

        // save to databases
        $query = "
            INSERT INTO 
                matches
            SET 
                id = {$matchid},
                server = {$data['server']},
                servername = '{$data['servername']}',
                winner = {$data['winner']},
                duration = '{$data['duration']}',
                map = {$data['map']}";
        db_query($query);

        /* Teams */

        $i = 0;

        // insert teams
        $teamids = array();
        foreach($races[1] as $race) {
            $query = "
                INSERT INTO
                    teams
                SET
                    `match` = {$matchid},
                    `race` = '{$race}'";
            db_query($query);
            $teamids[$i++] = mysql_insert_id();
        }

        /* Commanders */
        $regexp = '/getCommMatchInfo\(([0-9]+), ([0-9]+)\)/';
        preg_match_all($regexp, $matchstats, $comms);

        $team = 0;
        foreach ($comms[2] as $userid) {
            // get ids
            $teamid = $teamids[$team];
    
            // read stats
            $commstats = do_request('http://www.savage2replays.com/get_comm_match_stats.php?aid=' . $userid . '&mid=' . $matchid);

            // get username and save in db
            $regexp = '$<span class=g12><b>([a-zA-Z0-9\-_\.,]*)</b>$';
            preg_match_all($regexp, $commstats, $values);
            $username = $values[1][0];
            save_user($userid, $username);

            // parse all values into an array
            $regexp = '$ width=129 height=30 valign=top>([0-9,:]*)</td$';
            preg_match_all($regexp, $commstats, $values);

            // set correct keys
            $fields = array_combine(
                array('exp', 'orders', 'gold', 'builds', 'repaired', 'razed', 'buffs', 'hp_healed', 'debuffs', 'pdmg', 'kills', 'secs'),
                $values[1]);
    
            // repaired doesn't work
            unset($fields['repaired']);

            // calculate duration in seconds
            $parts = explode(":", $fields['secs']);
            $fields['secs'] = $parts[2] + 60 * $parts[1] + 60 * 60 * $parts[2];

            // insert into database
            $query = "
                INSERT INTO
                    commanders
                SET
                    `match`= {$matchid},
                    `team` = {$teamid},
                    `user` = {$userid}";
            foreach($fields as $key => $value) {
                $query .= ", `$key` = '$value'";
            }
            db_query($query);
            $team++;
        }

        /* Players */

        // Split matchstats into the two teams
        $teamstats = explode("<!-- Team 2 -->", $matchstats);
        $team = 0;
        foreach ($teamstats as $teamstat) {
            $regexp = '/getPlayerMatchInfo\(([0-9]+), ([0-9]+)\)/';
            preg_match_all($regexp, $teamstat, $players);
            $teamid = $teamids[$team];
            $players[2] = array_unique($players[2]);
            foreach($players[2] as $userid) {
                // read stats
                $playerstats = do_request('http://www.savage2replays.com/get_player_match_stats.php?aid=' . $userid . '&mid=' . $matchid);

                // get username and save in db
                $regexp = '$<span class=g12>([a-zA-Z0-9\-_\.,]*)</span>$';
                preg_match_all($regexp, $playerstats, $values);
                $username = $values[1][0];
                save_user($userid, $username);

                // parse all values into an array
                $regexp = '$ width=129 height=30 valign=top>([0-9,:\.]*)</td$';
                preg_match_all($regexp, $playerstats, $values);
        
                // set correct keys
                $fields = array_combine(
                    array('exp', 'pdmg', 'kills', 'assists', 'souls', 'npc', 'hp_healed', 'res', 'gold', 'hp_repaired', 'bdmg', 'razed', 'deaths', 'ignore',  'secs', 'ignore2'),
                    $values[1]);
                unset($fields['ignore']);
                unset($fields['ignore2']);

                // calculate duration in seconds
                $parts = explode(":", $fields['secs']);
                $fields['secs'] = $parts[2] + 60 * $parts[1] + 60 * 60 * $parts[2];

                // insert into database
                $query = "
                    INSERT INTO
                        actionplayers
                    SET
                        `match`= {$matchid},
                        `team` = {$teamid},
                        `user` = {$userid}";
                foreach($fields as $key => $value) {
                    $query .= ", `$key` = '$value'";
                }
                db_query($query);
            }
            $team++;
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
    
    echo "...done\n";
    $matchid++;
}
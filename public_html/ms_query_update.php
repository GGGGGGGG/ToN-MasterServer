<?php
include("../common/lib.php");
$nOnline = 0;
$data = array();
$result = db_query("select id, name, ip, port, num_conn AS numUsers, max_conn AS maxUsers, official, description from server");
while($row = mysqli_fetch_assoc($result)) {
	$row['location'] = "";
	$row['currentGameTime'] = "";
	$row['reserveForClanMatches'] = 0;
	$row['status'] = "";
	$row['minLevel'] = 0;
	$row['maxLevel'] = 9000;
	$data[$row['id']] = $row;
}
echo serialize($data);
db_close();
/*
plugins/gamestats/s2servers/s2servers.php
  'http://www.savage2.com/en/merc_temp/test/ms_query_update.php'

modules/mod_serverlist/helper.php
            $this->svr_list[$id] = array(
                                'id'                    => $id,
                                'name'                  => $this->formatColorCode(htmlspecialchars($svr['name'])),
                                'description'           => $this->formatColorCode(htmlspecialchars($svr['description'])),
                                'ip'                    => htmlspecialchars($svr['ip']),
                                'port'                  => htmlspecialchars($svr['port']),
                                'location'              => $this->formatColorCode($svr['location']),
                                'numUsers'              => (intval($svr['numUsers']) < 0) ? 0 : intval($svr['numUsers']),
                                'maxUsers'              => intval($svr['maxUsers']),
                                'official'              => intval($svr['official']),
                                'currentGameTime'       => htmlspecialchars($svr['currentGameTime']),
                                'reserveForClanMatches' => intval($svr['reserveForClanMatches']),
                                'status'                => htmlspecialchars($svr['status']),
                                'minLevel'              => intval($svr['minLevel']),
                                'maxLevel'              => intval($svr['maxLevel'])
                                );


mysql> describe server;
+-------------+-------------+------+-----+---------+-------+
| Field       | Type        | Null | Key | Default | Extra |
+-------------+-------------+------+-----+---------+-------+
| id          | int(11)     | NO   | PRI | 0       |       |
| ip          | varchar(32) | YES  | MUL | NULL    |       |
| port        | int(11)     | YES  |     | NULL    |       |
| num_conn    | int(11)     | YES  |     | NULL    |       |
| max_conn    | int(11)     | YES  |     | NULL    |       |
| name        | varchar(50) | NO   |     | NULL    |       |
| description | text        | YES  |     | NULL    |       |
| minlevel    | int(11)     | YES  |     | NULL    |       |
| maxlevel    | int(11)     | YES  |     | NULL    |       |
| official    | char(1)     | NO   |     | N       |       |
| updated     | datetime    | YES  |     | NULL    |       |
+-------------+-------------+------+-----+---------+-------+
*/
?>

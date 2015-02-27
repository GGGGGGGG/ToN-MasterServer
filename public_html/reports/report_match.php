<?php
$con = mysqli_connect('localhost', 'tools', trim(file_get_contents('/var/www/masterserver1.talesofnewerth.com/toolsdbp')), 'tools');
if(mysqli_connect_errno()) {
  die();
}

$action = $_REQUEST['action'];
$id = mysql_real_escape_string($_REQUEST['matchid']);
$time = mysql_real_escape_string($_REQUEST['time']);
$reason = mysql_real_escape_string($_REQUEST['reason']);
$password = $_REQUEST['access_verifier'];
$userip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
$reporter = mysql_real_escape_string($_REQUEST['reporter']);
$spamprotection = 'nospamplease';
$spamtest = mysql_real_escape_string($_REQUEST['spamtest']);
$cookieWorkAround = mysql_real_escape_string($_REQUEST['cookie_work_around']);


$secret = '3fff9689ec2ba46e12997fcc9944059cb8ff8345';

$password = sha1($password);

if (strlen($cookieWorkAround) > 1)
{
	$password = $cookieWorkAround;
}

/* cookie does not work
if (sha1($_COOKIE['report_password']) == $secret)
{
	$password = sha1($_COOKIE['report_password']);
}

else
{
	$cookiePassword = $password;
	$password = sha1($password);
}*/



if($action == 'reportGame' && $spamprotection == $spamtest && strlen($id) > 0)
{

	$sql = "INSERT INTO `tools`.`reports` (`id`, `match_id`, `time`, `gametime`, `reason`, `reporter`, `reporter_ip`) VALUES (NULL, '$id', NOW(), '$time', '$reason', '$reporter', '$userip')";
	$handle = mysqli_query($con, $sql) or die ("Reporting failed");
	print("Report worked. Thank you.\n<br>");
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
	print('Filldata for curl. Filldata for curl. Filldata for curl. Filldata for curl.');
}


else if($password == $secret && $action == 'listReports')
{
	/*$cookie_return_value = */setcookie('report_password', $cookiePassword, time()+2592000); //cookie expires after 30 days
	//print("setcookie('report_password', $cookiePassword, time()+2592000); Return Value: $cookie_return_value");
	

	$sql = 'SELECT match_id, count( match_id ) AS counted, MAX(time) AS maxtime FROM reports GROUP BY match_id ORDER BY maxtime DESC, counted DESC';
	$handle = mysqli_query($con, $sql);

	print('<html><header><title>Reports overviews</title></header><html><body><table border="1">');
	print("
	<tr>
		<td>match_id</td>
		<td>reports</td>
		<td>time</td>
		<td>View detailed reports</td>
	</tr>
	");

	while($result = mysqli_fetch_row($handle))
	{
		print("
		<tr>
			<td><a href=\"http://www.savage2replays.com/replay_dl.php?file=$result[0]\" target=\"_blank\">$result[0]</a></td>
			<td>$result[1]</td>
			<td>$result[2]</td>
			<td><a href=\"report_match.php?matchid=$result[0]&action=viewFullReport&cookie_work_around=3fff9689ec2ba46e12997fcc9944059cb8ff8345\">View Details</a></td>
		</tr>");
	}


	print('</table><a href="./report_match.php?action=allReports&cookie_work_around=3fff9689ec2ba46e12997fcc9944059cb8ff8345">View all Reports (takes a long time)</a></body></html>');
}

else if($password == $secret && $action == 'viewFullReport' && strlen($id) > 1)
{
	setcookie('report_password', $cookiePassword, time()+2592000); //cookie expires after 30 days
	$sql = "SELECT * FROM reports WHERE match_id = '$id' ORDER BY time DESC";	
	$handle = mysqli_query($con, $sql);

	print('<html><header><title>Detailed Report</title></header><html><body><table border="1">');
	print("
	<tr>
		<td>id</td>
		<td>match_id</td>
		<td>time</td>
		<td>gametime</td>
		<td>reason</td>
		<td>reporter</td>
		<td>reporter_ip</td>
	</tr>
	");
	while($result = mysqli_fetch_row($handle))
	{
		print("
		<tr>
			<td>$result[0]</td>
			<td><a href=\"http://www.savage2replays.com/replay_dl.php?file=$result[1]\" target=\"_blank\">$result[1]</a></td>
			<td>$result[2]</td>
			<td>$result[3]</td>
			<td>$result[4]</td>
			<td>$result[5]</td>
			<td>$result[6]</td>
		</tr>");
	}
	print('</table><a href="./report_match.php?action=listReports&cookie_work_around=3fff9689ec2ba46e12997fcc9944059cb8ff8345">Back</a></body></html>');

}


else if($password == $secret && $action == 'allReports')
{
	setcookie('report_password', $cookiePassword, time()+2592000); //cookie expires after 30 days
	$sql = 'SELECT * FROM reports ORDER BY time DESC';
	$handle = mysqli_query($con, $sql);
	
	print('<html><header><title>Full reports Report</title></header><html><body><a href="./report_match.php?action=listReports&cookie_work_around=3fff9689ec2ba46e12997fcc9944059cb8ff8345">Back</a><table border="1">');
	print("
	<tr>
		<td>id</td>
		<td>match_id</td>
		<td>time</td>
		<td>gametime</td>
		<td>reason</td>
		<td>reporter</td>
		<td>reporter_ip</td>
	</tr>
	");
	while($result = mysqli_fetch_row($handle))
	{
		print("
		<tr>
			<td>$result[0]</td>
			<td>$result[1]</td>
			<td>$result[2]</td>
			<td>$result[3]</td>
			<td>$result[4]</td>
			<td>$result[5]</td>
			<td>$result[6]</td>
		</tr>");
	}
	print('</table><a href="./report_match.php?action=listReports&cookie_work_around=3fff9689ec2ba46e12997fcc9944059cb8ff8345">Back</a></body></html>');
}

else
{
	print("Unknown Command\n");
}



?>

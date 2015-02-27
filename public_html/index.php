<?php
include("../common/lib.php");
$body = "\t<tr>\n".
	"\t\t<td>ID</td>\n".
	"\t\t<td>Name</td>\n\t\t".
	"\t\t<td>IP:Port</td>\n".
	"\t\t<td>Connections</td>\n".
	"\t\t<td>Description</td>\n".
	"\t</tr>\n\n\n";
$nOnline = 0;
$result = db_query("select id, name, ip, port, num_conn, max_conn, description from server WHERE updated > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
while($row = mysqli_fetch_assoc($result)) {
	$body = $body.
		"\t<tr>\n".
		"\t\t<td>{$row['id']}</td>\n".
		"\t\t<td>{$row['name']}</td>\n\t\t".
		"\t\t<td>{$row['ip']}".":"."{$row['port']}</td>\n".
		"\t\t<td>{$row['num_conn']}/{$row['max_conn']}</td>\n".
		"\t\t<td>{$row['description']}</td>\n".
		"\t</tr>\n\n\n";
	$nOnline = $nOnline + intval($row['num_conn']);
}
$header = 
"<html xmlns=\"http://www.w3.org/1999/xhtml\">\n\n<head>\n  <title>Hello!</title>\n</head>\n\n<body>\n<table>\n";
$footer = 
"<tr>\n<td colspan=5><b>Total Online: ".$nOnline."</td>\n</tr>\n</table>\n</body>\n\n</html>";
echo $header.$body.$footer;
db_close();
?>

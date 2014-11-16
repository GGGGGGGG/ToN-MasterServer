<?php

include("common/lib.php");
	
/* Sickened2 - if client needs an updateÉ */
$clientver = post_input("version");
$clientos = post_input("os);
$clientarch = post_input("arch");
$query = "select concat(major, '.', minor, '.', build, '.', revision) as ver
	from patches.version
	order by date limit 1";
	
$result = mysql_query($query);

if(!mysql_num_rows($result) == 1) {
	/* Something's terribly wrong */
	return array();	
} else {
	/* Return user data */
	$data = mysql_fetch_assoc($result);
	$curver = $data["ver"];
	if($clientver < $curver) {
		/* inform client of new patches */
		$query = "select name, concat(major, minor, build, revision) as version, os, arch from patches.version_check 
			where version=$curver 
			ORDER BY name";
		$result = mysql_query($query);
		$nRows = mysql_num_rows($result);
		$res = array();
		for(i=0; $i < $nRows; ++$i) {
			array_push($res, array("name" => name, "version" => version, "os" => os, "arch" => arch));
		}
		echo serialize($res);
	}
}
?>
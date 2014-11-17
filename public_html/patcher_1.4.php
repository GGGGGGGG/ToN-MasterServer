<?php

include("../common/lib.php");
	
/* Sickened2 - if client needs an updateÉ */
$clientver = post_input("version");
if($clientver == "") return;
$clientos = post_input("os");
$clientarch = post_input("arch");
patchesdb_open();
$query = "select concat(major, '.', minor, '.', build, '.', revision) as ver
	from patches.version
	order by date limit 1";
	
$result = db_query($query);

if(mysqli_num_rows($result) != 1) {
	/* Something's terribly wrong */
	return;	
} else {
	/* Return user data */
	$data = mysqli_fetch_assoc($result);
	$curver = $data["ver"];
	if($clientver == $curver) {
		echo serialize(array("version" => $curver));
	}
	else if($clientver < $curver) {
		/* inform client of new patches */
		$query = "select name, concat(major, '.', minor, '.', build, '.', revision) as version, os, arch from patches.version_check 
			where concat(major, '.', minor, '.', build, '.', revision)=\"$curver\" 
			ORDER BY name";
		$result = db_query($query);
		$nRows = mysqli_num_rows($result);
		$res = array();
		for($i=0; $i < $nRows; ++$i) {
			$data = mysqli_fetch_assoc($result);
			$name = $data["name"];
			$version = $data["version"];
			$os = $data["os"];
			$arch = $data["arch"];
			array_push($res, array("name" => $name, "version" => $version, "os" => $os, "arch" => $arch));
		}
		$res["version"] = $curver;
		echo serialize($res);
	}
}
?>

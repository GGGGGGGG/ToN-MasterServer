<?php

include("../common/proxy.php");

$f = $_GET['f'];
if(strcmp($f, 'get') !== 0) return "";
$nickname = $_GET['nickname'];
return persist_test_proxy($nickname);

?>

<?php

include("../common/proxy.php");

$nickname = $_GET['nickname'];
return friend_info_proxy($nickname);

?>

<?php
require_once 'HTTP/Request2.php';

function auth_proxy($email, $password) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$r->addPostParameter(array('f' => 'auth', 'email' => $email, 'password' => $password));
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array('error' => 'Error in receiving response from official masterserver :/');
}

function get_online_proxy() {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/svr_request_pub.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$r->addPostParameter(array('f' => 'get_online'));
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();	
}

function c_conn_proxy($c_conn) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/svr_request_pub.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$r->addPostParameter(array('f' => 'c_conn', 
				'account_id' => $c_conn['account_id'], 
				'server_id' => $c_conn['server_id'], 
				'num_conn' => $c_conn['num_conn'], 
				'cookie' => $c_conn['cookie'], 
				'ip' => $c_conn['ip']));
	try {
		$body = $r->send()->getBody();
		if(strcmp($body, 'N;') == 0) return array();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();
}
?>

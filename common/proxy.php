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

function set_online_proxy($set_online) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/svr_request_pub.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$params = array('f' => 'set_online');
	while(list($key, $value) = each($set_online)) {
		if($value != "")
			$params[$key] = $value;
	}
	$r->addPostParameter($params);
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

function c_disc_proxy($account_id, $server_id) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$r->addPostParameter(array('f' => 'c_disc',
				'account_id' => $account_id,
				'server_id' => $server_id));
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();	
}

function set_online_ids_proxy($set_online_ids) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$params = array('f' => 'set_online_ids');
	while(list($key, $value) = each($set_online_ids)) {
		if($value != "") {
			if(strcmp($key, "account_id") !== 0)
				$params[$key] = $value;
			else {
				for($i = 0; $i < count($set_online_ids['account_id']); ++$i)
					$params["account_id[{$i}]"] = $set_online_ids['account_id'][$i];
			}
		}
	}
	$r->addPostParameter($params);
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();
}

function get_item_list($account_id) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$r->addPostParameter(array('f' => 'item_list',
				'account_id' => $account_id));
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();	
}

function nick2id_proxy($nicknames) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$params = array('f' => 'nick2id');
	for($i = 0; $i < count($nicknames); ++$i)
		$params["nickname[{$i}]"] = $nicknames[$i];
	$r->addPostParameter($params);
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();	
}

function get_all_stats_proxy($account_ids) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$params = array('f' => 'get_all_stats');
	for($i = 0; $i < count($account_ids); ++$i)
		$params["account_id[{$i}]"] = $account_ids[$i];
	$r->addPostParameter($params);
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();	
}

function new_buddy_proxy($account_id, $buddy_id) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$params = array('f' => 'new_buddy', 'account_id' => $account_id, 'buddy_id' => $buddy_id);
	$r->addPostParameter($params);
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();	
}

function remove_buddy_proxy($account_id, $buddy_id) {
$masterserver = 'http://masterserver.savage2.s2games.com/irc_updater/irc_requester.php';
	$r = new Http_Request2($masterserver, Http_Request2::METHOD_POST);
	$params = array('f' => 'remove_buddy', 'account_id' => $account_id, 'buddy_id' => $buddy_id);
	$r->addPostParameter($params);
	try {
		$body = $r->send()->getBody();
		return unserialize($body);
	} catch (Http_Request2_Exception $ex) {
		//echo $ex;
	}
	return array();	
}


?>

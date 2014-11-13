<?php

/* Show all errors */
ini_set('display_errors', 'On');
error_reporting(E_ALL);

/* Load config */
include("config.php");

/* Get input values */
function get_input($key, $default = "")
{
	if(!isset($_GET[$key]))
		return mysql_real_escape_string($default);
	return mysql_real_escape_string($_GET[$key]);
}

function post_input($key, $default = "")
{
	if(!isset($_POST[$key]))
		return mysql_real_escape_string($default);
	return mysql_real_escape_string($_POST[$key]);
}

function post_serialized($key, $default = array())
{
	if(!isset($_POST[$key]))
		$result = $default;
	else
		$result = unserialize(preg_replace('/\s\s+/', '', $_POST[$key]));
	return db_escape($result);
}

/* Open database connection */
function db_open()
{
	global $config;
	
	$db = $config['db'];
	
	mysql_connect($db['host'], $db['username'], $db['password'])
		or die("No connection to database");
	
	mysql_select_db($db['database'])
		or die("Cannot select database");
}

/* Send query */
function db_query($query) {
	$result = mysql_query($query);
	if (!$result)
		throw new Exception(mysql_error());
	return $result;
}

/* Receive query as array */
function db_query_array($query) {
    $result = db_query($query);
    $array = array();
    while ($line = mysql_fetch_array($result)) {
        $array[$line[0]] = $line[1];
    }
    return $array;
}

/* Escape anything */
function db_escape($values) {
	if (is_array($values)) {
		foreach ($values as $key => $value) {
			$values[$key] = db_escape($value);
		}
	}
	else if ($values === null) {
		$values = 'NULL';
	}
	else if (is_bool($values)) {
		$values = $values ? 1 : 0;
	}
	else if (!is_numeric($values)) {
		$values = mysql_real_escape_string($values);
	}
	return $values;
}

/* Dispatch request into a handle function */
function dispatch_request($valid_actions)
{
	/* Parse request */
	$action = post_input("f");	
	if(!in_array($action, $valid_actions))
		die("Wrong action");
	
	/* Dispatch request */
	$func = "handle_".$action;
	$data = $func();
	
	/* Display output */
	echo serialize($data);		
}

/* Open database connection */
db_open();

?>
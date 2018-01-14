<?php

/* Show all errors */
ini_set('display_errors', 'On');
error_reporting(E_ALL);

/* Load config */
include("config.php");

/* Get input values */
function get_input($key, $default = "")
{
	global $dbcon;
	$returnValue = null;
	$getValue = $_GET[$key];

    if(!isset($getValue)) {
        $returnValue = mysqli_real_escape_string($dbcon, $default);
    } else {
        if(is_array($getValue)){
            foreach ($getValue as $value)
            {
                $getValue[$value] = mysqli_real_escape_string($dbcon, $value);
            }
            $returnValue = $getValue;
        } else {
            $returnValue = mysqli_real_escape_string($dbcon, $getValue);
        }
    }

    return $returnValue;
}

function post_input($key, $default = "")
{
	global $dbcon;
	$returnValue = null;
	$postValue = $_POST[$key];

	if(!isset($postValue)) {
        $returnValue = mysqli_real_escape_string($dbcon, $default);
    } else {
		if(is_array($postValue)){
			foreach ($postValue as $value)
			{
				$postValue[$value] = mysqli_real_escape_string($dbcon, $value);
			}
			$returnValue = $postValue;
		} else {
            $returnValue = mysqli_real_escape_string($dbcon, $postValue);
        }
	}

	return $returnValue;
}

function post_serialized($key, $default = array())
{
	global $dbcon;
	$postValue = $_POST[$key];

	if(!isset($postValue)) {
        $result = $default;
    }
	else {
		if (is_array($postValue)){
			foreach ($postValue as $value)
			{
                $postValue[$value] = mysqli_real_escape_string($dbcon, $value);
			}
		}
        $result = unserialize(preg_replace('/\s\s+/', '', $postValue));
	}

	return $result;
}

/* Open database connection */
function db_open()
{
	global $config;
	global $dbcon;
	
	$db = $config['db'];
	
	$dbcon = mysqli_connect($db['host'], $db['username'], $db['password'], $db['database'])
		or die("No connection to database");
}

function patchesdb_open()
{
	global $config;
	global $patchesdbcon;
	
	$db = $config['db'];
	
	$patchesdbcon = mysqli_connect($db['host'], $db['username'], $db['password'], $db['patchesdb'])
		or die("No connection to database");
}

function patchesdb_query($query) {
	global $patchesdbcon;
	$result = mysqli_query($patchesdbcon, $query);
	if (!$result)
		throw new Exception(mysqli_error($patchesdbcon));
	return $result;
}

function patchesdb_close()
{
	global $patchesdbcon;
	mysqli_close($patchesdbcon);
}

function db_close()
{
	global $dbcon;
	mysqli_close($dbcon);
}


/* Send query */
function db_query($query) {
	global $dbcon;
	$result = mysqli_query($dbcon, $query);
	if (!$result)
		throw new Exception(mysqli_error($dbcon));
	return $result;
}

/* Receive query as array */
function db_query_array($query) {
    $result = db_query($query);
    $array = array();
    while ($line = mysqli_fetch_array($result)) {
        $array[$line[0]] = $line[1];
    }
    return $array;
}

/* Dispatch request into a handle function */
function dispatch_request($valid_actions)
{
	/* Parse request */
	$action = post_input("f");	
	if(!in_array($action, $valid_actions)) {
		error_log($action, 3, '/var/tmp/ton.log');
        die("Wrong action");
    }
	
	/* Dispatch request */
	$func = "handle_".$action;
	$data = $func();
	
	/* Display output */
	echo serialize($data);		
}

/* Open database connection */
db_open();

?>

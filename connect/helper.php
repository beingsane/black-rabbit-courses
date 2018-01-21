<?php
include_once('brc.php');
include_once('database.php');
include_once('config.php');

/**
 *	Helpers
 */
function md5It($string, $responseType) {
	// md5 string
	$response = md5($string);

	// is response json?
	if($responseType == 'json') {
		jsonIt($response);
	} else {
		return $response;
	}

}

function jsonIt($response) {
	print_r(json_encode($response));
	exit();
}

/**
*	Installed Helpers
*/


function checkConnection($host, $table, $user, $pass, $responseType) {
	// turn off error reporting
	error_reporting(0);
	// response
	$response = array(
		'error' => false,
		'msg'   => '',
		'extra' => '',
	);
	$mysqli = mysqli_init();
	if (!$mysqli) {
		$response['msg'] = 'mysqli_init failed';
		$response['error'] =  true;
	}

	if (!$mysqli->options(MYSQLI_INIT_COMMAND, 'SET AUTOCOMMIT = 0')) {
		$response['msg'] = 'Setting MYSQLI_INIT_COMMAND failed';
		$response['error'] =  true;
	}

	if (!$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
		$response['msg'] = 'Setting MYSQLI_OPT_CONNECT_TIMEOUT failed';
		$response['error'] =  true;
	}

	if (!$mysqli->real_connect($host, $user, $pass, $table)) {
		$connectError = mysqli_connect_error();
		$response['msg'] = 'Connect Error: ' . $connectError;
		$response['error'] =  true;
		// if the database is non-existant..
		if(substr($connectError, 0, 16) == 'Unknown database') {
			$response['extra'] = 'showCreateDB';
		}
	} else {
		$response['msg'] = 'success';
		$mysqli->close();
	}

	// is response json?
	if($responseType == 'json') {
		jsonIt($response);
	} else {
		return $response;
	}
}

/**
 *	Login/Session Helpers
 */
function pass_encrypt($text){
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function pass_decrypt($text){
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

function startsession($user_info, $db){
	session_start();

	$_SESSION['loggedin']		= 1;
	$_SESSION['is_admin']		= $user_info[0]['is_admin'];
	$_SESSION['uid']				= $user_info[0]['id'];
	$_SESSION['name']				= $user_info[0]['name'];
	$_SESSION['username']		= $user_info[0]['username'];
	$_SESSION['email']			= $user_info[0]['email'];
	$_SESSION['last_login']	= $user_info[0]['last_login'] == '0000-00-00 00:00:00' ? date('Y-m-d H:i:s') : $user_info[0]['last_login']; // first time then just say last login was 2 seconds ago and so on..
	$_SESSION['timeout']		= time();

	// update user table
	$user = array(
		'last_login' => date('Y-m-d H:i:s'),
	);
	$db->update('users', $user, 'id='.$user_info[0]['id']);

	timeoutsession();
}

function checksession(){
	session_start();
	if($_SESSION['loggedin'] == 1){
		timeoutsession();
	}
}

function timeoutsession(){
	session_start();
	echo 'hello';
	exit();
	if($_SESSION['timeout'] + $default_user_idle < time()){
		session_destroy();
		session_start();
		$_SESSION['msg'] = 'You have been logged out due to inactivity.';
		$_SESSION['msg-type'] = 'info';
		header('Location: index.php');
		exit();
	} else {
		$_SESSION['timeout']	= time();
	}
}

function endsession(){
	session_start();
	session_destroy();
	session_start();
	$_SESSION['msg'] = 'You are now logged out!';
	$_SESSION['msg-type'] = 'info';
	header('Location: index.php');
	exit();
}

function isAdmin(){
	checksession();
	if($_SESSION['is_admin'] == 0){
		$_SESSION['msg'] = 'You do not have access to this page.';
		$_SESSION['msg-type'] = 'danger';
		header('Location: index.php');
		exit();
	}
}

function isThereAnAdminUser(){
	// connect to datbase
	$db = new Database(brc::DBHOST, brc::DBTABLE, brc::DBUSER, brc::DBPASS);

	$response = $db->custom_query('SELECT id FROM users', true);

	return !empty($response) ? 1 : 0;
}

/**
 *	Data Helpers
 */
function setKeyDBData($data, $field){
	$formatted = array();
	if($data && $field && is_array($data)){
		foreach($data as $key => $value){
			if(isset($value->$field)){
				$formatted[$value->$field] = $value;
			}
		}
	}

	return $formatted;
}

/**
 *	Profile Helpers
 */
function checkUsername($username) {
	// connect to datbase
	$db = new Database(brc::DBHOST, brc::DBTABLE, brc::DBUSER, brc::DBPASS);

	$username = $db->sanitize($username);
	$response = $db->custom_query('SELECT id FROM users WHERE username ="' . $username . '" LIMIT 1', true);

	return !empty($response) ? 1 : 0;
}

?>

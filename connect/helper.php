<?php
include_once('brc.php');
include_once('database.php');
include_once('config.php');

session_start();

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
	return password_hash($text, PASSWORD_DEFAULT, ['cost' => 12]);
}

function pass_decrypt($text, $hash){
	return password_verify($text, $hash);
}

function startsession($user){
	// connect to datbase
	$db = new Database(brc::DBHOST, brc::DBTABLE, brc::DBUSER, brc::DBPASS);

	session_start();

	$_SESSION['loggedin']		= 1;
	$_SESSION['is_admin']		= (int) $user->is_admin;
	$_SESSION['uid']				= $user->id;
	$_SESSION['name']				= $user->name;
	$_SESSION['username']		= $user->username;
	$_SESSION['email']			= $user->email;
	// first time then just say last login was 2 seconds ago and so on..
	$_SESSION['last_login']	= $user->last_login == '0000-00-00 00:00:00' ? date('Y-m-d H:i:s') : $user->last_login;
	$_SESSION['timeout']		= time();

	// update user table
	$user_update = array(
		'last_login' => date('Y-m-d H:i:s'),
	);
	$db->update('users', $user_update, 'id=' . $user->id);

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

	if($_SESSION['timeout'] + $GLOBALS['default_user_idle'] < time()){
		session_destroy();
		session_start();
		$_SESSION['msg'] = 'You have been logged out due to inactivity.';
		$_SESSION['msg-type'] = 'info';
		header('Location: /index.php');
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
	header('Location: /index.php');
	exit();
}

function isAdmin(){
	checksession();
	if($_SESSION['is_admin'] === 0){
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

function addAdminUser($post){
	// simple validation for now
	if(empty($post['name']) || empty($post['username']) || empty($post['email']) || empty($post['password'])) {
		// check post data
		$_SESSION['msg'] = 'Please fill in all form fields.';
		$_SESSION['msg-type'] = 'warning';

		// go admin login
		header('Location: /admin/new-admin-user.php');
		exit();
	}

	// connect to datbase
	$db = new Database(brc::DBHOST, brc::DBTABLE, brc::DBUSER, brc::DBPASS);

	// insert new admin user
	$admin_record = array (
		'name' 						=> $post['name'],
		'username'				=> $post['username'],
		'email'						=> $post['email'],
		'password'				=> pass_encrypt($post['password']),
		'access_code'			=> '',
		'is_admin' 				=> 1,
		'created'					=> date('Y-m-d H:i:s'),
		'updated'					=> '0000-00-00 00:00:00',
		'last_login'			=> '0000-00-00 00:00:00',
		'active'					=> 1,
	);

	$response = $db->insert('users', $admin_record);

	$_SESSION['msg'] = 'Admin user saved!';
	$_SESSION['msg-type'] = 'success';

	// go admin login
	header('Location: /admin/');
	exit();
}

function loginAdmin($post){
	$passWrong = false;

	// do we have what we need?.. if not..
	if(!isset($post['username']) || !isset($post['password']) || empty($post['username']) || empty($post['password'])) {
		$passWrong = true;
	}

	// continue down the rabbit hole
	if($passWrong === false) {
		// check admin username
		$response = checkAdminUsername($post['username']);

		// username exists?
		if(!empty($response)){
			$pass = pass_decrypt($post['password'], $response->password);

			// if we pass.. log the user in
			if($pass) {
				// log in user session start
				startsession($response);
				$_SESSION['msg'] = 'You are now logged in!';
				$_SESSION['msg-type'] = 'success';

				header('Location: /admin/');
				exit();
			} else {
				$passWrong = true;
			}
		} else {
			$_SESSION['msg'] = 'Sorry, that username does not exist, or does not have administrator privileges...';
			$_SESSION['msg-type'] = 'warning';
			header('Location: /admin/login.php');
			exit();
		}
	}

	// if we got here.. they did something wrong
	if($passWrong) {
		$_SESSION['msg'] = 'Please remember to type your username and password correctly.';
		$_SESSION['msg-type'] = 'warning';
		header('Location: /admin/login.php');
		exit();
	}
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
 *	Users helpers
 */
function checkAdminUsername($username) {
	// connect to datbase
	$db = new Database(brc::DBHOST, brc::DBTABLE, brc::DBUSER, brc::DBPASS);

	$username = $db->sanitize($username);
	$response = $db->custom_query('SELECT * FROM users WHERE username ="' . $username . '" && is_admin = 1 LIMIT 1', true);

	return $response;
}

?>

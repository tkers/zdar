<?php
$cmd = rawurldecode($_GET['cmd']);

ob_start();
session_start();

$headers = '';

//MODE / CMD / AREA / INVENTORY / STATS > K/V pairs

$USERNAME = '';
$INPUT = '';
$resume = false;

if(!isset($_SESSION['username'])){ // Not logged in
	if(isset($_GET['loadgame'])){ // First request
		echo "\n".'To start playing, enter your character name and press ENTER...';
	}
	else{ // Entered username
		// Add PW protection?
		$_SESSION['username'] = $cmd;
		$USERNAME = $_SESSION['username'];
		$resume = true;
	}
}
else{ // Running game
	$USERNAME = $_SESSION['username'];
	
	if(isset($_GET['loadgame'])){ // Resume
		$resume = true;
	}
	else{
		$INPUT = strtolower($cmd); // Existing game
	}
}

if($USERNAME != ''){
	include('servercookie.php');
	$savegames = new Servercookie('savegames');
	if($savegames->exists($USERNAME)){
		$sav = $savegames->read($USERNAME);
	}
	else{
		$sav = array();
		$sav['area'] = -1;
		$sav['items'] = array();
	}
	$oldArea = $sav['area'];
}






function addHeader($k, $v = ''){
	global $headers;
	$headers .= rawurlencode($k).':'.rawurlencode($v).':';
}

function getArea(){
	global $oldArea;
	return $oldArea;
}

function setArea($area){
	global $sav;
	$sav['area'] = $area;
}

function getItem($item, $default = 0){
	global $sav;
	if(array_key_exists($item, $sav['items'])){
		return $sav['items'][$item];
	}
	return $default;
}

function setItem($item, $status){
	global $sav;
	$sav['items'][$item] = $status;
}




if($resume && getArea() != -1){ // resume
	echo "\n".'<b>[...]</b>';
	if(array_key_exists('history', $sav)){
		echo "\n\n".$sav['history'];
	}
}





# Common commands
if($INPUT == 'cls'){
	echo '&nbsp;';
	addHeader('cls');
}
else if($INPUT == 'clear progress'){
	$savegames->delete($USERNAME);
	$USERNAME = '';
	session_destroy();
	echo 'Progress cleared.';
	echo '<br><br>';
	addHeader('reset');
}
else if($INPUT == 'logout' || $INPUT == 'sign out' || $INPUT == 'quit'){
	session_destroy();
	addHeader('reset');
	echo 'Signed out...<br><br>';
}
else if($INPUT == 'about'){
	echo 'Project Zdar [Version 0.1]<br>';
	echo 'Copyright (c) 2011 Tijn Kersjes<br>';
	echo 'All rights reserved';
}
else if($INPUT == 'help'){
	echo 'Not yet available...';
}







if(ob_get_length() == 0){
	require('world.php');
	if(!$resume && ob_get_length() != 0){
		$sav['history'] = ob_get_contents();
	}
}

if(ob_get_length() == 0){
	echo '** Unknown command **';
}

if($USERNAME != ''){
	$savegames->write($USERNAME, $sav);
}

$body = ob_get_contents();
ob_end_clean();

$body = str_replace("\n", '<br>', $body);

echo substr($headers, 0, -1).chr(13).chr(10).$body;
?>
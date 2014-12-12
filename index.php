<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<title>Zdar</title>

<link rel="icon" type="image/png" href="favicon.png" />

<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="icon.png" />
<!-- <link rel="apple-touch-icon-precomposed" sizes="114x114" href="touch-icon-iphone-retina2.png" /> -->

<link href="default.css" rel="stylesheet" type="text/css" media="all">

<script type="text/javascript" src="XHR.js"></script>

<script type="text/javascript">
_wait = true;

prevCommandN = 0;
prevCommand = new Array();
for(i = 1; i <= 20; i++){
	prevCommand[i] = "";
}

function saveCommand(cmd){
	for(i = 20; i > 1; i--){
		prevCommand[i] = prevCommand[i-1];
	}
	prevCommand[1] = cmd;
	prevCommandN = 0;
}

function checkReturn(event){
	/*if(event.keyCode == "13"){
		output("<br><br>" + "<b>&rsaquo;&nbsp;" + _in.value + "</b>");
		_in.value = "";
		return;
	}*/

	if(event.keyCode == "13" && !_wait && _in.value != ""){
		output("<br><br>" + "<b>&rsaquo;&nbsp;" + _in.value + "</b>");
		_wait = true;
		_xhr.get("cmd.php?cmd=" + escape(_in.value) + "&" + timestamp(), "response");
		saveCommand(_in.value);
		_in.value = "";
	}
	else if(event.keyCode == "38" && prevCommandN < 20 && prevCommand[prevCommandN + 1] != ""){
		prevCommandN++;
		_in.value = prevCommand[prevCommandN];
	}
	else if(event.keyCode == "40" && prevCommandN > 1 && prevCommand[prevCommandN - 1] != ""){
		prevCommandN--;
		_in.value = prevCommand[prevCommandN];
	}
}

function processHeaders(head){
	var parts = head.split(":");
	for(i = 0; i < parts.length; i += 2){
		var key = unescape(parts[i]);
		var val = unescape(parts[i + 1]);
		if(key == "cls"){
			_out.innerHTML = "";
			return true;
		}
		if(key == "username"){
			//document.getElementById('username').innerHTML = val+"@zdar&rsaquo;";
		}
		else if(key == "reset"){
			//document.getElementById('username').innerHTML = "anonymous";
			_xhr.get("cmd.php?loadgame" + "&" + timestamp(), "loaded");
		}
	}
	return true;
}

function response(res){
	parts = res.responseText.split("\r\n");
	
	toscreen = true;
	if(parts[0] != ""){
		toscreen = processHeaders(parts[0]);
	}
	
	if(toscreen){
		output("<br>" + parts[1]);
	}
	
	_wait = false;
}

function output(text){
	_out.innerHTML = _out.innerHTML + text;
	scrollAllDown();
}

function scrollAllDown(){
	document.getElementById('movedown').scrollTop += 5000;
	//window.scrollBy(0, 5000);
}

function loaded(res){
	parts = res.responseText.split("\r\n");
	if(parts[0] != ""){
		processHeaders(parts[0]);
	}
	output("<br>" + parts[1]);
	_wait = false;
	
	blink = false;
	document.getElementById('inputhider').style.visibility = "visible";
	_in.focus();
	scrollAllDown();
}

function timestamp(){ //Destroy that evil cache!
	var a = new Date();
	var b = a.getTime();
	return b;
}

var blink = true;
function blinkMe(){
	var a = document.getElementById('blink');
	if(a.innerHTML == "" && blink)
		a.innerHTML = "&#9608;"; //&#9608;
	else
		a.innerHTML = "";
	
	if(blink)
		window.setTimeout(blinkMe, 500);
}

function run(){
	_xhr.get("cmd.php?loadgame" + "&" + timestamp(), "loaded");
}

window.onload = function(){
	_in = document.getElementById('cmd');
	_out = document.getElementById('output');	
	_xhr = new XHR(); //No fallback, because bananas

	scrollAllDown();
	
	window.setTimeout(blinkMe, 500);
	window.setTimeout(run, 3000);
}
</script>

</head>

<body onclick="document.getElementById('cmd').focus();">

<div class="wrap">
<div id="movedown">

<div id="output">
Project Zdar <b>[Version 0.3]</b><br>
Copyright (c) 2013 Tijn Kersjes<br>
All rights reserved<span id="blink"></span>
</div>

<div id="inputbar">
<span id="inputhider" style="visibility: hidden">
<span style="float:left; line-height: 1;">&rsaquo;</span><div class="cmdwrapper"><input type="text" id="cmd" onkeydown="checkReturn(event)"></div>
</span>
</div>

</div>
</div>

</body>

</html>
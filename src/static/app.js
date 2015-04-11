_wait = true;

prevCommandN = 0;
prevCommand = new Array();
for (i = 1; i <= 20; i++) {
	prevCommand[i] = undefined;
}

function processCommand(cmd) {
	setTimeout(function () {

		output("<br>Que?");

		_wait = false;
	}, 1000);
}

function saveCommand(cmd) {
	for(i = 20; i > 1; i--){
		prevCommand[i] = prevCommand[i - 1];
	}
	prevCommand[1] = cmd;
}

function handleKeyPress(event) {

	if (event.keyCode == "13")
		return handleEnter(event);

	if (event.keyCode == "38")
		return handleUp(event);

	if (event.keyCode == "40")
		return handleDown(event);
}

function handleEnter(event) {

	if (_wait || _in.value == "")
		return;

	var cmd = _in.value;
	_in.value = "";

	prevCommandN = 0;
	saveCommand(cmd);

	output("<br><br>" + "<b>&rsaquo;&nbsp;" + cmd + "</b>");

	_wait = true;
	processCommand(cmd);
}

function handleUp(event) {
	if (prevCommandN >= 20 || !prevCommand[prevCommandN + 1])
		return;

	prevCommandN++;
	_in.value = prevCommand[prevCommandN];
}

function handleDown(event) {
	if (prevCommandN <= 1 || !prevCommand[prevCommandN - 1])
		return;

	prevCommandN--;
	_in.value = prevCommand[prevCommandN];
}

function output(text) {
	_out.innerHTML = _out.innerHTML + text;
	scrollAllDown();
}

function scrollAllDown() {
	document.getElementById('movedown').scrollTop += 5000;
}

function run() {

	// @TODO should load saved game here
	// @TODO output previous saved output

	_wait = false;

	blinking = false;
	document.getElementById('inputhider').style.visibility = "visible";
	_in.focus();
	scrollAllDown();
}


var blinking = true;
function blinkCursor() {
	var a = document.getElementById('blink');
	if(a.innerHTML == "" && blink)
		a.innerHTML = "&#9608;";
	else
		a.innerHTML = "";

	if (blinking)
		window.setTimeout(blinkCursor, 500);
}

window.onload = function() {
	_in = document.getElementById('cmd');
	_out = document.getElementById('output');

	scrollAllDown();

	window.setTimeout(blinkCursor, 500);
	window.setTimeout(run, 3000);
}

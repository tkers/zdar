prevCommandN = 0;
prevCommand = new Array();
for (i = 1; i <= 20; i++) {
	prevCommand[i] = undefined;
}

function processCommand(cmd) {

	cmd = cmd.toLowerCase();
	words = cmd.split(" ");

	if (words[0] === "look") {
		return output(AREA.commands && AREA.commands.look || AREA.description);
	}

	if (words[0] === "go" && words.length > 1) {
		if (AREA.exits && words[1] in AREA.exits) {
			if (AREA.exits[words[1]] instanceof Function)
				return AREA.exits[words[1]]();
			else {
				AREA = WORLD.areas[AREA.exits[words[1]]];
				return output(AREA.description);
			}
		}
	}

	if (AREA.commands && cmd in AREA.commands) {
		if (AREA.commands[cmd] instanceof Function) {
			return AREA.commands[cmd]();
		}
		else {
			return output(AREA.commands[cmd]);
		}
	}

	if (WORLD.commands && cmd in WORLD.commands) {
		if (WORLD.commands[cmd] instanceof Function) {
			return WORLD.commands[cmd]();
		}
		else {
			return output(WORLD.commands[cmd]);
		}
	}

	output("You scratch your head.");
}

function saveCommand(cmd) {
	for(i = 20; i > 1; i--){
		prevCommand[i] = prevCommand[i - 1];
	}
	prevCommand[1] = cmd;
}

function handleKeyPress(event) {

	if (_end)
		return;

	if (event.keyCode == "13")
		return handleEnter(event);

	if (event.keyCode == "38")
		return handleUp(event);

	if (event.keyCode == "40")
		return handleDown(event);
}

function handleEnter(event) {

	if (_in.value == "")
		return;

	var cmd = _in.value;
	_in.value = "";

	prevCommandN = 0;
	saveCommand(cmd);

	output("<b>&rsaquo;&nbsp;" + cmd + "</b>", true);

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

singleLine = false;
function output(text, userText) {
	_out.innerHTML = _out.innerHTML + (singleLine ? "<br>" : "<br><br>") + text.split("\n").join("<br>");
	scrollAllDown();
	singleLine = !!userText;
}

function scrollAllDown() {
	document.getElementById('movedown').scrollTop += 5000;
}

function run() {

	// @TODO should load saved game here
	// @TODO output previous saved output
	output(WORLD.intro);
	output(AREA.description);

	blinking = false;
	document.getElementById('inputhider').style.visibility = "visible";
	_in.focus();
	scrollAllDown();
}

var _end = false;
function gameover() {
	output("<hr><br><center>G&nbsp;A&nbsp;M&nbsp;E &nbsp; O&nbsp;V&nbsp;E&nbsp;R</center>")
	_end = true;
	document.getElementById('inputhider').style.visibility = "hidden";
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

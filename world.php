<?php

# New game
if(getArea() == -1){
	setArea(1);
	echo 'You are Captain '.$USERNAME.', self-employed astronaut.
	
	In one of your many daring missions, your space craft is intercepted by an unknown starship. Seeing no heroic way out of this situation whatsoever, you rush towards the escape pods. You manage to get away safely, only seconds before your beloved ship is completely torn apart by the starship\'s ion cannons.
	
	As your escape pod automatically plots a course to the nearest planet, you are able to distinguish a yellow, triangle-shaped symbol on the hull of the hostile starship.
	
	A few hours later, with the starship far out of sight, you crash onto the surface of a planet unknown to you. The airlock opens with a hiss, and you step outside...';
}


# Game

function look1(){
	echo 'Your escape pod is still lying in the crater it created on impact. It looks badly damaged (both the pod and the surface).';
}
if(getArea() == 1){ // Crash Site
	if($INPUT == 'look'){
		echo 'You take a look around. There seems to be nothing but dust on this planet, but you are able to make out 2 paths leading to the north and the east...';
	}
	else if($INPUT == 'enter' || $INPUT == 'enter pod' || $INPUT == 'enter escape pod'){
		setArea(2);
		echo 'You enter your escape pod.'."\n\n";
		echo 'Flashing lights near the control panel indicate that the pod was badly damaged in the crash.';
		if(getItem(1) == 0){
			echo ' Fin is swimming around in his fishbowl, not looking too concerned about the situation.';
		}
	}
	else if(go('north')){
		if(getItem(3) == 0){
			echo 'The road to the north is blocked by a rock.';
		}
		else{
			setArea(4);
			look4();
		}
	}
	else if(go('east')){
		setArea(3);
		look3();
	}
	else if(getItem(2) == 1 && getItem(3) == 0 && (startsWith('use blaster') || startsWith('shoot rock'))){
		echo 'You skillfully aim your blaster at the rock, and pull the trigger. The rock explodes, allowing you to pass.';
		setItem(3, 1);
	}
}

function look2(){
	echo 'Flashing lights near the control panel indicate that the pod was badly damaged in the crash.';
	if(getItem(1) == 0){
		echo ' Fin is swimming around in his fishbowl, not looking too concerned about the situation.';
	}
}
if(getArea() == 2){ // Inside Escape Pod
	if($INPUT == 'look'){
		look2();
	}
	else if($INPUT == 'take' || $INPUT == 'take fin' || $INPUT == 'take fish' || $INPUT == 'take bowl' || $INPUT == 'take fishbowl'){
		if(getItem(1) == 0){
			echo 'You take Fin with you.';
			setItem(1, 1);
		}
		else{
			echo 'You are already carrying Fin\'s fishbowl.';
		}
	}
	else if($INPUT == 'exit' || $INPUT == 'exit pod' || $INPUT == 'exit escape pod'){
		setArea(1);
		echo 'You step trough the airlock, and are back outside again.';
	}
}

function look3(){
	echo 'You arrive at a place which appears to be a garbage dump. There could be some usefull tools lying around.';
}
if(getArea() == 3){
	if($INPUT == 'look'){
		look3();
	}
	else if(go('west')){
		setArea(1);
		look1();
	}
	else if($INPUT == 'search' || startsWith('search ')){
		if(getItem(2) == 0){
			echo 'Between piles of garbage, you find an old blaster.';
			setItem(2, 1);
		}
		else{
			echo 'You search for anything usefull, but don\'t find anything.';
		}
	}
}

function look4(){
	echo 'You found a small, dusty and forgotten spaceship. It looks capable enough to get you off this planet.';
}
if(getArea() == 4){
	if($INPUT == 'look'){
		look4();
	}
	else if(go('south')){
		setArea(1);
		look1();
	}	
	else if(startsWith('enter') || startsWith('board')){
		setArea(5);
		echo 'You enter the spaceship. The airlock closes behind you.';
	}
}

if(getArea() == 5){
	if($INPUT == 'look'){
		echo 'With a quick glance at the control panel, you can see that the spaceship is able to take off without any problems.';
	}
	else if(startsWith('exit') || startsWith('leave')){
		setArea(4);
		echo 'You go outside again.';
	}	
	else if($INPUT == 'launch' || $INPUT == 'take off'){
		echo 'You fasten your seatbelt and press the ignition button. The engines rumble, your seat is shaking, but soon enough, you lift off.';
		if(getItem(1) == 1){
			echo "\n\n".'Fin is swimming in his bowl, still uninterested in anything that is happening. As you move away from the dusty planet, you give him some food, to celebrate your survival.';
		}
		else{
			echo "\n\n".'As you move away from the dusty planet, you notice that you have forgotten to take your goldfish with you.';
		}
		setArea(6);
	}
}

if(getArea() == 6){
	if($INPUT == 'look'){
		echo 'Around you is nothing but vast, empty space...';
	}
	elseif($INPUT == 'open airlock' || startsWith('exit')){
		addHeader('cls');
		echo 'You open the airlock...'."\n\n";
		echo '<b>'.makeStars(600, 20).'</b>';
		echo '<center>G&nbsp;A&nbsp;M&nbsp;E &nbsp; O&nbsp;V&nbsp;E&nbsp;R</center>';
		echo '<b>'.makeStars(900, 30).'</b>';
	}
}

function makeStars($size, $dens){
	$r = '';
	for($i = 0; $i < $size; $i++){
		$r .= (rand(1, $size) <= $dens) ? ' * ' : ' &nbsp; ';
	}
	return $r;
}

# Overridable commands
if(ob_get_length() == 0){
	if($INPUT == 'ask fin' && getItem(1) == 1){
		echo 'You ask Fin for help, but your trusty goldfish isn\'t able to help you, even if he could understand you.';
	}
	else if($INPUT == 'promote fin' && getItem(1) == 1){
		if(getItem(4) == 0){
			echo 'You promote your trusty goldfish to lieutenant.';
			setItem(4, 1);
		}
		elseif(getItem(4) == 1){
			echo 'You promote your trusty goldfish to commander. Fin gives you a questioning look.';
			setItem(4, 2);
		}
		elseif(getItem(4) == 2){
			echo 'You promote your trusty goldfish to admiral.';
			setItem(4, 3);
		}
		else{
			echo 'Your trusty goldfish is already admiral.';
		}
	}
}

function go($dir){
	global $INPUT;
	return ($INPUT == 'go '.$dir || $INPUT == $dir || $INPUT == substr($dir, 0, 1));
}

function startsWith($str){
	global $INPUT;
	return strpos($INPUT, $str) === 0;
}

function chance($p){
	return (rand(1, 1/$p) == 1);
}

?>
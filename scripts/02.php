<?php
	define("ROOT", __DIR__ . "/../");
	
	require_once(ROOT . "bin/init.php");	

	use App\Intcode;

	$helper = new Intcode();

	// Sample: 3500
	$helper->setProgram("1,9,10,3,2,3,11,0,99,30,40,50");

	#$helper->load(ROOT . "data/02");

	// Part 1: 3166704
	#$helper->initialise(12, 2);

	// Part 2: 19690720
	#$helper->initialise(80, 18);
	
	$result = $helper->run();

	echo($result . PHP_EOL);
	
	// $noun++ = +243000
	// $verb++ = +1
	
	// 80, 18
?>
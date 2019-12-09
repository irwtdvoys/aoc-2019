<?php
	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	use App\Intcode;

	$helper = new Intcode();

	#$helper->setProgram("109,1,204,-1,1001,100,1,100,1008,100,16,101,1006,101,0,99");
	#$helper->setProgram("1102,34915192,34915192,7,4,7,99,0");
	#$helper->setProgram("104,1125899906842624,99");

	$helper->load(ROOT . "data/09");
	$result = $helper->run();

	echo($result);

	// Part 1: 3497884671
	// Part 2: 46470
?>

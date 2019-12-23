<?php
	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	use App\Intcode\VirtualMachine;

	$helper = new VirtualMachine();
	$helper->load(ROOT . "data/05/input");
	$helper->run();
	$result = $helper->output();

	echo($result);

	// Part 1: 15386262
	// Part 2: 10376124
?>

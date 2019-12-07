<?php
	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	use App\Intcode;

	$helper = new Intcode();
	$helper->load(ROOT . "data/05");
	$result = $helper->run();

	echo($result);

	// Part 1: 15386262
	// Part 2: 10376124
?>

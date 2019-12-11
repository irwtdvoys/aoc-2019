<?php
	use App\Robot;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Robot();
	$helper->load();
	$helper->run(2);

	// Part 1: 2293
	// Part 2: AHLCPRAL
?>

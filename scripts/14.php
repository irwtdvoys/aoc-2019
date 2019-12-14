<?php
	use App\FuelRefinery\NanoFactory;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new NanoFactory();
	$helper->load();
	$result = $helper->run(2);

	echo($result . PHP_EOL);

	// Part 1: 278404
	// Part 2: 4436981
?>

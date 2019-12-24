<?php
	use App\Life;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Life(2);
	$helper->load();
	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 10282017
	// Part 2: 2065
?>

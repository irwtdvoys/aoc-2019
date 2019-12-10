<?php
	use App\Asteroids;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Asteroids();
	$result = $helper->run(1);

	echo($result . PHP_EOL);

	// Part 1: 253
	// Part 2: 815
?>

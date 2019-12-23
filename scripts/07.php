<?php
	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	use App\Amplifiers;

	$helper = new Amplifiers();
	$result = $helper->run([5, 6, 7, 8, 9]); // [0, 1, 2, 3, 4]

	echo($result . PHP_EOL);

	// Part 1: 366376
	// Part 2: 21596786
?>

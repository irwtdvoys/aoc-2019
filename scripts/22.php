<?php
	use App\Shuffle;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Shuffle();
	$helper->load();

	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 4775
	// Part 2:
?>

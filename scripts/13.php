<?php
	use App\Arcade;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Arcade();
	$helper->load();

	$result = $helper->run(2);

	echo($result . PHP_EOL);

	// Part 1: 348
	// Part 2: 16999
?>

<?php
	use App\TractorBeam;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new TractorBeam(2);
	$helper->load();
	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 209
	// Part 2: 10450905
?>

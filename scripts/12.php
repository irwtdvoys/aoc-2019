<?php
	use App\SphereOfInfluence;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new SphereOfInfluence();
	$helper->load();

	$result = $helper->run(2);

	echo($result);

	// Part 1: 31019
	// Part 2: 314610635824376
?>

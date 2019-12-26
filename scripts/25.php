<?php
	use App\Adventure;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Adventure();
	$helper->run(true);

	// Part 1: 2105377
?>

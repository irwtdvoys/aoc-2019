<?php
	use App\Network;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Network();

	$result = $helper->run(2);

	echo($result . PHP_EOL);

	// Part 1: 17714
	// Part 2: 10982
?>

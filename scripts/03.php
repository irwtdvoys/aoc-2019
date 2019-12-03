<?php
	define("ROOT", __DIR__ . "/../");
	
	include_once(ROOT . "bin/init.php");

	use App\FuelManagement;

	$helper = new FuelManagement();
	$result = $helper->run(2); // 1 or 2

	echo($result . "\n");

	// Part 1: 529
	// Part 2: 20386
?>

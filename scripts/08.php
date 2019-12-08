<?php
	use App\Image;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Image(25, 6);
	$helper->load();
	$checksum = $helper->checksum();
	$image = $helper->output();

	echo($checksum . PHP_EOL);
	echo($image);

	// Part 1: 1560
	// Part 2: UGCUH
?>

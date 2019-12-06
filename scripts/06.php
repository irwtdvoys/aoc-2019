<?php
	use App\OrbitalTransfers;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$test = "COM)B
B)C
C)D
D)E
E)F
B)G
G)H
D)I
E)J
J)K
K)L";

	$helper = new OrbitalTransfers();
	$helper->load();
	$result = $helper->run(1);

	echo($result . PHP_EOL);

	//Part 1: 234446
	//Part 2: 385
?>

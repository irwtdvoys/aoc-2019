<?php
	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	use App\PasswordCrack;

	$helper = new PasswordCrack(153517, 630395);
	$result = $helper->run(2);

	echo(PHP_EOL . $result . PHP_EOL);

	// Part 1: 1729
	// Part 2: 1172
?>

<?php
	use App\AsciiProcessor as Ascii;
	use App\Intcode\VirtualMachine;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$encoded = Ascii::fetch(ROOT . "data/21/part1");
	$encoded = Ascii::fetch(ROOT . "data/21/part2");

	$vm = new VirtualMachine();
	$vm->load(ROOT . "data/21/input");
	$result = $vm->run($encoded);

	echo(Ascii::output($result));

	// Part 1: 19350375
	// Part 2: 1143990055
?>

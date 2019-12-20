<?php
	define("ROOT", __DIR__ . "/../");

	require_once(ROOT . "bin/init.php");

	use App\Fuel;
	use Bolt\Files;

	$test1 = array(12, 14, 1969, 100756);
	$test2 = array(14, 1969, 100756);

	$total = 0;

	$raw = (new Files())->load(ROOT . "data/01/input");
	$data = array_filter(explode("\n", $raw));

	foreach ($data as $mass)
	{
		$fuel = Fuel::calculate((int)$mass);
		#$fuel = Fuel::recursive((int)$mass);
		echo("Mass: $mass, Fuel: $fuel\n");
		$total += $fuel;
	}

	echo("Total: " . $total . "\n");
	// Part 1: 3249817
	// Part 2: 4871866
?>

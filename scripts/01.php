<?php
	define("ROOT", __DIR__ . "/../");
	
	require_once(ROOT . "vendor/autoload.php");
	
	use App\Fuel;
	
	$test1 = array(12, 14, 1969, 100756);
	$test2 = array(14, 1969, 100756);
	
	$total = 0;
	
	$raw = file_get_contents(ROOT . "data/01");
	$data = array_filter(explode("\n", $raw));

	foreach ($test1 as $mass)
	{
		#$fuel = Fuel::calculate((int)$mass);
		$fuel = Fuel::recursive((int)$mass);
		echo("Mass: $mass, Fuel: $fuel\n");
		$total += $fuel;
	}
	
	echo("Total: " . $total . "\n");
	// Part 1: 3249817
	// Part 2: 4871866
?>

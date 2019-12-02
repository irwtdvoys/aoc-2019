<?php
	define("ROOT", __DIR__ . "/../");
	
	use function Cruxoft\dump;
	
	use App\Fuel;
	use App\Handler;
	use Bolt\Files;
	
	require_once(ROOT . "vendor/autoload.php");
	
	set_error_handler([Handler::class, "error"], E_ALL & ~E_NOTICE);
	set_exception_handler([Handler::class, "exception"]);
	
	$test1 = array(12, 14, 1969, 100756);
	$test2 = array(14, 1969, 100756);
	
	$total = 0;
	
	$raw = (new Files())->load(ROOT . "data/01");
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

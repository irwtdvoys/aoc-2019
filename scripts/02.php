<?php
	define("ROOT", __DIR__ . "/../");
	
	use function Cruxoft\dump;
	
	use App\Handler;
	use App\Intcode;
	
	require_once(ROOT . "vendor/autoload.php");
	
	set_error_handler([Handler::class, "error"], E_ALL & ~E_NOTICE);
	set_exception_handler([Handler::class, "exception"]);

	$tmp = new Intcode();
	#$tmp->initialise(12, 2);
	// 3166704
	
	$tmp->initialise(80, 18);
	// 19690720
	
	$tmp->run();
	
	// $noun++ = +243000
	// $verb++ = +1
	
	// 80, 18
?>

<?php
	use App\Handler;
	use App\Loggers;
	use Cruxoft\Logbook;
	use Monolog\Handler\StreamHandler;
	use Monolog\Logger;

	if (PHP_SAPI !== "cli")
	{
		die("Command line usage only");
	}

	require_once(ROOT . "vendor/autoload.php");
	
	set_error_handler([Handler::class, "error"], E_ALL & ~E_NOTICE);
	set_exception_handler([Handler::class, "exception"]);
	
	Logbook::add(Loggers::GENERAL, array(new StreamHandler(ROOT . "logs/main.log", Logger::INFO)));

	function dump($object)
	{
		Cruxoft\dump($object);
	}
?>

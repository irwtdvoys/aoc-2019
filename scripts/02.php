<?php
	define("ROOT", __DIR__ . "/../");
	
	require_once(ROOT . "bin/init.php");	

	use App\Intcode;
	
	$tmp = new Intcode();
	#$tmp->setProgram("1,9,10,3,2,3,11,0,99,30,40,50");
	// 3500
	
	#$tmp->initialise(12, 2);
	// 3166704
	
	$tmp->initialise(80, 18);
	// 19690720
	
	$tmp->run();
	
	// $noun++ = +243000
	// $verb++ = +1
	
	// 80, 18
?>

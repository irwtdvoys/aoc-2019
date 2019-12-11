<?php
	use App\Intcode;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	$helper = new Intcode(true);
	$helper->load(ROOT . "data/11");

	while (true)
	{
		$result = $helper->run();
		echo($result);
	}
?>

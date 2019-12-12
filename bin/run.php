<?php
	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	use App\Intcode;

	$options = getopt("f:", array("filename:"));

	if (!$options['f'] && !$options['filename'])
	{
		throw new Exception("Missing Filename");
	}

	$filename = isset($options['f']) ? $options['f'] : $options['filename'];

	$helper = new Intcode();
	$helper->load($filename);
	$result = $helper->run();

	echo($result);
?>

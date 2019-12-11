<?php
	namespace App\Utils;

	use Bolt\Enum;

	class Directions extends Enum
	{
		const UP = "^";
		const RIGHT = ">";
		const DOWN = "v";
		const LEFT = "<";
	}
?>

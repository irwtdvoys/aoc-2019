<?php
	namespace App\Intcode;

	use Bolt\Enum;

	class Modes extends Enum
	{
		const POSITION = 0;
		const IMMEDIATE = 1;
		const RELATIVE = 2;
	}
?>

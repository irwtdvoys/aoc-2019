<?php
	namespace App\Intcode\VM;

	use Bolt\Enum;

	class InterruptTypes extends Enum
	{
		const NONE = 0;
		const OUTPUT = 1;
		const INPUT = 2;
	}
?>

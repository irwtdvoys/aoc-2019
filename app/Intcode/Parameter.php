<?php
	namespace App\Intcode;
	
	class Parameter
	{
		public $value;
		public $mode;

		public function __construct(int $value, int $mode = Modes::POSITION)
		{
			$this->value = $value;
			$this->mode = $mode;
		}
	}
?>

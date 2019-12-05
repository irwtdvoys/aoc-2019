<?php
	namespace App\Intcode;
	
	class Parameter
	{
		public $value;
		public $mode;

		public function __construct(int $value, int $mode = 0)
		{
			$this->value = $value;
			$this->mode = $mode;
		}
	}
?>

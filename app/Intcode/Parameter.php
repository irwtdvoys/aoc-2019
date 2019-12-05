<?php
	namespace App\Intcode;
	
	use Exception;

	class Parameter
	{
		public $value;
		public $mode;

		public function __construct(int $value, int $mode = Modes::POSITION)
		{
			if (!in_array($mode, array_values(Modes::expose())))
			{
				throw new Exception("Unknown parameter mode [" . $mode . "]");
			}

			$this->value = $value;
			$this->mode = $mode;
		}
	}
?>

<?php
	namespace App\Intcode;

	use Exception;
	
	class Instruction
	{
		public $opcode;
		public $parameters = array();
		
		public function __construct($data) // data may be too much, only parameterise required amount
		{
			$first = $data[0];
			$this->opcode = (int)substr((string)$first, -2);

			switch ($this->opcode)
			{
				case 1:
				case 2:
				case 7:
				case 8:
					$count = 3;
					break;
				case 3:
				case 4:
					$count = 1;
					break;
				case 5:
				case 6:
					$count = 2;
					break;
				case 99:
					$count = 0;
					break;
				default:
					throw new Exception("Missing parameter count for opcode [" . $this->opcode . "]");
					break;
			}

			// Build full parameter mode list in matching order (default 0)
			$modes = strrev(substr(str_pad((string)$first, $count + 2, "0", STR_PAD_LEFT), 0, -2));

			// Add parameters to instruction
			for ($index = 1; $index <= $count; $index++)
			{
				$value = $data[$index];
				$mode = (int)$modes[$index - 1];

				$this->parameters[] = new Parameter($value, $mode);
			}
		}
	}
?>

<?php
	namespace App\Intcode;

	use Exception;

	class Instruction
	{
		public int $opcode;
		public array $parameters = array();

		public function __construct($data) // data may be too much, only parameterise required amount
		{
			$first = $data[0];

			$this->opcode = $first % 100;
			$modes = floor($first / 100);

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
				case 9:
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

			// Add parameters to instruction
			for ($index = 1; $index <= $count; $index++)
			{
				$value = $data[$index];

				$mode = $modes % 10;
				$modes = floor($modes / 10);

				$this->parameters[] = new Parameter($value, $mode);
			}
		}
	}
?>

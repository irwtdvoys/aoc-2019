<?php
	namespace App\Intcode\VM;

	use Exception;

	class Instruction
	{
		public int $opcode;
		public array $parameters = array();

		public function __construct($data) // data may be too much, only parameterise required amount
		{
			list($modes, $this->opcode) = $this->extract($data[0], 2);

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
				list($modes, $mode) = $this->extract($modes);

				$this->parameters[] = new Parameter($data[$index], $mode);
			}
		}

		private function extract($values, $quantity = 1)
		{
			$base = pow(10, $quantity);
			$value = $values % $base;

			return array(floor($values / $base), $value);
		}
	}
?>

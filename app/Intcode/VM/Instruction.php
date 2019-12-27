<?php
	namespace App\Intcode\VM;

	class Instruction
	{
		public int $opcode;
		public array $parameters = array();

		public function __construct($data)
		{
			list($modes, $this->opcode) = $this->extract($data[0], 2);

			$count = count($data);

			// Add parameters to instruction
			for ($index = 1; $index < $count; $index++)
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

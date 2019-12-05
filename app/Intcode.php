<?php
	namespace App;

	use App\Intcode\Instruction;
	use App\Intcode\Parameter;
	use Bolt\Files;
	use Exception;

	class Intcode
	{
		public array $memory = array();
		public bool $stopped = false;
		public int $cursor = 0;

		public function load($filename = "input.txt"): void
		{
			$this->setProgram((new Files())->load($filename));
		}

		public function nextInstruction(): Instruction
		{
			// send 4 long memory chunk from cursor (max used by instructions, only required)
			$instruction = new Instruction(array_slice($this->memory, $this->cursor, 4));

			// Move cursor dynamic amount based on number of memory locations used by the instruction
			$this->cursor += count($instruction->parameters) + 1;

			return $instruction;
		}

		private function getValue(Parameter $parameter): int
		{
			return ($parameter->mode === Modes::POSITION) ? $this->memory[$parameter->value] : $parameter->value;
		}

		private function getParameters(Instruction $instruction): array
		{
			return array(
				$this->getValue($instruction->parameters[0]),
				$this->getValue($instruction->parameters[1])
			);
		}

		public function processInstruction(Instruction $instruction): void
		{
			switch ($instruction->opcode)
			{
				case 1:
					// Opcode 1 adds together numbers read from two positions and stores the result in a third position.
					$parameters = $this->getParameters($instruction);

					$this->memory[$instruction->parameters[2]->value] = $parameters[0] + $parameters[1];
					break;
				case 2:
					// Opcode 2 works exactly like opcode 1, except it multiplies the two inputs instead of adding them.
					$parameters = $this->getParameters($instruction);

					$this->memory[$instruction->parameters[2]->value] = $parameters[0] * $parameters[1];
					break;
				case 3:
					// Opcode 3 takes a single integer as input and saves it to the position given by its only parameter. For example, the instruction 3,50 would take an input value and store it at address 50.
					fputs(STDOUT, "Enter Value: ");
					$value = (int)trim(fgets(STDIN));

					$this->memory[$instruction->parameters[0]->value] = $value;
					break;
				case 4:
					// Opcode 4 outputs the value of its only parameter. For example, the instruction 4,50 would output the value at address 50.
					fputs(STDOUT, $this->memory[$instruction->parameters[0]->value] . PHP_EOL);
					break;
				case 5:
					// if the first parameter is non-zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
					$parameters = $this->getParameters($instruction);

					if ($parameters[0] !== 0)
					{
						$this->cursor = $parameters[1];
					}
					break;
				case 6:
					// if the first parameter is zero, it sets the instruction pointer to the value from the second parameter. Otherwise, it does nothing.
					$parameters = $this->getParameters($instruction);

					if ($parameters[0] === 0)
					{
						$this->cursor = $parameters[1];
					}
					break;
				case 7:
					// if the first parameter is less than the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
					$parameters = $this->getParameters($instruction);

					$this->memory[$instruction->parameters[2]->value] = ($parameters[0] < $parameters[1]) ? 1 : 0;
					break;
				case 8:
					// if the first parameter is equal to the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
					$parameters = $this->getParameters($instruction);

					$this->memory[$instruction->parameters[2]->value] = ($parameters[0] === $parameters[1]) ? 1 : 0;
					break;
				case 99:
					$this->stopped = true;
					break;
				default:
					throw new Exception("Unknown opcode [" . $instruction->opcode . "]");
					break;
			}
		}

		public function run(): string
		{
			$count = 0;

			while (!$this->stopped)
			{
				$instruction = $this->nextInstruction();

				$this->processInstruction($instruction);

				$count++;
			}

			return $this->output();
		}

		public function output(): string
		{
			return $this->memory[0];
		}

		public function setProgram($string): void
		{
			$this->memory = array_map(function ($element) {
				return (int)$element;
			}, explode(",", $string));
		}

		public function initialise(int $noun, int $verb): void
		{
			$this->memory[1] = $noun;
			$this->memory[2] = $verb;
		}
	}
?>

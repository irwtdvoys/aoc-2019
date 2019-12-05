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
		
		public function processInstruction(Instruction $instruction)
		{
			switch ($instruction->opcode)
			{
				case 1:
					$this->memory[$instruction->parameters[2]] = $this->memory[$instruction->parameters[0]] + $this->memory[$instruction->parameters[1]];
					break;
				case 2:
					$this->memory[$instruction->parameters[2]] = $this->memory[$instruction->parameters[0]] * $this->memory[$instruction->parameters[1]];
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

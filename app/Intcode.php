<?php
	namespace App;

	use App\Intcode\Instruction;
	use App\Intcode\Modes;
	use App\Intcode\Parameter;
	use Bolt\Files;
	use Exception;

	class Intcode
	{
		public array $memory = array();
		public bool $stopped = false;
		public int $cursor = 0;
		public array $inputs = array();
		public string $output = "";
		public int $relativeBase = 0;

		public bool $allowInterrupts = false;

		public function __construct(bool $interrupts = false)
		{
			$this->allowInterrupts = $interrupts;
		}

		public function load(string $filename = "input.txt"): void
		{
			$this->setProgram((new Files())->load($filename));
		}

		public function nextInstruction(): Instruction
		{
			// send 4 long memory chunk from cursor (max used by instructions, only required added to instruction)
			$instruction = new Instruction(array_slice($this->memory, $this->cursor, 4));

			// Move cursor dynamic amount based on number of memory locations used by the instruction
			$this->cursor += count($instruction->parameters) + 1;

			return $instruction;
		}

		private function getValue(Parameter $parameter): int
		{
			switch ($parameter->mode)
			{
				case Modes::POSITION:
					$value = $this->memory[$this->getPosition($parameter)];
					break;
				case Modes::IMMEDIATE:
					$value = $parameter->value;
					break;
				default:
					throw new Exception("Unknown mode [" . $parameter->mode . "]");
					break;
			}

			return $value;
		}

		private function getPosition(Parameter $parameter)
		{
			switch ($parameter->mode)
			{
				case Modes::POSITION:
					$value = $parameter->value;
					break;
				default:
					throw new Exception("Cannot get memory position for parameter in that mode [" . $parameter->mode . "]");
					break;
			}

			return $value;
		}

		private function getParameters(Instruction $instruction): array
		{
			return array(
				$this->getValue($instruction->parameters[0]),
				$this->getValue($instruction->parameters[1])
			);
		}

		public function nextInput(): ?int
		{
			return (count($this->inputs) > 0) ? array_shift($this->inputs) : null;
		}

		public function processInstruction(Instruction $instruction): void
		{
			switch ($instruction->opcode)
			{
				case 1:
					// Opcode 1 adds together numbers read from two positions and stores the result in a third position.
					$parameters = $this->getParameters($instruction);

					$this->memory[$this->getPosition($instruction->parameters[2])] = $parameters[0] + $parameters[1];
					break;
				case 2:
					// Opcode 2 works exactly like opcode 1, except it multiplies the two inputs instead of adding them.
					$parameters = $this->getParameters($instruction);

					$this->memory[$this->getPosition($instruction->parameters[2])] = $parameters[0] * $parameters[1];
					break;
				case 3:
					// Opcode 3 takes a single integer as input and saves it to the position given by its only parameter. For example, the instruction 3,50 would take an input value and store it at address 50.
					$value = $this->nextInput();

					if ($value === null)
					{
						fputs(STDOUT, "Enter Value: ");
						$value = (int)trim(fgets(STDIN));
					}

					$this->memory[$this->getPosition($instruction->parameters[0])] = $value;
					break;
				case 4:
					// Opcode 4 outputs the value of its only parameter. For example, the instruction 4,50 would output the value at address 50.
					$this->output .= $this->getValue($instruction->parameters[0]) . PHP_EOL;

					if ($this->allowInterrupts === true)
					{
						$this->stopped = true;
					}
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

					$this->memory[$this->getPosition($instruction->parameters[2])] = ($parameters[0] < $parameters[1]) ? 1 : 0;
					break;
				case 8:
					// if the first parameter is equal to the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
					$parameters = $this->getParameters($instruction);

					$this->memory[$this->getPosition($instruction->parameters[2])] = ($parameters[0] === $parameters[1]) ? 1 : 0;
					break;
				case 9:
					$this->relativeBase += $this->getValue($instruction->parameters[0]);
					break;
				case 99:
					$this->stopped = true;
					break;
				default:
					throw new Exception("Unknown opcode [" . $instruction->opcode . "]");
					break;
			}
		}

		public function run(array $inputs = []): ?string
		{
			$this->stopped = false;
			$this->inputs = $inputs;
			$this->output = "";

			while (!$this->stopped)
			{
				$instruction = $this->nextInstruction();
				$this->processInstruction($instruction);
			}

			return $this->output();
		}

		public function output(): string
		{
			return $this->output;
		}

		public function setProgram(string $string): void
		{
			$this->memory = array_map(function ($element) {
				return (int)$element;
			}, explode(",", $string));

			$this->memory = array_pad($this->memory, 2000, 0); // Todo: hardcoded upper limit on memory
		}

		public function initialise(int $noun, int $verb): void
		{
			$this->memory[1] = $noun;
			$this->memory[2] = $verb;
		}
	}
?>

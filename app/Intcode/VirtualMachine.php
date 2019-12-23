<?php
	namespace App\Intcode;

	use App\Intcode\VM\Inputs;
	use App\Intcode\VM\Instruction;
	use App\Intcode\VM\Memory;
	use App\Intcode\VM\Modes;
	use App\Intcode\VM\Parameter;
	use Bolt\Enum;
	use Bolt\Files;
	use Exception;

	class Interrupts extends Enum
	{
		const NONE = 0;
		const OUTPUT = 1;
		const INPUT = 2;
	}

	class VirtualMachine
	{
		public Memory $memory;
		public Inputs $inputs;

		public bool $stopped = false;
		public bool $paused = false;
		public int $cursor = 0;
		public array $output = array();
		public int $relativeBase = 0;

		public object $interrupt;

		public function __construct(int $interrupts = Interrupts::NONE)
		{
			$this->memory = new Memory();
			$this->inputs = new Inputs();

			$this->interrupt = (object)array(
				"type" => $interrupts,
				"allow" => ($interrupts === Interrupts::NONE) ? false : true
			);
		}

		public function __clone()
		{
			$this->memory = clone $this->memory;
			$this->inputs = clone $this->inputs;
		}

		public function load(string $filename = "input.txt"): void
		{
			$this->setProgram(trim((new Files())->load($filename)));
		}

		public function nextInstruction(): Instruction
		{
			// send 4 long memory chunk from cursor (max used by instructions, only required added to instruction)
			$instruction = new Instruction($this->memory->slice($this->cursor, 4));

			// Move cursor dynamic amount based on number of memory locations used by the instruction
			$this->cursor += count($instruction->parameters) + 1;

			return $instruction;
		}

		private function getValue(Parameter $parameter): int
		{
			switch ($parameter->mode)
			{
				case Modes::POSITION:
				case Modes::RELATIVE:
					$value = $this->memory->get($this->getPosition($parameter));
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
				case Modes::RELATIVE:
					$value = $this->relativeBase + $parameter->value;
					break;
				default:
					throw new Exception("Cannot get memory position for parameter in that mode [" . $parameter->mode . "]");
					break;
			}

			return $value;
		}

		private function getParameters(Instruction $instruction): array
		{
			if (count($instruction->parameters) === 0)
			{
				return array();
			}

			$result = array();

			foreach ($instruction->parameters as $parameter)
			{
				$result[] = $this->getValue($parameter);
			}

			return $result;
		}

		public function processInstruction(Instruction $instruction): void
		{
			switch ($instruction->opcode)
			{
				case 1:
					// Opcode 1 adds together numbers read from two positions and stores the result in a third position.
					$parameters = $this->getParameters($instruction);

					$this->memory->set($this->getPosition($instruction->parameters[2]), ($parameters[0] + $parameters[1]));
					break;
				case 2:
					// Opcode 2 works exactly like opcode 1, except it multiplies the two inputs instead of adding them.
					$parameters = $this->getParameters($instruction);

					$this->memory->set($this->getPosition($instruction->parameters[2]), ($parameters[0] * $parameters[1]));
					break;
				case 3:
					// Opcode 3 takes a single integer as input and saves it to the position given by its only parameter. For example, the instruction 3,50 would take an input value and store it at address 50.

					{
						try
						{
							$value = $this->inputs->fetch();
						}
						catch (Exception $exception)
						{
							fputs(STDOUT, "Enter Value: ");
							$value = (int)trim(fgets(STDIN));
						}

						$this->memory->set($this->getPosition($instruction->parameters[0]), $value);
					}
					break;
				case 4:
					// Opcode 4 outputs the value of its only parameter. For example, the instruction 4,50 would output the value at address 50.
					$this->output[] = $this->getValue($instruction->parameters[0]);

					if ($this->interrupt->type === Interrupts::OUTPUT && $this->interrupt->allow)
					{
						$this->paused = true;
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

					$value = ($parameters[0] < $parameters[1]) ? 1 : 0;

					$this->memory->set($this->getPosition($instruction->parameters[2]), $value);
					break;
				case 8:
					// if the first parameter is equal to the second parameter, it stores 1 in the position given by the third parameter. Otherwise, it stores 0.
					$parameters = $this->getParameters($instruction);

					$value = ($parameters[0] === $parameters[1]) ? 1 : 0;

					$this->memory->set($this->getPosition($instruction->parameters[2]), $value);
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
			if ($this->stopped === true)
			{
				throw new Exception("Program has halted");
			}

			$this->paused = false;
			$this->inputs->set($inputs);
			$this->output = array();

			while (!$this->stopped && !$this->paused)
			{
				$instruction = $this->nextInstruction();
				$this->processInstruction($instruction);
			}

			return $this->output;
		}

		public function output(): string
		{
			return implode(PHP_EOL, $this->output) . PHP_EOL;
		}

		public function setProgram(string $string): void
		{
			$memory = array_map(function ($element) {
				return (int)$element;
			}, explode(",", $string));

			$this->memory->load($memory);
		}

		public function initialise(int $noun, int $verb): void
		{
			$this->memory->set(1, $noun);
			$this->memory->set(2, $verb);
		}
	}
?>

<?php
	namespace App;
	
	use Bolt\Files;
	
	class Intcode
	{
		private $memory;
		private $instructionPointer = 0;
		private $stopped = false;
		
		public function load($filename = "input.txt")
		{
			$this->setProgram((new Files())->load($filename));
		}
		
		public function getInstruction(int $address = 0)
		{
			$offset = $address * 4;
			
			return new Instruction(array_slice($this->memory, $offset, 4));
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
					$this->output();
					break;
			}
		}
		
		public function run()
		{
			while (!$this->stopped)
			{
				$operation = $this->getInstruction($this->instructionPointer);
				$this->processInstruction($operation);
				
				$this->instructionPointer++;
			}
		}
		
		public function output()
		{
			echo($this->memory[0] . "\n");
		}
		
		public function setProgram($string)
		{
			$this->memory = array_map(function ($element) {
				return (int)$element;
			}, explode(",", $string));
		}
		
		public function initialise(int $noun, int $verb)
		{
			$this->load(ROOT . "data/02");
			$this->memory[1] = $noun;
			$this->memory[2] = $verb;
		}
	}
?>

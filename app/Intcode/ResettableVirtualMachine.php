<?php
	namespace App\Intcode;

	use App\Intcode\VM\Memory;

	class ResettableVirtualMachine extends VirtualMachine
	{
		public Memory $initialMemory;

		public function setProgram(string $string): void
		{
			parent::setProgram($string);

			$this->initialMemory = $this->memory;
		}

		public function reset()
		{
			$this->memory = $this->initialMemory;
			$this->stopped = false;
			$this->paused = false;
			$this->cursor = 0;
			$this->inputs->clear();
			$this->output = array();
			$this->relativeBase = 0;
		}
	}
?>

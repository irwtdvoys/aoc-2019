<?php
	namespace App;
	
	class Instruction
	{
		public $opcode;
		public $parameters;
		
		public function __construct($data)
		{
			$this->opcode = array_shift($data);
			$this->parameters = $data;
		}
	}
?>

<?php
	namespace App\Intcode\VM;

	use Exception;

	class Opcodes
	{
		public const ADD = 1;
		public const MULTIPLY = 2;
		public const INPUT = 3;
		public const OUTPUT = 4;
		public const JUMP_IF_TRUE = 5;
		public const JUMP_IF_FALSE = 6;
		public const LESS_THAN = 7;
		public const EQUALS = 8;
		public const RELATIVE_BASE_OFFSET = 9;
		public const HALT = 99;

		private const PARAMETER_COUNT = array(
			self::ADD => 4,
			self::MULTIPLY => 4,
			self::INPUT => 2,
			self::OUTPUT => 2,
			self::JUMP_IF_TRUE => 3,
			self::JUMP_IF_FALSE => 3,
			self::LESS_THAN => 4,
			self::EQUALS => 4,
			self::RELATIVE_BASE_OFFSET => 2,
			self::HALT => 1
		);

		public static function parameterCount(int $opcode)
		{
			if (!isset(Opcodes::PARAMETER_COUNT[$opcode]))
			{
				throw new Exception("Missing parameter count for opcode [" . $opcode . "]");
			}

			return self::PARAMETER_COUNT[$opcode];
		}
	}
?>

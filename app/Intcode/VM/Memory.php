<?php
	namespace App\Intcode\VM;

	class Memory
	{
		/** @var int[] */
		private array $data = array();

		public function set(int $index, int $value)
		{
			$this->data[$index] = $value;
		}

		public function get(int $index): int
		{
			if (!isset($this->data[$index]))
			{
				$this->set($index, 0);
			}

			return $this->data[$index];
		}

		public function load(array $data = array())
		{
			$this->data = $data;
		}

		public function clear()
		{
			$this->data = array();
		}

		public function slice(int $offset, int $length)
		{
			$result = array();

			for ($index = 0; $index < $length; $index++)
			{
				$result[] = $this->get($index + $offset);
			}

			return $result;
		}
	}
?>

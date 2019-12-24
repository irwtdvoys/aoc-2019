<?php
	namespace App\Intcode\VM;

	use Exception;

	class Inputs
	{
		private array $data = array();

		public function add(array $inputs)
		{
			$this->data = array_merge($this->data, $inputs);
		}

		public function count(): int
		{
			return count($this->data);
		}

		public function fetch(): ?int
		{
			if (count($this->data) === 0)
			{
				throw new Exception("No input data available");
			}

			return array_shift($this->data);
		}

		public function set(array $inputs)
		{
			$this->data = $inputs;
		}

		public function get(): array
		{
			return $this->data;
		}

		public function clear()
		{
			$this->data = array();
		}
	}
?>

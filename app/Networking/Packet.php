<?php
	namespace App\Networking;

	class Packet
	{
		/** @var int[]  */
		private array $data = array();

		public function __construct(array $data = array())
		{
			$this->data = $data;
		}

		public function address(): int
		{
			return $this->data[0];
		}

		public function x(): int
		{
			return $this->data[1];
		}

		public function y(): int
		{
			return $this->data[2];
		}

		public function add(int $data)
		{
			$this->data[] = $data;
		}

		public function data(): array
		{
			return array(
				$this->x(),
				$this->y()
			);
		}

		public function reset()
		{
			$this->data = array();
		}

		public function isComplete(): bool
		{
			return (count($this->data) === 3) ? true : false;
		}

		public function isEmpty(): bool
		{
			return (count($this->data) === 0) ? true : false;
		}
	}
?>

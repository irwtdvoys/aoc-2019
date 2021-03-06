<?php
	namespace App;

	use App\Intcode\VirtualMachine;

	class TractorBeam
	{
		private int $part;
		private VirtualMachine $computer;
		private VirtualMachine $initial;
		private array $map;

		private int $size = PHP_INT_MAX;

		public function __construct($part = 1)
		{
			$this->part = $part;
			$this->computer = new VirtualMachine();
			$this->map = array(
				array()
			);

			if ($this->part === 1)
			{
				$this->size = 50;
			}
		}

		public function load($override = null)
		{
			$filename = isset($override) ? $override : ROOT . "data/19/input";
			$this->computer->load($filename);

			$this->initial = clone $this->computer;
		}

		public function reset()
		{
			$this->computer = clone $this->initial;
		}

		public function scan()
		{
			$result = null;
			$y = 0;
			$stop = false;

			$cache = [0, 2];

			while ($stop === false)
			{
				echo($y . "\r");

				if ($y < 7)
				{
					$from = 0;
					$to = 10;
				}
				else
				{
					$from = $cache[0];
					$to = $from + 3;
				}

				if ($this->part === 1)
				{
					$from = 0;
					$to = 100;
				}

				$cache = [PHP_INT_MAX, 0];

				$found =  false;

				for ($x = $from; $x < $to; $x++)
				{
					$this->reset();
					$result = $this->search($x, $y);

					$this->map[$x][$y] = $result;

					if ($result === 1)
					{
						$cache[0] = min($cache[0], $x);
						$cache[1] = max($cache[1], $x);

						if ($found === false)
						{
							$found = true;
							$check = $this->search($x + 99, $y - 99);

							if ($check === 1)
							{
								$result = (($x * 10000) + $y - 99);

								break 2;
							}

							continue;
						}
					}

				}

				$y++;

				if ($y === $this->size)
				{
					$stop = true;
				}
			}

			return $result;
		}

		private function search(int $x, int $y): int
		{
			$this->reset();
			$result = $this->computer->run([$x, $y])[0];

			return (int)$result;
		}

		public function draw()
		{
			for ($y = 0; $y < $this->size; $y++)
			{
				for ($x = 0; $x < $this->size; $x++)
				{
					if (!isset($this->map[$x][$y]))
					{
						$result = " ";
					}
					elseif ($this->map[$x][$y] === 1)
					{
						$result = "#";
					}
					else
					{
						$result = ".";
					}
					echo($result);
				}

				echo(PHP_EOL);
			}
		}

		private function affected(): int
		{
			$count = 0;

			for ($y = 0; $y < $this->size; $y++)
			{
				for ($x = 0; $x < $this->size; $x++)
				{
					if ($this->map[$x][$y] === 1)
					{
						$count++;
					}
				}
			}

			return $count;
		}

		public function run()
		{
			$result = $this->scan();

			if ($this->part === 1)
			{
				$this->draw();
				$result = $this->affected();
			}

			return $result;
		}
	}
?>

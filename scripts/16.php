<?php
	use Bolt\Files;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");


	ini_set("memory_limit", -1);


	class FFT
	{
		public array $input;
		public array $output;

		public array $base;

		public int $part;

		public function __construct($part = 1)
		{
			$this->base = array(0, 1, 0, -1);
			$this->part = $part;
		}

		public function load(string $input = null)
		{
			$data = ($input !== null) ? $input : trim((new Files())->load(ROOT . "data/16/input"));

			$multiplier = ($this->part === 1) ? 1 : 10000;

			$data = str_repeat($data, $multiplier);

			$input = array_map(function ($element) {
					return (int)$element;
				}, str_split($data, 1)
			);

			$this->input = $input;
		}

		public function pattern(int $position)
		{
			$index = 0;
			$pattern = [];

			while (count($pattern) <= count($this->input))
			{
				echo($index . "\r");
				$baseIndex = $index % count($this->base);

				$pattern = array_merge($pattern, array_fill(count($pattern), $position, $this->base[$baseIndex]));


				$index++;
			}

			$pattern = array_slice($pattern, 1, count($this->input));

			return $pattern;
		}

		public function part1()
		{
			$phase = 0;

			while ($phase < 100)
			{
				$this->output = [];
				$count = count($this->input);

				for ($position = 0; $position < $count; $position++)
				{
					$step = $position + 1;
					$index = $position;
					$total = 0;

					while ($index < $count)
					{
						$total += array_sum(array_slice($this->input, $index, $step));
						$index += 2 * $step;

						$total -= array_sum(array_slice($this->input, $index, $step));
						$index += 2 * $step;
					}

					$this->output[$position] = abs($total % 10);
				}

				$this->input = $this->output;

				$phase++;
			}

			return implode("", array_slice($this->output, 0, 8));
		}

		public function part2()
		{
			$target = (int)implode("", array_slice($this->input, 0, 7));

			if ($target < count($this->input) / 2)
			{
				throw new Exception("Expects offset to be in last half");
			}

			$inputTail = array_slice($this->input, $target);
			$length = count($inputTail);

			for ($step = 0; $step < 100; $step++)
			{
				for ($index = $length - 2; $index > -1; $index--)
				{
					$inputTail[$index] += $inputTail[$index + 1];
					$inputTail[$index] %= 10;
				}
			}

			return implode("", array_slice($inputTail, 0, 8));
		}

		public function run()
		{
			return $this->{"part" . $this->part}();
		}
	}

	$helper = new FFT(1);
	$helper->load();

	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 36627552
	// Part 2: 79723033
?>

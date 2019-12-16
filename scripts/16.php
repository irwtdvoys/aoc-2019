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

		public array $history;

		public function __construct()
		{
			$this->base = array(0, 1, 0, -1);
		}

		public function load(string $input = null)
		{
			$data = ($input !== null) ? $input : trim((new Files())->load(ROOT . "data/16"));

			$data = str_repeat($data, 1);//10000

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

			dump(implode(",", $pattern));

			return $pattern;
		}

		public function run()
		{
			$phase = 0;

			while ($phase < 1)
			{
				echo(PHP_EOL . "[$phase]" . PHP_EOL);
				$this->output = [];

				for ($position = 0; $position < count($this->input); $position++)
				{
					$pattern = $this->pattern($position + 1);

					$total = 0;

					for ($loop = 0; $loop < count($this->input); $loop++)
					{
						$total += $this->input[$loop] * $pattern[$loop];
					}

					$this->output[$position] = abs($total % 10);
				}


				$this->input = $this->output;

				$phase++;
			}

			return implode("", array_slice($this->output, (int)implode("", array_slice($this->input, 0, 7)), 8));
		}
	}



	$helper = new FFT();
	$helper->load("123456");

	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 278404
	// Part 2: 4436981
?>

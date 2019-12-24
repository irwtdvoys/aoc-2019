<?php
	use Bolt\Enum;
	use Bolt\Files;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	class Tile extends Enum
	{
		const EMPTY = ".";
		const BUG = "#";

		public int $count;
		public string $state;

		public function __construct(string $state)
		{
			$this->count = 0;
			$this->state = $state;
		}
	}

	class LifeSpace
	{
		/** @var Tile[][] */
		public array $map;
		public array $history;

		public function __construct()
		{
			$this->map = array(
				array()
			);
			$this->history = array();
		}

		public function load(string $override = null)
		{
			$filename = isset($override) ? $override : ROOT . "data/24/input";

			$data = trim((new Files())->load($filename));
			$rows = explode(PHP_EOL, $data);

			$count = 0;

			for ($y = 0; $y < count($rows); $y++)
			{
				$characters = str_split($rows[$y], 1);

				for ($x = 0; $x < count($characters); $x++)
				{
					$this->map[$x][$y] = new Tile($characters[$x]);
				}

				$count++;
			}
		}

		public function process()
		{
			// counts
			for ($y = 0; $y < count($this->map[0]); $y++)
			{
				for ($x = 0; $x < count($this->map); $x++)
				{
					$count = 0;

					// WiP
					if (isset($this->map[$x - 1][$y]) && $this->map[$x][$y]->state === Tile::BUG)
					{
						$count++
					}

					$this->map[$x][$y]->count = $count;
				}
			}

			// apply
			for ($y = 0; $y < count($this->map[0]); $y++)
			{
				for ($x = 0; $x < count($this->map); $x++)
				{
					switch ($this->map[$x][$y]->state)
					{
						case Tile::EMPTY:
							if ($this->map[$x][$y]->count === 1 || $this->map[$x][$y]->count === 2)
							{
								$this->map[$x][$y]->state = Tile::BUG;
							}
							break;
						case Tile::BUG:
							if ($this->map[$x][$y]->count !== 1)
							{
								$this->map[$x][$y]->state = Tile::EMPTY;
							}
							break;
					}

					$this->map[$x][$y]->count = 0;
				}
			}
		}

		public function draw()
		{
			for ($y = 0; $y < count($this->map[0]); $y++)
			{
				for ($x = 0; $x < count($this->map); $x++)
				{
					echo($this->map[$x][$y]->state);
				}

				echo(PHP_EOL);
			}

			echo(PHP_EOL);
		}

		public function run()
		{
			$loop = 0;

			while ($loop < 5)
			{
				$this->process();
				$this->draw();

				$loop++;
			}
		}
	}


	$helper = new LifeSpace();
	$helper->load(ROOT . "data/24/examples/01");
	$helper->run();

	die();
	dump($helper);


	$result = "";

	echo($result . PHP_EOL);

	// Part 1: 17714
	// Part 2: 10982
?>

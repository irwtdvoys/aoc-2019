<?php
	use Bolt\Enum;
	use Bolt\Files;
	use Bolt\Json;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	class Tile extends Enum
	{
		const EMPTY = ".";
		const BUG = "#";

		public int $count;
		public string $state;

		public function __construct(string $state = self::EMPTY)
		{
			$this->count = 0;
			$this->state = $state;
		}
	}

	class LifeSpace
	{
		/** @var Tile[][][] */
		public array $map = array();
		public array $history;

		public object $grid;
		public int $part;

		public function __construct($part = 1)
		{
			$this->part = $part;
			$this->map = array();
			$this->history = array();
		}

		public function initialise()
		{
			if ($this->part === 1)
			{
				$this->addLayer(0);
			}
			else
			{
				for ($loop = -200; $loop <= 200; $loop++)
				{
					$this->addLayer($loop);
				}
			}
		}

		public function addLayer(int $index)
		{
			$layer = array(
				array()
			);

			for ($x = $this->grid->min; $x <= $this->grid->max; $x++)
			{
				for ($y = $this->grid->min; $y <= $this->grid->max; $y++)
				{
					$layer[$x][$y] = new Tile();
				}
			}

			$this->map[$index] = $layer;
		}

		public function load(string $override = null)
		{
			$filename = isset($override) ? $override : ROOT . "data/24/input";

			$data = trim((new Files())->load($filename));
			$rows = explode(PHP_EOL, $data);

			$max = (count($rows) - 1) / 2;
			$min = 0 - $max;

			$this->grid = (object)array(
				"min" => $min,
				"max" => $max
			);

			$this->initialise();

			for ($y = $this->grid->min; $y <= $this->grid->max; $y++)
			{
				$characters = str_split($rows[$y + 2], 1);

				for ($x = $this->grid->min; $x <= $this->grid->max; $x++)
				{
					$this->map[0][$x][$y]->state = $characters[$x + 2];
				}
			}
		}

		public function process()
		{
			// counts
			foreach ($this->map as $index => $data)
			{
				for ($y = $this->grid->min; $y <= $this->grid->max; $y++)
				{
					for ($x = $this->grid->min; $x <= $this->grid->max; $x++)
					{
						$count = 0;

						if (isset($this->map[$index][$x - 1][$y]) && $this->map[$index][$x - 1][$y]->state === Tile::BUG)
						{
							$count++;
						}

						if (isset($this->map[$index][$x + 1][$y]) && $this->map[$index][$x + 1][$y]->state === Tile::BUG)
						{
							$count++;
						}

						if (isset($this->map[$index][$x][$y - 1]) && $this->map[$index][$x][$y - 1]->state === Tile::BUG)
						{
							$count++;
						}

						if (isset($this->map[$index][$x][$y + 1]) && $this->map[$index][$x][$y + 1]->state === Tile::BUG)
						{
							$count++;
						}

						$this->map[$index][$x][$y]->count = $count;
					}
				}
			}

			// apply
			foreach ($this->map as $index => $data)
			{
				for ($y = $this->grid->min; $y <= $this->grid->max; $y++)
				{
					for ($x = $this->grid->min; $x <= $this->grid->max; $x++)
					{
						switch ($this->map[$index][$x][$y]->state)
						{
							case Tile::EMPTY:
								if ($this->map[$index][$x][$y]->count === 1 || $this->map[$index][$x][$y]->count === 2)
								{
									$this->map[$index][$x][$y]->state = Tile::BUG;
								}
								break;
							case Tile::BUG:
								if ($this->map[$index][$x][$y]->count !== 1)
								{
									$this->map[$index][$x][$y]->state = Tile::EMPTY;
								}
								break;
						}

						$this->map[$index][$x][$y]->count = 0;
					}
				}
			}
		}

		public function draw()
		{
			foreach ($this->map as $index => $data)
			{
				echo("#" . $index . PHP_EOL);

				for ($y = $this->grid->min; $y <= $this->grid->max; $y++)
				{
					for ($x = $this->grid->min; $x <= $this->grid->max; $x++)
					{
						echo($this->map[$index][$x][$y]->state);
					}

					echo(PHP_EOL);
				}

				echo(PHP_EOL);
			}

		}

		public function run()
		{
			$this->draw();
			$this->remember();

			$loop = 1;
			$running = true;

			while ($running === true)
			{
				$this->process();
				$this->draw();

				try
				{
					$this->remember();
				}
				catch (Exception $exception)
				{
					return $this->rating();
				}

				$loop++;
			}
		}

		public function remember()
		{
			$identifier = $this->encode();

			if (in_array($identifier, $this->history))
			{
				throw new Exception("State exists");
			}

			$this->history[] = $identifier;
		}

		public function encode()
		{
			$index = 0;
			$result = 0;

			foreach ($this->map as $layer)
			{
				foreach ($layer as $column)
				{
					foreach ($column as $tile)
					{
						if ($tile->state === Tile::BUG)
						{
							$result |= 1 << $index;
						}

						$index++;
					}
				}
			}

			return $result;
		}

		public function rating()
		{
			$index = 0;
			$rating = 0;

			for ($y = $this->grid->min; $y <= $this->grid->max; $y++)
			{
				for ($x = $this->grid->min; $x <= $this->grid->max; $x++)
				{
					if ($this->map[0][$x][$y]->state === Tile::BUG)
					{
						$rating += pow(2, $index);
					}

					$index++;
				}
			}

			return $rating;
		}
	}


	$helper = new LifeSpace();
	$helper->load(/*ROOT . "data/24/examples/01"*/);
	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 10282017
	// Part 2:
?>

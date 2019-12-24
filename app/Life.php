<?php
	namespace App;

	use Bolt\Enum;
	use Bolt\Files;
	use Exception;

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

	class Life
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

		public function checkAdjacency(array $checks) : int
		{
			$count = 0;

			foreach ($checks as $check)
			{
				list($layer, $x, $y) = $check;

				if (isset($this->map[$layer][$x][$y]) && $this->map[$layer][$x][$y]->state === Tile::BUG)
				{
					$count++;
				}
			}

			return $count;
		}

		public function adjacencyP1(int $layer, int $x, int $y)
		{
			$checks = array(
				array($layer, $x - 1, $y),
				array($layer, $x + 1, $y),
				array($layer, $x, $y - 1),
				array($layer, $x, $y + 1)
			);

			return $this->checkAdjacency($checks);
		}

		public function adjacencyP2(int $layer, int $x, int $y)
		{
			$count = 0;
			$grid = $this->grid;

			if ($x === 0 && $y === 0)
			{
				return $count;
			}

			$checks = array();

			// Outer ring
			if ($y === $grid->min)
			{
				// top edge
				$checks[] = array($layer + 1, 0, -1); // up
				$checks[] = array($layer, $x, $y + 1); // down
			}

			if ($y === $grid->max)
			{
				// bottom edge
				$checks[] = array($layer, $x, $y - 1); // up
				$checks[] = array($layer + 1, 0, 1); // down
			}

			if ($x === $grid->min)
			{
				// left edge
				$checks[] = array($layer + 1, -1, 0); // left
				$checks[] = array($layer, $x + 1, $y); // right
			}

			if ($x === $grid->max)
			{
				// right edge
				$checks[] = array($layer, $x - 1, $y); // left
				$checks[] = array($layer + 1, 1, 0); // right
			}

			if (($x === $grid->min || $x === $grid->max) && ($y !== $grid->min && $y !== $grid->max))
			{
				$checks[] = array($layer, $x, $y - 1); // up
				$checks[] = array($layer, $x, $y + 1); // down
			}

			if (($y === $grid->min || $y === $grid->max) && ($x !== $grid->min && $x !== $grid->max))
			{
				$checks[] = array($layer, $x - 1, $y); // left
				$checks[] = array($layer, $x + 1, $y); // right
			}

			// Inner corners
			if (($x === 1 || $x === -1) && ($y === 1 || $y === -1))
			{
				$checks = array(
					array($layer, $x - 1, $y),
					array($layer, $x + 1, $y),
					array($layer, $x, $y - 1),
					array($layer, $x, $y + 1)
				);
			}

			// Inner edges
			if (($x === 0 && ($y === 1 || $y === -1)) || ($y === 0 && ($x === 1 || $x === -1)))
			{
				// top
				if ($x === 0 && $y === -1)
				{
					$checks = array(
						array($layer, $x - 1, $y),
						array($layer, $x + 1, $y),
						array($layer, $x, $y - 1)
					);

					for ($loop = $grid->min; $loop <= $grid->max; $loop++)
					{
						$checks[] = array($layer - 1, $loop, $grid->min);
					}
				}
				// bottom
				elseif ($x === 0 && $y === 1)
				{
					$checks = array(
						array($layer, $x - 1, $y),
						array($layer, $x + 1, $y),
						array($layer, $x, $y + 1)
					);

					for ($loop = $grid->min; $loop <= $grid->max; $loop++)
					{
						$checks[] = array($layer - 1, $loop, $grid->max);
					}
				}
				// left
				elseif ($y === 0 && $x === -1)
				{
					$checks = array(
						array($layer, $x - 1, $y),
						array($layer, $x, $y - 1),
						array($layer, $x, $y + 1)
					);

					for ($loop = $grid->min; $loop <= $grid->max; $loop++)
					{
						$checks[] = array($layer - 1, $grid->min, $loop);
					}
				}
				// right
				elseif ($y === 0 && $x === 1)
				{
					$checks = array(
						array($layer, $x + 1, $y),
						array($layer, $x, $y - 1),
						array($layer, $x, $y + 1)
					);

					for ($loop = $grid->min; $loop <= $grid->max; $loop++)
					{
						$checks[] = array($layer - 1, $grid->max, $loop);
					}
				}
			}

			return $this->checkAdjacency($checks);
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
						$method = "adjacencyP" . $this->part;
						$this->map[$index][$x][$y]->count = $this->$method($index, $x, $y);
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
					}
				}
			}
		}

		public function draw()
		{

			foreach ($this->map as $index => $data)
			{
				$output = "";

				$count = 0;
				$output .= "#" . $index . PHP_EOL;

				for ($y = $this->grid->min; $y <= $this->grid->max; $y++)
				{
					for ($x = $this->grid->min; $x <= $this->grid->max; $x++)
					{
						if ($this->map[$index][$x][$y]->state === Tile::BUG)
						{
							$count++;
						}

						if ($this->part === 2 && $x === 0 && $y === 0)
						{
							$output .= "?";
						}
						else
						{
							$output .= $this->map[$index][$x][$y]->/*count;//*/state;
						}
					}

					$output .= PHP_EOL;
				}

				$output .= PHP_EOL;

				if ($count > 0)
				{
					echo($output);
				}
			}
		}

		public function run()
		{
			$this->remember();

			$loop = 1;
			$running = true;

			while ($running === true)
			{
				$this->process();

				try
				{
					$this->remember();
				}
				catch (Exception $exception)
				{
					if ($this->part === 1)
					{
						return $this->rating();
					}
				}

				$loop++;

				if ($loop > 200)
				{
					$running = false;
				}
			}

			return $this->count();
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

		public function count(): int
		{
			$count = 0;

			foreach ($this->map as $layer)
			{
				foreach ($layer as $column)
				{
					foreach ($column as $tile)
					{
						if ($tile->state === Tile::BUG)
						{
							$count++;
						}
					}
				}
			}

			return $count;
		}
	}
?>

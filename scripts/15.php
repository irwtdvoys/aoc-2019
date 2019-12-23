<?php
	use App\Intcode\Interrupts;
	use App\Intcode\VirtualMachine;
	use App\Utils\Position2d;
	use Bolt\GeoJson\Geometry\Envelope;
	use Bolt\GeoJson\Geometry\Point;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");

	class Tiles
	{
		const WALL = 0;
		const PATH = 1;
		const SYSTEM = 2;
	}

	class Responses
	{
		const WALL = 0;
		const MOVED = 1;
		const FOUND = 2;
	}

	class Directions
	{
		const NORTH = 1;
		const SOUTH = 2;
		const WEST = 3;
		const EAST = 4;
	}

	class Node
	{
		public int $type;
		public int $distance;

		public function __construct($type, $distance = 0)
		{
			$this->type = $type;
			$this->distance = $distance;
		}
	}

	class Robot
	{
		public Position2d $location;
		public int $facing;

		public array $history;

		public Envelope $bounds;

		public function __construct()
		{
			$this->location = new Position2d(0, 0);
			$this->facing = Directions::NORTH;
			$this->reset();
		}

		public function reset()
		{
			$this->history = array();

			$this->bounds = new Envelope();
			$this->bounds->extend(new Point([0, 0]));
		}

		public function move(int $direction)
		{
			$this->history[] = (string)$this->location;

			switch ($direction)
			{
				case Directions::NORTH:
					$this->location->y++;
					break;
				case Directions::SOUTH:
					$this->location->y--;
					break;
				case Directions::WEST:
					$this->location->x--;
					break;
				case Directions::EAST:
					$this->location->x++;
					break;
			}

			$this->facing = $direction;
			$this->bounds->extend(new Point([$this->location->x, $this->location->y]));
		}

		public function left(): int
		{
			switch ($this->facing)
			{
				case Directions::NORTH:
					$result = Directions::WEST;
					break;
				case Directions::SOUTH:
					$result = Directions::EAST;
					break;
				case Directions::WEST:
					$result = Directions::SOUTH;
					break;
				case Directions::EAST:
					$result = Directions::NORTH;
					break;
				default:
					throw new Exception("Unknown direction");
					break;
			}

			return $result;
		}

		public function forward()
		{
			$this->move($this->facing);
		}

		public function turn()
		{
			switch ($this->facing)
			{
				case Directions::NORTH:
					$result = Directions::EAST;
					break;
				case Directions::SOUTH:
					$result = Directions::WEST;
					break;
				case Directions::WEST:
					$result = Directions::NORTH;
					break;
				case Directions::EAST:
					$result = Directions::SOUTH;
					break;
				default:
					throw new Exception("Unknown direction");
					break;
			}

			$this->facing = $result;
		}
	}

	class RepairDroid
	{
		private array $map = array();

		private VirtualMachine $vm;
		private Robot $robot;

		private int $part;

		public function __construct($part = 1)
		{
			$this->robot = new Robot();

			$this->map[0] = array(
				new Node(Tiles::PATH, 0)
			);

			$this->part = $part;
		}

		public function load()
		{
			$this->vm = new VirtualMachine(Interrupts::OUTPUT);
			$this->vm->load(ROOT . "data/15/input");
		}

		public function explore()
		{
			$loop = 0;

			while ($loop < 3196) // Todo: better end
			{
				$input = $this->robot->left();
				$result = $this->vm->run([$input])[0];

				switch ($result)
				{
					case Responses::MOVED:
						// set as path
						$this->setTile(Tiles::PATH);
						$this->robot->move($input);
						break;
					case Responses::WALL:
						// Mark wall in direction
						$this->setTile(Tiles::WALL);
						$this->robot->turn();
						break;
					case Responses::FOUND:
						$this->setTile(Tiles::SYSTEM);
						$this->robot->move($input);

						if ($this->part === 2)
						{
							$loop = 0;
							$this->map = array();
							$this->map[$this->robot->location->x][$this->robot->location->y] = new Node(Tiles::SYSTEM, 0);
						}

						break;
				}

				$this->draw();

				echo(PHP_EOL . $loop . PHP_EOL);

				$loop++;
			}
		}

		public function run()
		{
			$this->explore();

			if ($this->part === 1)
			{
				for ($y = $this->robot->bounds->top() + 1; $y > $this->robot->bounds->bottom() - 2; $y--)
				{
					for ($x = $this->robot->bounds->left() - 1; $x < $this->robot->bounds->right() + 2; $x++)
					{
						if (isset($this->map[$x][$y]))
						{
							if ($this->map[$x][$y]->type === Tiles::SYSTEM)
							{
								return $this->map[$x][$y]->distance;
							}
						}
					}
				}
			}

			if ($this->part === 2)
			{
				$max = 0;

				for ($y = $this->robot->bounds->top() + 1; $y > $this->robot->bounds->bottom() - 2; $y--)
				{
					for ($x = $this->robot->bounds->left() - 1; $x < $this->robot->bounds->right() + 2; $x++)
					{
						if (isset($this->map[$x][$y]))
						{
							$max = max($max, $this->map[$x][$y]->distance);
						}
					}
				}

				return $max;
			}

			return false;
		}

		public function setTile($tile)
		{
			$position = clone $this->robot->location;

			switch ($this->robot->left())
			{
				case Directions::NORTH:
					$position->y++;
					break;
				case Directions::SOUTH:
					$position->y--;
					break;
				case Directions::WEST:
					$position->x--;
					break;
				case Directions::EAST:
					$position->x++;
					break;
			}

			if (isset($this->map[$position->x][$position->y]))
			{
				return null;
			}

			$distance = ($tile !== Tiles::WALL) ? $this->map[$this->robot->location->x][$this->robot->location->y]->distance + 1 : 0;

			$this->map[$position->x][$position->y] = new Node($tile, $distance);
		}

		public function draw()
		{
			system("clear");

			for ($y = $this->robot->bounds->top() + 1; $y > $this->robot->bounds->bottom() - 2; $y--)
			{
				for ($x = $this->robot->bounds->left() - 1; $x < $this->robot->bounds->right() + 2; $x++)
				{
					$result = ".";

					if (isset($this->map[$x][$y]))
					{
						switch ($this->map[$x][$y]->type)
						{
							case Tiles::WALL:
								$result = "X";
								break;
							case Tiles::PATH:
								$result = " ";
								break;
							case Tiles::SYSTEM:
								$result = "O";
								break;
							default:
								$result = "!";
								break;
						}

						if ($x == 0 && $y == 0)
						{
							$result = "S";
						}
					}

					echo($result);
				}

				echo(PHP_EOL);
			}
		}
	}

	$helper = new RepairDroid(2);
	$helper->load();
	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 218
	// Part 2: 544
?>

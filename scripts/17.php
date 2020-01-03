<?php
	use App\AsciiProcessor;
	use App\Intcode\VirtualMachine;
	use App\Utils\ArrowDirections as Directions;
	use App\Utils\Position2d;

	define("ROOT", __DIR__ . "/../");

	include_once(ROOT . "bin/init.php");


	ini_set("memory_limit", -1);

	class Tiles
	{
		const SCAFFOLD = "#";
		const BLANK = ".";
		const INTERSECTION = "O";
	}

	class Robot
	{
		public Position2d $position;
		public string $direction;

		public function forward($look = false)
		{
			$location = clone $this->position;

			switch ($this->direction)
			{
				case Directions::UP:
					$location->y--;
					break;
				case Directions::DOWN:
					$location->y++;
					break;
				case Directions::LEFT:
					$location->x--;
					break;
				case Directions::RIGHT:
					$location->x++;
					break;
			}

			if ($look === true)
			{
				return $location;
			}

			$this->position = $location;
		}

		public function left()
		{
			$result = $this->direction;

			switch ($this->direction)
			{
				case Directions::UP:
					$result = Directions::LEFT;
					break;
				case Directions::DOWN:
					$result = Directions::RIGHT;
					break;
				case Directions::LEFT:
					$result = Directions::DOWN;
					break;
				case Directions::RIGHT:
					$result = Directions::UP;
					break;
			}

			$this->direction = $result;
		}

		public function right()
		{
			$result = $this->direction;

			switch ($this->direction)
			{
				case Directions::UP:
					$result = Directions::RIGHT;
					break;
				case Directions::DOWN:
					$result = Directions::LEFT;
					break;
				case Directions::LEFT:
					$result = Directions::UP;
					break;
				case Directions::RIGHT:
					$result = Directions::DOWN;
					break;
			}

			$this->direction = $result;
		}
	}

	class ASCII
	{
		public VirtualMachine $computer;
		public array $map;
		public Robot $robot;
		public string $path;

		public function __construct($part = 1)
		{
			$this->robot = new Robot();
			$this->computer = new VirtualMachine();
			$this->map = array(array());
			$this->path = "";
		}

		public function load()
		{
			$this->computer->load(ROOT . "data/17/input");

			// Part 2
			$this->computer->memory[0] = 2;

			$output = $this->computer->run($this->generate());

			echo(AsciiProcessor::output($output));
			die();
			$output = explode(PHP_EOL, trim($output));

			$output = array_map(
				function ($element) {
					return chr((int)$element);
				},
				$output
			);

			#echo(implode("", $output));

			$x = 0;
			$y = 0;

			foreach ($output as $next)
			{
				if ($next === PHP_EOL)
				{
					$x = 0;
					$y++;
					continue;
				}

				if (!in_array($next, array(Tiles::SCAFFOLD, Tiles::BLANK)))
				{
					$this->robot->position = new Position2d($x, $y);
					$this->robot->direction = $next;
				}

				$this->map[$x][$y] = $next;

				$x++;
			}
		}

		public function draw()
		{
			for ($y = 0; $y < count($this->map[0]); $y++)
			{
				for ($x = 0; $x < count($this->map); $x++)
				{
					echo($this->map[$x][$y]);
				}

				echo(PHP_EOL);
			}
		}

		public function intersections()
		{
			for ($x = 1; $x < count($this->map) - 1; $x++)
			{
				for ($y = 1; $y < count($this->map[$x]) - 1; $y++)
				{
					if ($this->map[$x][$y] !== Tiles::BLANK)
					{
						// count adjacent
						$count = 0;

						if ($this->map[$x - 1][$y] !== Tiles::BLANK)
						{
							$count++;
						}

						if ($this->map[$x + 1][$y] !== Tiles::BLANK)
						{
							$count++;
						}

						if ($this->map[$x][$y - 1] !== Tiles::BLANK)
						{
							$count++;
						}

						if ($this->map[$x][$y + 1] !== Tiles::BLANK)
						{
							$count++;
						}


						if ($count === 4)
						{
							$this->map[$x][$y] = Tiles::INTERSECTION;
						}
					}
				}
			}
		}

		private function check($x, $y)
		{
			echo($x . "," . $y . PHP_EOL);
			return (!isset($this->map[$x][$y]) || $this->map[$x][$y] === Tiles::BLANK) ? false : true;
		}

		public function path()
		{
			$path = "";

			$loop = 0;

			while (true)
			{

				$next = $this->robot->forward(true);

				if ($this->check($next->x, $next->y) === false)
				{
					switch ($this->robot->direction)
					{
						case Directions::UP:
							$left = $this->check($this->robot->position->x - 1, $this->robot->position->y);
							$right = $this->check($this->robot->position->x + 1, $this->robot->position->y);
							break;
						case Directions::DOWN:
							$left = $this->check($this->robot->position->x + 1, $this->robot->position->y);
							$right = $this->check($this->robot->position->x - 1, $this->robot->position->y);
							break;
						case Directions::LEFT:
							$left = $this->check($this->robot->position->x, $this->robot->position->y + 1);
							$right = $this->check($this->robot->position->x, $this->robot->position->y - 1);
							break;
						case Directions::RIGHT:
							$left = $this->check($this->robot->position->x, $this->robot->position->y - 1);
							$right = $this->check($this->robot->position->x, $this->robot->position->y + 1);
							break;
					}

					if ($left === true)
					{
						$path .= "L";
						$this->robot->left();
					}
					elseif ($right === true)
					{
						$path .= "R";
						$this->robot->right();
					}
					else
					{
						break;
					}
				}
				else
				{
					$path .= "F";
					$this->robot->forward();
				}

				$loop++;
			}

			$this->path = $path;
		}

		public function run()
		{
			$this->intersections();
			$this->draw();
			$this->path();

			// calculate part 1
			$total = 0;

			for ($x = 1; $x < count($this->map) - 1; $x++)
			{
				for ($y = 1; $y < count($this->map[$x]) - 1; $y++)
				{
					if ($this->map[$x][$y] === Tiles::INTERSECTION)
					{
						$total += $x * $y;
					}
				}
			}

			return $total;
		}

		public function generate()
		{
			$raw = "A,B,A,A,B,C,B,C,C,B
L,12,R,8,L,6,R,8,L,6
R,8,L,12,L,12,R,8
L,6,R,6,L,12
n
";
			return AsciiProcessor::encode($raw);
		}
	}

	$helper = new ASCII();
	$helper->load();

	$result = $helper->run();

	echo($result . PHP_EOL);

	// Part 1: 36627552
	// Part 2: 79723033
?>

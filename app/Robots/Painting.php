<?php
	namespace App\Robots;

	use App\Intcode\VM\InterruptTypes;
	use App\Intcode\VirtualMachine;
	use App\Robot;
	use App\Utils\Colours as Colourer;
	use App\Utils\ArrowDirections as Directions;
	use Bolt\Enum;
	use Exception;

	class Colours extends Enum
	{
		const BLACK = 0;
		const WHITE = 1;
	}

	class Turns extends Enum
	{
		const RIGHT = 0;
		const LEFT = 1;
	}

	class Painting extends Robot
	{
		private array $data;
		private array $painted;

		private VirtualMachine $computer;

		public function __construct()
		{
			$this->initialise(0, 0, Directions::UP);

			$this->data = array(
				array()
			);

			$this->painted = array();
			$this->computer = new VirtualMachine(InterruptTypes::OUTPUT);
		}

		public function data(int $x, int $y, int $value = null): ?int
		{
			if ($value === null)
			{
				return $this->data[$x][$y] ?? Colours::BLACK;
			}

			$this->data[$x][$y] = $value;

			return null;
		}

		public function load($override = null)
		{
			$path = ($override !== null) ? $override : ROOT . "data/11/input";

			$this->computer->load($path);
		}

		private function turn($direction)
		{
			if ($direction === Turns::RIGHT)
			{
				// right
				switch ($this->direction)
				{
					case Directions::UP:
						$this->direction = Directions::RIGHT;
						break;
					case Directions::RIGHT:
						$this->direction = Directions::DOWN;
						break;
					case Directions::DOWN:
						$this->direction = Directions::LEFT;
						break;
					case Directions::LEFT:
						$this->direction = Directions::UP;
						break;
				}
			}
			elseif ($direction === Turns::LEFT)
			{
				// left
				switch ($this->direction)
				{
					case Directions::UP:
						$this->direction = Directions::LEFT;
						break;
					case Directions::LEFT:
						$this->direction = Directions::DOWN;
						break;
					case Directions::DOWN:
						$this->direction = Directions::RIGHT;
						break;
					case Directions::RIGHT:
						$this->direction = Directions::UP;
						break;
				}
			}
		}

		private function move()
		{
			switch ($this->direction)
			{
				case Directions::UP:
					$this->position->x++;
					break;
				case Directions::RIGHT:
					$this->position->y--;
					break;
				case Directions::DOWN:
					$this->position->x--;
					break;
				case Directions::LEFT:
					$this->position->y++;
					break;
			}
		}

		public function fetch($current)
		{
			$result = $this->computer->run($current);

			return ($result !== array()) ? $result[0] : null;
		}

		public function run($part = 1)
		{
			if ($part === 2)
			{
				$this->data(0, 0, Colours::WHITE);
				$this->direction = Directions::RIGHT;
			}

			$loop = 0;

			while (true)
			{
				$current = [$this->data($this->position->x, $this->position->y)];

				try
				{
					$colour = $this->fetch($current);
					$direction = $this->fetch($current);
				}
				catch (Exception $exception)
				{
					echo(PHP_EOL . $exception->getMessage() . PHP_EOL);
					break;
				}

				echo("[" . $loop . "] " . $colour . " " . $direction . "\r");

				$this->paint($colour);
				$this->turn($direction);
				$this->move();

				$loop++;
			}

			switch ($part)
			{
				case 1:
					echo(count(array_unique($this->painted)) . PHP_EOL);
					break;
				case 2:
					$this->draw();
					break;
			}
		}

		private function paint($colour)
		{
			$this->data($this->position->x, $this->position->y, $colour);

			$this->painted[] = (string)($this->position->x . "," . $this->position->y);
		}

		private function draw()
		{
			$bounds = $this->getBounds();

			for ($y = $bounds[0][1]; $y <= $bounds[1][1]; $y++)
			{
				for ($x = $bounds[0][0]; $x <= $bounds[1][0]; $x++)
				{
					echo($this->data($x, $y) === Colours::WHITE ? Colourer::colour("█", Colourer::WHITE) : " ");
				}

				echo(PHP_EOL);
			}
		}

		private function getBounds()
		{
			$xList = array();
			$yList = array();

			foreach ($this->painted as $next)
			{
				list($x, $y) = explode(",", $next);

				$xList[] = (int)$x;
				$yList[] = (int)$y;
			}

			return [[min($xList), min($yList)], [max($xList), max($yList)]];
		}
	}
?>

<?php
	namespace App;

	use App\Intcode\VM\InterruptTypes;
	use App\Intcode\VirtualMachine;
	use App\Utils\Colours as Colourer;
	use App\Utils\Directions;
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

	class Robot
	{
		private array $currentLocation;
		private string $currentDirection;
		private array $data;
		private array $painted;

		private VirtualMachine $computer;

		public function __construct()
		{
			$this->currentLocation = [0, 0];
			$this->currentDirection = Directions::UP;

			$this->data = array(array());

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
				switch ($this->currentDirection)
				{
					case Directions::UP:
						$this->currentDirection = Directions::RIGHT;
						break;
					case Directions::RIGHT:
						$this->currentDirection = Directions::DOWN;
						break;
					case Directions::DOWN:
						$this->currentDirection = Directions::LEFT;
						break;
					case Directions::LEFT:
						$this->currentDirection = Directions::UP;
						break;
				}
			}
			elseif ($direction === Turns::LEFT)
			{
				// left
				switch ($this->currentDirection)
				{
					case Directions::UP:
						$this->currentDirection = Directions::LEFT;
						break;
					case Directions::LEFT:
						$this->currentDirection = Directions::DOWN;
						break;
					case Directions::DOWN:
						$this->currentDirection = Directions::RIGHT;
						break;
					case Directions::RIGHT:
						$this->currentDirection = Directions::UP;
						break;
				}
			}
		}

		private function move()
		{
			switch ($this->currentDirection)
			{
				case Directions::UP:
					$this->currentLocation[0]++;
					break;
				case Directions::RIGHT:
					$this->currentLocation[1]--;
					break;
				case Directions::DOWN:
					$this->currentLocation[0]--;
					break;
				case Directions::LEFT:
					$this->currentLocation[1]++;
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
				$this->currentDirection = Directions::RIGHT;
			}

			$loop = 0;

			while (true)
			{
				$current = [$this->data($this->currentLocation[0], $this->currentLocation[1])];

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
			$this->data($this->currentLocation[0], $this->currentLocation[1], $colour);

			$this->painted[] = (string)($this->currentLocation[0] . "," . $this->currentLocation[1]);
		}

		private function draw()
		{
			$bounds = $this->getBounds();

			for ($y = $bounds[0][1]; $y <= $bounds[1][1]; $y++)
			{
				for ($x = $bounds[0][0]; $x <= $bounds[1][0]; $x++)
				{
					echo($this->data($x, $y) === Colours::WHITE ? Colourer::colour("█", Colourer::GRAY) : " ");
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

<?php

	namespace App;

	use App\BreakoutTiles as Tiles;
	use App\Utils\Position2d;
	use Exception;

	class Arcade
	{
		private Intcode $computer;
		private array $board;

		private Position2d $ball;
		private Position2d $paddle;

		public function __construct()
		{
			$this->board[0] = array();
		}

		public function load()
		{
			$this->computer = new Intcode(true);
			$this->computer->load(ROOT . "data/13");
		}

		public function run($part = 1)
		{
			if ($part === 2)
			{
				$this->computer->memory[0] = 2;
			}

			$count = 0;
			$input = [];

			while (true)
			{
				try
				{
					$x = (int)$this->computer->run($input);
					$y = (int)$this->computer->run();
					$code = (int)$this->computer->run();

					if ($code === Tiles::BLOCK)
					{
						$count++;
					}

					switch ($code)
					{
						case Tiles::BLOCK:
							$count++;
							break;
						case Tiles::PADDLE:
							$this->paddle = new Position2d($x, $y);
							break;
						case Tiles::BALL:
							$this->ball = new Position2d($x, $y);
							break;
					}

					$this->board[$x][$y] = $code;

					$input = $this->inputDirection();
				}
				catch (Exception $exception)
				{
					echo($exception->getCode() . PHP_EOL);
					break;
				}

				$this->draw();
				$this->inputDirection();
			}


			switch ($part)
			{
				case 1:
					return $count;
					break;
				case 2:
					return $this->score();
				default:
					return false;
					break;
			}
		}

		private function inputDirection(): array
		{
			$input = [];

			if (isset($this->ball) && isset($this->paddle))
			{
				if ($this->ball->x < $this->paddle->x)
				{
					$input[] = -1;
				}
				elseif ($this->ball->x > $this->paddle->x)
				{
					$input[] = 1;
				}
				else
				{
					$input[] = 0;
				}
			}

			return $input;
		}

		public function score()
		{
			return isset($this->board[-1][0]) ? $this->board[-1][0] : 0;
		}

		public function draw()
		{
			system("clear");

			for ($y = 0; $y < 25; $y++)
			{
				for ($x = 0; $x < 40; $x++)
				{
					if (!isset($this->board[$x][$y]))
					{
						// Board is still incomplete
						echo(PHP_EOL);
						break 2;
					}

					$element = $this->board[$x][$y];

					switch ($element)
					{
						case Tiles::EMPTY:
							$value = " ";
							break;
						case Tiles::WALL:
							$value = "W";
							break;
						case Tiles::BLOCK:
							$value = "X";
							break;
						case Tiles::PADDLE:
							$value = "_";
							break;
						case Tiles::BALL:
							$value = "O";
							break;
						default:
							$value = $element;
							break;
					}

					echo($value);
				}

				echo(PHP_EOL);
			}

			if (isset($this->board[-1][0]))
			{
				echo("Score: " . $this->score() . PHP_EOL);
			}
		}
	}
?>
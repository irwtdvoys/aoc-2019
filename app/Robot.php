<?php
	namespace App;

	use App\Utils\ArrowDirections as Directions;
	use App\Utils\Position2d;

	abstract class Robot
	{
		public Position2d $position;
		public string $direction;

		public function initialise(int $x = 0, int $y = 0, string $direction = Directions::UP)
		{
			$this->position = new Position2d($x, $y);
			$this->direction = $direction;
		}
	}
?>

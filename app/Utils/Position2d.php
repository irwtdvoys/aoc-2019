<?php
	namespace App\Utils;

	class Position2d
	{
		public int $x = 0;
		public int $y = 0;

		public function __construct(int $x = 0, int $y = 0)
		{
			$this->x = $x;
			$this->y = $y;
		}

		public function __toString()
		{
			return "(" . $this->x . ", " . $this->y . ")";
		}
	}
?>

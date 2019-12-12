<?php
	namespace App\Utils;

	class Position3d
	{
		public int $x = 0;
		public int $y = 0;
		public int $z = 0;

		public function __construct(int $x = 0, int $y = 0, int $z = 0)
		{
			$this->x = $x;
			$this->y = $y;
			$this->z = $z;
		}

		public function energy()
		{
			return abs($this->x) + abs($this->y) + abs($this->z);
		}
	}
?>

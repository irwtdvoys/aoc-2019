<?php
	namespace App;

	use App\Utils\Position3d;

	class Moon
	{
		public Position3d $position;
		public Position3d $velocity;

		public function __construct(int $x = 0, int $y = 0, int $z = 0)
		{
			$this->position = new Position3d($x, $y, $z);
			$this->velocity = new Position3d();
		}

		public function applyVelocity()
		{
			$this->position->x += $this->velocity->x;
			$this->position->y += $this->velocity->y;
			$this->position->z += $this->velocity->z;
		}

		public function energy(): int
		{
			return $this->position->energy() * $this->velocity->energy();
		}
	}
?>

<?php
	namespace App;

	use App\Utils\Maths;
	use Bolt\Files;

	class SphereOfInfluence
	{
		public array $bodies = array();
		public array $pairs = array();
		public array $initial = array();
		public array $matches = array();

		public function __construct()
		{
			// Todo: calculate dynamically after load
			$this->pairs = [
				[0, 1],
				[0, 2],
				[0, 3],
				[1, 2],
				[1, 3],
				[2, 3]
			];
		}

		public function load($override = null)
		{
			$data = ($override !== null) ? $override : trim((new Files())->load(ROOT . "data/12"));

			$positions = explode(PHP_EOL, $data);

			foreach ($positions as $position)
			{
				preg_match_all("/([-0-9]+)/", $position, $matches);

				$this->bodies[] = new Moon((int)$matches[0][0], (int)$matches[0][1], (int)$matches[0][2]);
			}

			$this->initial = json_decode(json_encode($this->bodies));

			$this->matches = [
				"x" => [
					false,
					null
				],
				"y" => [
					false,
					null
				],
				"z" => [
					false,
					null
				]
			];
		}

		public function applyGravity()
		{
			$axes = ["x", "y", "z"];

			foreach ($this->pairs as $pair)
			{
				foreach ($axes as $axis)
				{
					$a = $this->bodies[$pair[0]]->position->$axis;
					$b = $this->bodies[$pair[1]]->position->$axis;

					if ($a < $b)
					{
						$this->bodies[$pair[0]]->velocity->$axis++;
						$this->bodies[$pair[1]]->velocity->$axis--;
					}
					elseif ($a > $b)
					{
						$this->bodies[$pair[0]]->velocity->$axis--;
						$this->bodies[$pair[1]]->velocity->$axis++;
					}
				}
			}
		}

		public function updateVelocity()
		{
			foreach ($this->bodies as $body)
			{
				$body->applyVelocity();
			}
		}

		private function check($index, $axis): bool
		{
			$result = false;

			if (
				$this->bodies[$index]->position->$axis === $this->initial[$index]->position->$axis &&
				$this->bodies[$index]->velocity->$axis === $this->initial[$index]->velocity->$axis
			)
			{
				$result = true;
			}

			return $result;
		}

		private function checkMatches(int $iteration)
		{
			$axes = ["x", "y", "z"];

			foreach ($axes as $axis)
			{
				if ($this->check(0, $axis) && $this->check(1, $axis) && $this->check(2, $axis) && $this->check(3, $axis))
				{
					$this->matches[$axis] = [
						true,
						$iteration
					];
				}
			}
		}

		public function run($part = 1)
		{
			$loop = 0;
			$stopped = false;

			while ($stopped === false)
			{
				echo($loop . "\r");
				$this->applyGravity();
				$this->updateVelocity();
				$this->checkMatches($loop + 1);

				$loop++;

				switch ($part)
				{
					case 1:
						$stopped = ($loop < 2772) ? false : true;
						break;
					case 2:
						$stopped = ($this->matches['x'][0] === true && $this->matches['y'][0] === true && $this->matches['z'][0] === true) ? true : false;
						break;
				}
			}

			switch ($part)
			{
				case 1:
					$energy = 0;

					foreach ($this->bodies as $body)
					{
						$energy += $body->energy();
					}

					$result = $energy;
					break;
				case 2:

					$result = Maths::lcm($this->matches['x'][1], Maths::lcm($this->matches['y'][1], $this->matches['z'][1]));

					break;
				default:
					$result = false;
					break;
			}

			return $result;
		}
	}
?>
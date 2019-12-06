<?php
	namespace App;

	use Bolt\Files;

	class FuelManagement
	{
		public $paths;
		public $grid = array();
		public $steps = array();
		private $cursor;

		public function addPath(string $path)
		{
			$elements = explode(",", $path);

			$this->paths[] = array_map(function ($element)
			{
				return array(
					"direction" => $element[0],
					"distance" => (int)substr($element, 1)
				);
			}, $elements);
		}

		public function drawPath(int $index)
		{
			$this->cursor = array(0, 0);

			$path = $this->paths[$index];

			$function = null;

			$count = 0;

			foreach ($path as $step)
			{
				switch ($step['direction'])
				{
					case "U":
						$function = function ($cursor) {
							$cursor[1]++;

							return $cursor;
						};
						break;
					case "R":
						$function = function ($cursor) {
							$cursor[0]++;

							return $cursor;
						};
						break;
					case "D":
						$function = function ($cursor) {
							$cursor[1]--;

							return $cursor;
						};
						break;
					case "L":
						$function = function ($cursor) {
							$cursor[0]--;

							return $cursor;
						};
						break;
				}

				for ($loop = 0; $loop < $step['distance']; $loop++)
				{
					$this->cursor = $function($this->cursor);
					$identifier = $this->cursor[0] . "," . $this->cursor[1];

					if (!isset($this->grid[$identifier]))
					{
						$this->grid[$identifier] = 0;
					}

					$this->grid[$identifier] |= 1 << $index;

					$count++;

					if (!isset($this->steps[$index][$identifier]))
					{
						$this->steps[$index][$identifier] = $count;
					}
				}
			}
		}

		public function drawPaths()
		{
			for ($index = 0; $index < count($this->paths); $index++)
			{
				$this->drawPath($index);
			}
		}

		public function load()
		{
			$data = (new Files())->load(ROOT . "data/03");
			$paths = explode("\n", trim($data));

			foreach ($paths as $path)
			{
				$this->addPath($path);
			}
		}

		public function run(int $part): int
		{
			$this->load();
			$this->drawPaths();

			switch ($part)
			{
				case 1:
					$result = $this->closest();
					break;
				case 2:
					$result = $this->shortest();
					break;
				default:
					$result = 0;
					break;
			}

			return $result;
		}

		private function getCrossovers(): array
		{
			return array_keys(array_filter($this->grid, function ($element) {
				return $element === 3;
			}));
		}

		public function closest(): int
		{
			$crossovers = $this->getCrossovers();

			$distances = array();

			foreach ($crossovers as $crossover)
			{
				list($x, $y) = explode(",", $crossover);

				$distances[] = abs((int)$x) + abs((int)$y);
			}

			return min($distances);
		}

		public function shortest(): int
		{
			$crossovers = $this->getCrossovers();

			$steps = array();

			foreach ($crossovers as $crossover)
			{
				$steps[] = $this->steps[0][$crossover] + $this->steps[1][$crossover];
			}

			return min($steps);
		}
	}
?>

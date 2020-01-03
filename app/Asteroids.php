<?php
	namespace App;

	use Bolt\Files;
	use Bolt\Maths;

	class Asteroids
	{
		private array $data = array();
		private array $mapData = array();

		public function __construct()
		{
			$data = trim((new Files())->load(ROOT . "data/10/input"));

			$data = explode(PHP_EOL, $data);

			foreach ($data as &$next)
			{
				$next = str_split($next, 1);
			}

			$this->data = $data;
		}

		private function map()
		{
			for ($y = 0; $y < count($this->data); $y++)
			{
				for ($x = 0; $x < count($this->data[0]); $x++)
				{
					if ($this->data[$y][$x] === "#")
					{
						$this->mapData["$x,$y"] = $this->targeting($x, $y);
					}
				}
			}
		}

		public function run(int $part = 1)
		{
			$this->map();

			$count = 0;
			$index = "";

			foreach ($this->mapData as $key => $value)
			{
				if (count($value) > $count)
				{
					$count = count($value);
					$index = $key;
				}
			}

			if ($part === 1)
			{
				return $count;
			}

			return $this->laser($index, 200);
		}

		public function laser($index, $target)
		{
			$count = 0;
			$targetData = $this->mapData[$index];

			$lastNode = array();

			while (true)
			{
				foreach ($targetData as &$next)
				{
					if (count($next['nodes']) > 0)
					{
						if ($count >= ($target - 1))
						{
							$lastNode = explode(",", $next['nodes'][0]['coords']);

							break 2;
						}

						array_shift($next['nodes']);

						$count++;
					}

				}
			}

			return ($lastNode[0] * 100) + $lastNode[1];
		}

		public function targeting($x, $y)
		{
			$data = array();

			for ($yPos = 0; $yPos < count($this->data); $yPos++)
			{
				for ($xPos = 0; $xPos < count($this->data[0]); $xPos++)
				{
					if ($this->data[$yPos][$xPos] === "#" && ($x !== $xPos || $y !== $yPos))
					{
						$deltaX = $xPos - $x;
						$deltaY = $yPos - $y;

						$theta = atan2($deltaY, $deltaX);

						// starts at top not right;
						$theta += Maths::tau() / 4;

						if ($theta < 0)
						{
							$theta += Maths::tau();
						}

						$data[(string)$theta][] = [
							"coords" => "$xPos,$yPos",
							"distance" => sqrt(pow($deltaX, 2) + pow($deltaY, 2))
						];
					}
				}
			}

			$realData = array();

			foreach ($data as $key => $value)
			{
				usort($value, function ($a, $b) {
					return ($a['distance'] < $b['distance']) ? -1 : 1;
				});

				$realData[] = [
					"angle" => (float)$key,
					"nodes" => $value
				];
			}

			usort($realData, function ($a, $b) {
				return ($a['angle'] < $b['angle']) ? -1 : 1;
			});

			return $realData;
		}
	}
?>

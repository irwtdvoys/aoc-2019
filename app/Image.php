<?php
	namespace App;

	use Bolt\Files;

	class Image
	{
		private int $width;
		private int $height;
		private array $layers;

		public function __construct(int $width, int $height)
		{
			$this->height = $height;
			$this->width = $width;
		}

		public function load(string $override = null): void
		{
			$filename = ($override !== null) ? $override : ROOT . "data/08/input";

			$data = trim((new Files())->load($filename));

			$pixels = $this->width * $this->height;

			$this->layers = str_split($data, $pixels);
		}

		public function checksum(): int
		{
			$max = strlen($this->layers[0]);
			$layerIndex = 0;

			for ($loop = 0; $loop < count($this->layers); $loop++)
			{
				$layer = $this->layers[$loop];
				$result = substr_count($layer, "0");

				if ($result < $max)
				{
					$max = $result;
					$layerIndex = $loop;
				}
			}

			$layer = $this->layers[$layerIndex];

			return substr_count($layer, "1") * substr_count($layer, "2");
		}

		public function output(): string
		{
			$result = "";
			$output = array();
			$length = strlen($this->layers[0]);

			for ($index = 0; $index < $length; $index++)
			{
				foreach ($this->layers as $layer)
				{
					if ($layer[$index] !== "2")
					{
						$output[$index] = $layer[$index] === "1" ? "X": " ";
						break;
					}
				}
			}

			$output = str_split(implode("", $output), $this->width);

			foreach ($output as $line)
			{
				$result .= $line . PHP_EOL;
			}

			return $result;
		}
	}
?>

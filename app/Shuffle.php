<?php
	namespace App;

	use Bolt\Files;

	class Shuffle
	{
		public array $deck;
		public array $instructions;

		public function __construct(int $cards = 10007)
		{
			$this->initialise($cards);
		}

		public function initialise(int $cards)
		{
			for ($index = 0; $index < $cards; $index++)
			{
				$this->deck[] = $index;
			}
		}

		public function load($override = null)
		{
			$filename = isset($override) ? $override : ROOT . "data/22/input";

			$data = trim((new Files())->load($filename));

			$this->instructions = explode(PHP_EOL, $data);
		}

		public function processTechnique(string $technique)
		{
			if (strpos($technique, "deal into") === 0) // deal into new stack
			{
				$this->deck = array_reverse($this->deck);
			}
			elseif (strpos($technique, "deal with") === 0) // deal with increment
			{
				$value = (int)substr($technique, 20);

				$count = count($this->deck);
				$result = $this->deck;

				for ($index = 0; $index < $count; $index++)
				{
					$position = ($index * $value) % $count;
					$result[$position] = $this->deck[$index];
				}

				$this->deck = $result;
			}
			elseif (strpos($technique, "cut") === 0)
			{
				$value = (int)substr($technique, 4);

				if ($value >= 0)
				{
					$cut = array_slice($this->deck, 0, $value);
					$remainder = array_slice($this->deck, $value);
					$this->deck = array_merge($remainder, $cut);
				}
				else
				{
					$cut = array_slice($this->deck, $value);
					$remainder = array_slice($this->deck, 0, $value);
					$this->deck = array_merge($cut, $remainder);
				}
			}
		}

		public function run()
		{
			foreach ($this->instructions as $instruction)
			{
				$this->processTechnique($instruction);
			}

			$result = array_flip($this->deck);

			return $result[2019];
		}
	}
?>

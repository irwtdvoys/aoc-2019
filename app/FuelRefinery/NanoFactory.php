<?php
	namespace App\FuelRefinery;

	use Bolt\Files;
	use Exception;

	class NanoFactory
	{
		public array $reactions = array();
		public array $quantities = array();

		public int $rawOre = 1000000000000;//PHP_INT_MAX;

		public function __construct()
		{
			$this->quantities["ORE"] = $this->rawOre;
		}

		public function load($override = null)
		{
			$filename = ($override !== null) ? $override : ROOT . "data/14/input";

			$data = (new Files())->load($filename);

			$reactions = explode(PHP_EOL, trim($data));

			foreach ($reactions as $reaction)
			{
				$object = new Reaction($reaction);
				$this->reactions[$object->output->name] = $object;
				$this->quantities[$object->output->name] = 0;
			}
		}

		public function processReaction(string $string, $quantity = 1)
		{
			if ($string === "ORE")
			{
				throw new Exception("No ORE left " . $quantity . " requested");
			}

			$reaction = $this->reactions[$string];
			$multiples = ceil($quantity / $reaction->output->quantity);

			$inputs = array();

			foreach ($reaction->input as $input)
			{
				$required = $input->quantity * $multiples;

				while ($this->quantities[$input->name] < $required)
				{
					$this->processReaction($input->name, $required);
				}

				$this->quantities[$input->name] -= $required;
			}

			$this->quantities[$reaction->output->name] += $reaction->output->quantity * $multiples;

			return $inputs;
		}

		public function reset()
		{
			$this->quantities["ORE"] = $this->rawOre;

			foreach ($this->reactions as $reaction)
			{
				$this->quantities[$reaction->output->name] = 0;
			}
		}

		public function run($part = 1)
		{
			$required = 1;

			$loop = 0;

			while ($this->quantities["FUEL"] < $required)
			{
				$this->processReaction("FUEL", 1);
				$loop++;
			}

			$result = $this->rawOre - $this->quantities["ORE"];

			if ($part === 2)
			{
				$this->reset();

				while (true)
				{
					try
					{
						$estimate = ceil(($this->quantities["ORE"] / $result) / 2);
						$this->processReaction("FUEL", $estimate);
					}
					catch (Exception $exception)
					{
						break;
					}
				}

				$result = $this->quantities["FUEL"];
			}

			return $result;
		}
	}
?>

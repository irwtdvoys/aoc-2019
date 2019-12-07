<?php
	namespace App;

	class Amplifiers
	{
		public array $sequences = array();

		/**
		 * @var Intcode[]
		 */
		public array $amps = array();

		public function setup(array $sequence)
		{
			$this->amps = array();

			for ($loop = 0; $loop < count($sequence); $loop++)
			{
				$computer = new Intcode(true);
				#$computer->setProgram("3,26,1001,26,-4,26,3,27,1002,27,2,27,1,27,26,27,4,27,1001,28,-1,28,1005,28,6,99,0,0,5");
				$computer->load(ROOT . "data/07");

				$this->amps[] = $computer;
			}
		}

		public function processSequence(array $sequence)
		{
			$this->setup($sequence);

			$result = 0;
			$count = 0;
			$initialise = true;

			while (true)
			{
				$inputs = ($initialise === true) ? [(int)$sequence[$count], $result] : [$result];

				$output = $this->amps[$count]->run($inputs);

				if ($output === "")
				{
					return $result;
				}

				$result = (int)$output;

				$count++;

				if ($count === 5)
				{
					$initialise = false;
					$count = 0;
				}
			}

			return $result;
		}

		public function generateSequences(array $values, array $current = array())
		{
			if (count($values) === 1)
			{
				$this->sequences[] = array_merge($current, $values);
			}

			foreach ($values as $value)
			{
				$others = array_values(array_diff($values, array($value)));
				$this->generateSequences($others, array_merge($current, [$value]));
			}
		}

		public function run(array $settings)
		{
			$max = 0;
			$this->generateSequences($settings);

			$loop = 0;

			foreach ($this->sequences as $sequence)
			{
				$result = $this->processSequence($sequence);

				$max = max($max, $result);

				$loop++;
			}

			return $max;
		}
	}
?>
<?php
	namespace App\FuelRefinery;

	class Reaction
	{
		/** @var Chemical[] */
		public array $input;
		public Chemical $output;

		public function __construct(string $reaction = null)
		{
			if ($reaction !== null)
			{
				list($inputs, $output) = explode(" => ", $reaction);

				$inputs = explode(", ", $inputs);

				$this->addOutput(new Chemical($output));

				foreach ($inputs as $input)
				{
					$this->addInput(new Chemical($input));
				}
			}
		}

		public function addOutput(Chemical $chemical)
		{
			$this->output = $chemical;
		}

		public function addInput(Chemical $chemical)
		{
			$this->input[] = $chemical;
		}
	}
?>

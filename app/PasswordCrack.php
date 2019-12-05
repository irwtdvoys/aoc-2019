<?php
	namespace App;

	class PasswordCrack
	{
		private $range;
		private $rules;

		public function __construct(int $from, int $to)
		{
			$this->range = (object)array(
				"min" => $from,
				"max" => $to
			);
		}

		public function run($part = 1)
		{
			$count = 0;

			$countDouble = 0;
			$countIncrease = 0;

			$tests = array(112233, 123444, 111122);

			#foreach ($tmp as $loop)
			for ($loop = $this->range->min; $loop <= $this->range->max; $loop++)
			{
				$hasDouble = false;
				$increases = true;
				$badDoubles = false;

				$chains = array();

				$last = "";
				$chain = 1;

				for ($index = 0; $index < 6; $index++)
				{
					$current = (string)$loop;

					if ($current[$index] === $last)
					{
						$chain++;
					}
					else
					{
						if ($last !== "" && $chain > 1)
						{
							$chains[] = $chain;
							$chain = 1;
						}

						$last = $current[$index];
					}

					if ($index > 0)
					{
						if ($current[$index - 1] === $current[$index])
						{
							$hasDouble = true;
						}

						if ((int)$current[$index - 1] > (int)$current[$index])
						{
							$increases = false;
						}
					}
				}

				echo($loop . "\r");

				if ($part === 2)
				{
					if ($chain > 1)
					{
						$chains[] = $chain;
					}

					$chains = array_filter($chains, function ($var)
					{
						// filter to chains of exactly 2
						return $var === 2;
					});

					if (count($chains) === 0)
					{
						$badDoubles = true;
					}
				}

				if ($hasDouble && !$badDoubles)
				{
					$countDouble++;
				}

				if ($increases)
				{
					$countIncrease++;
				}

				if ($hasDouble === true && $increases === true && $badDoubles === false)
				{
					$count++;
				}
			}

			return $count;
		}
	}
?>

<?php
	namespace App\Utils;

	class Maths extends \Bolt\Maths
	{
		/**
		 * Greatest Common Divisor
		 *
		 * @param int $a
		 * @param int $b
		 * @return int
		 */
		public static function gcd(int $a, int $b): int
		{
			if ($a == 0)
			{
				return $b;
			}

			return self::gcd($b % $a, $a);
		}

		/**
		 * Lowest Common Multiple
		 *
		 * @param int $a
		 * @param int $b
		 * @return int
		 */
		public static function lcm(int $a, int $b): int
		{
			return ($a * $b) / self::gcd($a, $b);
		}
	}
?>
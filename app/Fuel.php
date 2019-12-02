<?php
	namespace App;
	
	class Fuel
	{
		public static function calculate(int $mass): int
		{
			return floor($mass / 3) - 2;
		}
		
		public static function recursive(int $mass): int
		{		
			$fuel = self::calculate($mass);
			
			if ($mass <= 0 || $fuel <= 0)
			{
				return 0;
			}
			
			return $fuel + self::recursive($fuel);
		}
	}
?>

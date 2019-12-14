<?php
	namespace App\FuelRefinery;

	class Chemical
	{
		public string $name;
		public int $quantity;

		public function __construct($input)
		{
			list($quantity, $name) = explode(" ", $input);

			$this->name = $name;
			$this->quantity = $quantity;
		}
	}
?>

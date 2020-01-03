<?php
	namespace App\Tiles;

	use Bolt\Enum;

	class Life extends Enum
	{
		const EMPTY = ".";
		const BUG = "#";

		public int $count;
		public string $state;

		public function __construct(string $state = self::EMPTY)
		{
			$this->count = 0;
			$this->state = $state;
		}
	}
?>

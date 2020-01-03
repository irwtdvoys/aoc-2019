<?php
	namespace App\Tiles;

	use Bolt\Enum;

	class Breakout extends Enum
	{
		const EMPTY = 0;
		const WALL = 1;
		const BLOCK = 2;
		const PADDLE = 3;
		const BALL = 4;
	}
?>

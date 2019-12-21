<?php
	namespace App;

	use Bolt\Files;

	class AsciiProcessor
	{
		public static function fetch(string $filename): array
		{
			$data = (new Files())->load($filename);

			return self::encode($data);
		}

		public static function encode(string $data): array
		{
			$encoded = array();

			$split = str_split($data, 1);

			foreach ($split as $next)
			{
				$encoded[] = ord($next);
			}

			return $encoded;
		}

		public static function output(string $data): string
		{
			$data = explode(PHP_EOL, trim($data));

			$output = "";

			foreach ($data as $next)
			{
				$output .= ($next >=0 && $next <=255) ? chr($next) : $next;
			}

			return $output . PHP_EOL;
		}
	}
?>

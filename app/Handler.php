<?php
	namespace App;

	use Bolt\Exception;
	use Cruxoft\Logbook;

	class Handler
	{
		public static function error($level, $message, $file, $line, $context)
		{
			throw new \Bolt\Exceptions\Error($message, 0, $level, $file, $line);
		}
		
		public static function exception($exception)
		{
			$className = get_class($exception);

			$type = $className;

			if ($exception instanceof Exception)
			{
				$type .= "::" . $exception->getCodeKey();
			}
			
			$data = array(
				"type" => $type,
				"message" => $exception->getMessage(),
				"code" => $exception->getCode(),
				"line" => $exception->getLine(),
				"file" => $exception->getFile(),
				"trace" => $exception->getTrace()
			);
			
			if (!is_string($data))
			{
				$data = json_encode($data, JSON_PRETTY_PRINT);
			}

			echo($data . "\n");
			
			
			#Logbook::get(Loggers::GENERAL)->error($exception->getMessage(), $data);

			return true;
		}
	}
?>

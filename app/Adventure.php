<?php
	namespace App;

	use App\Intcode\VirtualMachine;
	use App\Intcode\VM\InterruptTypes;

	class Adventure
	{
		private VirtualMachine $computer;

		private array $path;
		private array $inventory;

		public function __construct()
		{
			$this->computer = new VirtualMachine(InterruptTypes::INPUT);
			$this->computer->load(ROOT . "data/25/input");

			$this->path = array(
				"south",
				/*"west",
				"take shell",
				"east",*/
				"east",
				"take space heater",
				"west",
				"north",
				"west",
				"north",
				/*"take jam",*/
				"east",
				"south",
				"take asterisk",
				"south",
				"take klein bottle",
				/*"east",
				"take spool of cat6",
				"west",*/
				"north",
				"north",
				"west",
				"north",
				"take astronaut ice cream",
				/*"north",
				"east",
				"south",
				"take space law space brochure",
				"north",
				"west",
				"south",*/
				"south",
				"south",
				"south",
				"west",
				"south"
			);

			/*		astronaut ice cream
					space heater
					asterisk
					klein bottle*/


			$this->inventory = array(
				"spool of cat6",
				"space law space brochure",
				"asterisk",
				"jam",
				"shell",
				"astronaut ice cream",
				"space heater",
				"klein bottle"
			);
		}

		public function run($automated = false)
		{
			$input = [];

			while (true)
			{
				$result = $this->computer->run($input);

				echo(AsciiProcessor::output($result));

				if (count($this->path) > 0 && $automated === true)
				{
					$command = array_shift($this->path);
					$input = AsciiProcessor::encode($command . PHP_EOL);
				}
				else
				{
					$value = fgets(STDIN);
					$input = AsciiProcessor::encode($value);
				}
			}
		}
	}
?>

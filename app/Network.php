<?php
	namespace App;

	use App\Intcode\VirtualMachine;
	use App\Networking\Packet;

	class Network
	{
		/** @var VirtualMachine[] */
		public array $computers = array();

		/** @var Packet[] */
		public array $outputs = array();

		public Packet $nat;

		public function __construct()
		{
			$this->initialise();
		}

		public function initialise(int $nodes = 50)
		{
			$vm = new VirtualMachine();
			$vm->load(ROOT . "data/23/input");

			for ($loop = 0; $loop < $nodes; $loop++)
			{
				$computer = clone $vm;
				$computer->inputs->add([$loop]);

				$this->computers[] = $computer;

				$this->outputs[$loop] = new Packet();
			}
		}

		public function run($part = 1)
		{
			$count = 0;
			$cache = array();
			$idleCount = 0;

			while (true)
			{
				$loop = 0;

				foreach ($this->computers as $computer)
				{
					// Provide input of -1 if no inputs
					if ($computer->inputs->count() === 0)
					{
						$computer->inputs->add([-1]);
					}

					$computer->step();

					// Remove -1 input if it wasn't used
					if ($computer->inputs->get() === array(-1))
					{
						$computer->inputs->clear();
					}

					// If vm is producing output start building it's output packet
					if (count($computer->output) > 0)
					{
						$idleCount = 0;
						$this->outputs[$loop]->add($computer->output[0]);
						$computer->output = array();

						// If output packet is complete
						if ($this->outputs[$loop]->isComplete())
						{
							$packet = $this->outputs[$loop];

							if ($packet->address() === 255)
							{
								$this->nat = clone $packet;

								if ($part === 1)
								{
									return $packet->y();
								}
							}
							else
							{
								$this->computers[$packet->address()]->inputs->add($packet->data());
							}

							$this->outputs[$loop]->reset();
						}
					}

					$loop++;
				}

				if ($this->isIdle() === true)
				{
					$idleCount++;
				}

				// check inputs
				// if all empty network idle trigger nat
				if ($idleCount > 1000) // 50 vms need to be idling for multiple cycles
				{
					$this->computers[0]->inputs->add($this->nat->data());
					$idleCount = 0;

					if (in_array($this->nat->y(), $cache))
					{
						if ($part === 2)
						{
							return $this->nat->y();
						}
					}

					$cache[] = $this->nat->y();
				}

				$count++;
			}

			return false;
		}

		public function isIdle(): bool
		{
			$result = true;
			$loop = 0;

			foreach ($this->computers as $computer)
			{
				if (!$this->outputs[$loop]->isEmpty() || $computer->inputs->count() > 0)
				{
					$result = false;

					break;
				}

				$loop++;
			}

			return $result;
		}
	}
?>

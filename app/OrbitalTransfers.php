<?php

	namespace App;

	use Bolt\Files;

	class OrbitalTransfers
	{
		public array $bodies = array();
		public array $orbits = array();

		public array $data = array();

		public function load(string $override = null)
		{
			$data = (!isset($override)) ? (new Files())->load(ROOT . "data/06") : $override;

			$lines = explode(PHP_EOL, trim($data));

			foreach ($lines as $line)
			{
				$orbit = explode(")", $line);
				$this->orbits[] = $orbit;
				$this->bodies = array_unique(array_merge($this->bodies, $orbit));
			}
		}

		public function calculateOrbitCounts(&$node, $level = 0)
		{
			$node->orbits = $level;

			if (count($node->links))
			{
				foreach ($node->links as &$link)
				{
					$this->calculateOrbitCounts($link, $level + 1);
				}
			}
		}

		public function totalOrbits($bodies)
		{
			$count = 0;

			foreach ($bodies as $next)
			{
				$count += $next->orbits;
			}

			return $count;
		}

		public function orbitalChanges($from, $to)
		{
			$from = $this->data[$from];
			$to = $this->data[$to];
			$current = $from;

			for ($orbit = $from->orbits; $orbit > 0; $orbit--)
			{
				$isRoot = $current->contains($to->name);

				if ($isRoot === true)
				{
					break;
				}

				$current = $this->data[$current->parent];
			}

			return ($from->orbits - $current->orbits) + ($to->orbits - $current->orbits) - 2;
		}

		public function buildTree()
		{
			foreach ($this->bodies as $body)
			{
				$this->data[$body] = new Body($body);
			}

			// create tree
			foreach ($this->orbits as $orbit)
			{
				$this->data[$orbit[0]]->links[] = &$this->data[$orbit[1]];
				$this->data[$orbit[1]]->parent = &$this->data[$orbit[0]]->name;
			}

			$this->calculateOrbitCounts($this->data['COM']);
		}

		public function run($part = 1): int
		{
			$this->buildTree();

			$result = 0;

			switch ($part)
			{
				case 1:
					$result = $this->totalOrbits($this->data);
					break;
				case 2:
					$result = $this->orbitalChanges("YOU", "SAN");
					break;
			}

			return $result;
		}
	}
?>

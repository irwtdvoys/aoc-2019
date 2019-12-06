, <?php

	namespace App;

	class Body
	{
		public string $name;
		public array $links;
		public int $orbits;
		public ?string $parent;

		public function __construct(string $name)
		{
			$this->name = $name;
			$this->links = array();
			$this->orbits = 0;
			$this->parent = null;
		}

		public function contains($name)
		{
			$data = json_encode($this->links);

			return (strpos($data, $name) !== false) ? true : false;
		}
	}
?>

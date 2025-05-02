<?php
	session_start();

	// Includes
	include "./php/common.php";
	include "./loaderTop.php";
	/// JSON
	//// cf. Loader top
	$AVAILABLE = $all['r']['r2'];
	/// DB Connect
	$ELEMS = DB->fetch($conn_elems, "SELECT id, default_name FROM all_index WHERE type='planet'");
	print_r($ELEMS);
	// Code de generation des elements
	

	/// Planète,
	class Planet {
		public $name;
		public $radius;
		public $distanceSun;
		public $revolutionSpeed;
		public $ressources;
		public $type;

		const probaGazeous = 0;

		function __construct() {
			//// Initialisation de la planète
			$this->type = "planet";
			$this->name = "NAME";
			$this->radius = rand(2000000, 28000000);
			$this->distanceSun = 0;
			$this->revolutionSpeed = 0;
			if (rand(1, 100) <= probaGazeous) {
				$this->subtype = "gaz";
			} else {
				$this->subtype = "solid";
			}
			///// Initialisation des couches minerales, index 0 etant la surface.
			///// Divisée en couche de 500m, chaque ressource a des profondeurs max et min de spawn et un score de rareté.
			///// Height: profondeur depuis la surface, Width: largeur de la bande potentielle, Rarity: [0,1] chance d'apparaitre.
			$this->ressources = [];
			for ($i=0; $i <= $this->radius; $i += 500) {
				


			}

		}


		
		




	}
	
	new Planet();
	
	





























?>




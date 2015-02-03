<?php
	class Cancion extends Eloquent {
		protected $table = "cancion";
		public $timestamps = false;

		public function genero(){
			return $this->belongsTo("Genero");
		}

		public function interprete(){
			return $this->belongsTo("Interprete");
		}

		public function album(){
			return $this->belongsTo("Album");
		}
	}
?>
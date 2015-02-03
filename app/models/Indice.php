<?php
	class Indice extends Eloquent {
		protected $table = "indice";
		public $timestamps = false;

		public function palabra(){
			return $this->belongsTo("Palabra");
		}

		public function cancion(){
			return $this->belongsTo("Cancion");
		}
	}
?>
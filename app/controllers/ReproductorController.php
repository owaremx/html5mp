<?php
	class ReproductorController extends BaseController{
		function getIndex(){
			return View::make("reproductor/index");
		}

		function getAyuda(){
			return View::make("reproductor/ayuda");	
		}
	}
?>
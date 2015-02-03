<?php 
	class LogicaMusica{
		public function __construct(){

		}

		public function buscarInterprete($nombre){
			try{
				return Interprete::where("nombre", "=", $nombre)->firstOrFail();
			}
			catch(Exception $e){
				return null;
			}
		}

		public function buscarGenero($nombre){
			try{
				return Genero::where("nombre", "=", $nombre)->firstOrFail();
			}
			catch(Exception $e){
				return null;
			}
		}

		public function buscarCancionHash($hash){
			try{
				return Cancion::where("hash", "=", $hash)->firstOrFail();
			}
			catch(Exception $e){
				return null;
			}
		}

		public function buscarAlbum($nombre, $interprete_id){
			try{
				return Album::where("nombre", "=", $hash).where("interprete_id","=",$interprete_id)->firstOrFail();
			}
			catch(Exception $e){
				return null;
			}
		}
	}

	function endsWith($haystack, $needle)
	{
	    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
?>
<?php
	class CancionesController extends BaseController{
		public function getIndex(){
			$canciones = Cancion::all();

			return View::make("canciones/index", array("canciones"=>$canciones));
		}

		public function getNueva(){
			$interpretes_bd = Interprete::all();

			$interpretes = array();
			$interpretes[0] = "";
			foreach ($interpretes_bd as $key => $value) {
				$interpretes[$value->id] = $value->nombre;
			}

			$generos_bd = Genero::all();
			$generos = array();
			$generos[0] = "";
			foreach ($generos_bd as $key => $value) {
				$generos[$value->id] = $value->nombre;
			}

			return View::make("canciones/nueva", array("generos"=>$generos, "interpretes"=>$interpretes));
		}

		public function getSubir(){

			return View::make("canciones/subir");
		}

		public function getExaminar(){

			return View::make("canciones/examinar");
		}

		private function recorrerDirectorio($dir) {
			require_once(app_path() . "/includes/logica.php");
			$cdir = scandir($dir);
			foreach ($cdir as $key => $value)
			{

		      	if (!in_array($value,array(".","..")))
				{
					if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
					{
						$this->recorrerDirectorio($dir . DIRECTORY_SEPARATOR . $value);
					}
					else
					{
						try{
							$this->guardarCancion($dir . DIRECTORY_SEPARATOR . $value ,  true);
						}
						catch(Exception $e){
							//No hacer nada
						}
					}
				}
		    }
		}

		public function getArbol(){
			$rutas = "[" . $this->construirArbol("/home/musica/Music");

			$rutas = rtrim($rutas,',') . "]";

			$respuesta = Response::make($rutas);
			$respuesta->header("Content-type:","application/json");
			return $respuesta;
		}

		public function postProcesar(){
			$archivo = Input::get("archivo");

			try{
				$this->guardarCancion($archivo,  true);
				$salida = '{"Correcto":true, "Mensaje" : "Archivo guardado correctamente"}';
			}
			catch(Exception $e){
				$salida = '{"Correcto":false, "Mensaje":"Error: '.$e->getMessage().'"}';
			}

			$response = Response::make($salida);
			$response->header("Content-type", "application/json");
			return $response;
		}

		private function construirArbol($dir) {
			$cdir = scandir($dir);

			$json = "";
			foreach ($cdir as $key => $value)
			{
		      	if (!in_array($value,array(".","..")))
				{
					$ruta = $dir . DIRECTORY_SEPARATOR . $value;
					if (is_dir($ruta))
					{
						$json .= $this->construirArbol($ruta);
					}
					else
					{
						$json.=  '{"Archivo":"' . $ruta . '", "Status":"Sin procesar"},';
					}
				}
		    }
		    return $json;
		} 

		public function postMultiNueva(){
			header("Content-type: application/json");
			$archivo = Input::file("archivo_subir");

			try{
				$this->guardarCancion($archivo, false);
				$salida = '{"Correcto":true}';
			}
			catch(Exception $ex){
				$salida = '{"Correcto":false, "Mensaje":"'. $ex->getMessage() . '"}';
			}

			$respuesta = Response::make($salida);
			$respuesta->header("Content-type", "application/json");

			return $respuesta;
		}

		public function postNueva() {
			foreach(Input::file("archivo") as $archivo)
			{
				$this->guardarCancion($archivo, false);
			}

			$interpretes_bd = Interprete::all();

			$interpretes = array();
			$interpretes[0] = "";
			foreach ($interpretes_bd as $key => $value) {
				$interpretes[$value->id] = $value->nombre;
			}

			$generos_bd = Genero::all();
			$generos = array();
			$generos[0] = "";
			foreach ($generos_bd as $key => $value) {
				$generos[$value->id] = $value->nombre;
			}

			return View::make("canciones/nueva", array("generos"=>$generos, "interpretes"=>$interpretes));
		}

		public function postRuta(){
			//Obtiene la ruta del archivo
			$id = Input::get("id");

			$cancion = Cancion::find($id);
			if($cancion){
				$salida = '{"correcto": true, "url":"/public'.$cancion->ruta_archivo.'", "titulo":"'.$cancion->nombre.'", "interprete":"'. @$cancion->interprete->nombre . '"}';
				$cancion->reproducciones = $cancion->reproducciones + 1;
				$cancion->save();
			}
			else {
				$salida = '{"correcto": false}';
			}
			$respuesta = Response::make($salida);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}

		public function getBusqueda($term = null){
			if($term == null){
				$term = "";
			}
/*
			$palabras = explode(' ', $term);
			$condiciones = "";
			for($i = 0; $i < count($palabras); $i++){
				$condiciones = $condiciones . " texto LIKE ? ";
				$palabras[$i] = "%" . $palabras[$i] . "%";

				if($i != count($palabras) - 1)
				{
					$condiciones = $condiciones . " OR ";
				}
			}

			//Buscar en el índice, la búsqueda es más rápida
			$canciones = Palabra::
				whereRaw($condiciones, $palabras)->
				join("indice", "indice.palabra_id", "=", "palabra.id")->
				join("cancion", "indice.cancion_id", "=", "cancion.id")->
				leftJoin("interprete", "cancion.interprete_id", "=", "interprete.id")->
				leftJoin("album", "cancion.album_id", "=", "album.id")->
				groupBy("cancion.id")->
				orderBy("cancion.nombre")->
				take(150)->
				get(array("cancion.id", "cancion.nombre as titulo", "interprete.nombre as interprete", "cancion.id", "album.nombre as album"));
*/

			$patron = "/[^\w\(\)\. áéíóúÁÉÍÓÚñÑüÜ&]/";
			$str_term = '%' . $term . '%';
			$canciones = Cancion::
				leftJoin("interprete", "interprete.id","=", "cancion.interprete_id")->
				leftJoin("album", "cancion.album_id", "=", "album.id")->
				leftJoin("genero", "cancion.genero_id", "=", "genero.id")->
				whereRaw("interprete.nombre LIKE ? OR cancion.nombre LIKE ? OR genero.nombre like ? OR album.nombre LIKE ?", array($str_term, $str_term, $str_term, $str_term))->
				orderBy("cancion.nombre")->
				take(150)->
				get(array("cancion.id", "cancion.nombre as titulo", "interprete.nombre as interprete", "cancion.id", "album.nombre as album"));

			$json = "[";

			foreach ($canciones as $key => $cancion) {
				$json .= '{"Id" : "'.$cancion->id.'", "Titulo":"' . preg_replace($patron, "", $cancion->titulo) . '","Interprete":"' . preg_replace($patron, "", @$cancion->interprete) .'", "Album":"' . preg_replace($patron, "", @$cancion->album)  . '"},';
			}
			$json = rtrim($json, ",");
			$json .= "]";

			$respuesta = Response::make($json);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}

		public function getExplorar(){
			return View::make("canciones/explorar");
		}

		public function getResultadosBusqueda(){
			return View::make("canciones/resultados-busqueda");
		}

		private function guardarCancion($archivo /*posted file*/, $esRuta){
			require_once(app_path() . "/includes/getid3/getid3.php");
			require_once(app_path() . "/includes/logica.php");
			
			$logica = new LogicaMusica();
			$getID3 = new getID3();


			if(!$esRuta){
				$hash = hash_file("sha1", $archivo->getRealPath());
				$realname = $hash . "." . strtolower($archivo->getClientOriginalExtension());

				$dir_destino = app_path() . "/temporal/";

				if(!file_exists($dir_destino))
					mkdir($dir_destino);

				//Mover el archivo a una ubicación de pruebas y ponerle la extensión original ya que si no, no lee bien los tags
				$archivo->move($dir_destino, $realname);

				$realname = $dir_destino . $realname;
			}
			else
			{
				$hash = hash_file("sha1", $archivo);

				$realname = $archivo . "." . strtolower(pathinfo($archivo)["extension"]);

				//renombrar el archivo para poner todo en minusculas
				rename($archivo, $realname);
			}

			if(!(endsWith($realname,".mp3") || endsWith($realname, ".m4a") || endsWith($realname,".mp4")))
			{
				throw new Exception("No es un archivo válido: " . $realname);

				unlink($realname);
			}

			$info = $getID3->analyze($realname);

			getid3_lib::CopyTagsToComments($info);

			$cancion = $logica->buscarCancionHash($hash);
			if($cancion == null){
				//no existe la canción, agregarla
				if(isset($info["comments"]))
				{
					$genre = @$info["comments"]["genre"][0];

					if($genre == null)
						$genre = "";

					//Tiene id3
					$genero = $logica->buscarGenero($genre);
					if($genero == null){
						$genero  = new Genero();
						$genero->nombre = $genre;
						$genero->save();
					}

					$str_interprete = @$info["comments"]["artist"][0];
					if($str_interprete == null)
						$str_interprete = "";

					$interprete = $logica->buscarInterprete($str_interprete);
					if($interprete == null){
						$interprete = new Interprete();
						$interprete->nombre = $str_interprete;
						$interprete->save();
					}

					$str_album = @$info["comments"]["album"][0];
					if($str_album == null)
						$str_album = "";

					$album = $logica->buscarAlbum($str_album, $interprete->id);
					if($album == null){
						$album = new Album();
						$album->nombre = $str_album;
						$album->interprete_id = $interprete->id;
						$album->save();
					}

					$str_titulo = trim(@$info["comments"]["title"][0]);
					if(strlen($str_titulo) == 0 || strpos($str_titulo, "track") !== false ||  strpos($str_titulo, "pista") !== false || strpos($str_titulo, "desconocido") !== false)
					{
						throw new Exception("El archivo no está etiquetado correctamente con los tags id3, etiquételo y súbalo nuevamente", 1);
					}

					$cancion = new Cancion();
					$cancion->nombre = trim($str_titulo);
					$cancion->anio = trim(@$info["comments"]["year"][0]);
					$cancion->genero_id = $genero->id;
					$cancion->interprete_id = $interprete->id;
					$cancion->album_id = $album->id;
					$cancion->fecha_agregado = time();

					$interprete_id = $interprete->id;

					$palabras = $cancion->nombre . "," . $interprete->nombre . "," . $genero->nombre;
				}
				else {
					/*
					//No tiene id3
					$cancion = new Cancion();
					if(!$esRuta)
						$cancion->nombre = $archivo->getClientOriginalName();
					else
						$cancion->nombre = pathinfo($archivo)["basename"];

					$interprete_id = 0;

					$palabras = $cancion->nombre;*/
					throw new Exception("El archivo no está etiquetado correctamente con los tags id3, etiquételos y súbalos nuevamente");
				}

				$ruta_archivo = sprintf("/audio/%s/", $interprete_id);
				$destino = public_path() . $ruta_archivo;
				if(!file_exists($destino))
					mkdir($destino, 0777, true);

				if(!$esRuta)
					$nombre_archivo = sprintf("%s.%s", $hash, $archivo->getClientOriginalExtension());
				else
					$nombre_archivo = sprintf("%s.%s", $hash, pathinfo($archivo)["extension"]);

				/*if(!$esRuta)
					$archivo->move($destino, $nombre_archivo);
				else*/

				//Mover el archivo a la estructura de directorios especial
				rename($realname, $destino . $nombre_archivo);

				$cancion->hash = $hash;
				$cancion->ruta_archivo = $ruta_archivo . $nombre_archivo;

				$cancion->save();

				//extraer las palabras que tiene la canción para agregarlas al índice
				$patron = "/[A-Za-záéíóúÁÉÍÓÚüÜñÑ]+/";

				$matches = array();
				preg_match_all($patron, $palabras, $matches, PREG_PATTERN_ORDER);

				foreach ($matches as $val) {
					foreach($val as $p){
						$plower = strtolower($p);

						if(strlen($plower) <= 1 || $plower == "mp" || $plower == "wma" || $plower == "m4a")
							continue;

						
						//try{
							$palabra = Palabra::where("texto", "=", $p)->first();
						/*}	
						catch(Exception $e){
							$palabra = null;
						}*/
						if($palabra == null) {
							//No existe la palabra, crearla y crear el índice
							$palabra = new Palabra();
							$palabra->texto = $plower;
							$palabra->save();
						}

						// Una vez que tenemos la palabra, guardarla
						$indice = new Indice();
						$indice->palabra_id = $palabra->id;
						$indice->cancion_id = $cancion->id;
						$indice->save();
					}
				}

			}
		}
	}
?>
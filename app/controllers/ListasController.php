<?php
	class ListasController extends BaseController{
		/*
		Obtiene las listas de reproducción de un usuario autenticado
		*/

		public function getListas(){
			$usuario = Auth::id();

			$listas = ListaReproduccion::where("usuario_id", "=", $usuario)->get();
			$json = "[";
			foreach ($listas as $lista) {
				$json .= '{"Id":' . $lista->id . ', "Nombre":"' . $lista->nombre . '"},';
			}
			$json = rtrim($json,",") . "]";

			$respuesta = Response::make($json);
			$respuesta->header("Content-type", "application/json");
			return $respuesta;
		}

		public function postNueva(){
			$nombre = Input::get("nombre");
			$usuario = Auth::id();

			$lista = new ListaReproduccion();
			$lista->nombre = $nombre;
			$lista->usuario_id = $usuario;

			try{
				$lista->save();
				$salida = '{"Correcto":true, "lista" : {"Id":' . $lista->id . ', "Nombre" : "' . $nombre . '"}}';
			}
			catch(Exception $e){
				$salida = '{"Correcto":false, "mensaje" : "'.$e->getMessage().'"}';
			}

			$respuesta = Response::make($salida);
			$respuesta->header("Content-type", "application/json");
			return $respuesta;
		}

		public function getCanciones($lista_id)
		{
			if($lista_id == 0)
				return $this->getAleatoria();

			$canciones = Cancion::
				join("detalle_lista", "detalle_lista.cancion_id", "=", "cancion.id")->
				join("lista_reproduccion", "lista_reproduccion.id", "=", "detalle_lista.lista_id")->
				leftJoin("interprete", "interprete.id", "=", "cancion.interprete_id")->
				where("detalle_lista.lista_id","=",$lista_id)->
				whereAnd("lista_reproduccion.usuario_id", "=", Auth::id())->
				get(array("cancion.id","interprete.nombre as interprete", "cancion.nombre as titulo", "detalle_lista.id as detalle_id"));

			$json = "[";

			foreach($canciones as $cancion){
				$json .= '{"Id" : "'.$cancion->id.
					'","Titulo":"' . str_replace('"', "'", $cancion->titulo) . 
					'","Interprete":"' . str_replace('"', "'", @$cancion->interprete) .
					'","DetalleId":' . $cancion->detalle_id  . 
				'},';
			}

			$json = rtrim($json, ",");
			$json .= "]";

			$respuesta = Response::make($json);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}

		public function postCancion(){
			if(!Auth::check()){
				//No se ha logueado el usuario, agregar la canción pero sin detalle
				$salida = '{"Correcto":true, "DetalleId":0}';
				$respuesta = Response::make($salida);
				$respuesta->header("Content-type","application/json");
				return $respuesta;
			}

			$lista_id = Input::get("lista_id");
			$cancion_id = Input::get("cancion_id");
			$orden = Input::get("orden");

			$detalle = new DetalleListaReproduccion();
			$detalle->lista_id =$lista_id;
			$detalle->cancion_id = $cancion_id;
			$detalle->orden = $orden;

			try{
				$detalle->save();
				$salida = '{"Correcto":true, "DetalleId":' . $detalle->id . '}';
			}
			catch(Exception $e){
				$salida = '{"Correcto":false, "Mensaje" : "'.$e->getMessage().'"}';
			}

			$respuesta = Response::make($salida);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}

		public function postEliminarCancion(){
			if(!Auth::check()){
				//No se ha logueado el usuario, agregar la canción pero sin detalle
				$salida = '{"Correcto":true}';
				$respuesta = Response::make($salida);
				$respuesta->header("Content-type","application/json");
				return $respuesta;
			}

			$detalle_id = Input::get("detalle_id");
			$usuario_id = Auth::id();

			$detalle = DetalleListaReproduccion::
				where("id","=",$detalle_id)->
				whereAnd("usuario_id", "=", $usuario_id)->
				first();

			if($detalle){
				try{
					$detalle->delete();
					$salida = '{"Correcto":true}';
				}
				catch(Exception $e){
					$salida = '{"Correcto":false, "Mensaje" : "' . $e->getMessage() . '"}';
				}
			}
			else{
				$salida = '{"Correcto":false,"Mensaje":"No tiene asignada esa canción"}';
			}

			$respuesta = Response::make($salida);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}

		public function getAleatoria() {
			$max = Cancion::count();
			$elementos = 50;
			$generados = array();
			$ids = "(";
			$i = 0;
			while(1){
				$aleatorio = mt_rand(1, $max);
				if(!in_array($aleatorio, $generados))
				{
					$generados[] = $aleatorio;
					$ids .= mt_rand(1, $max) . ",";
					$i++;
				}
				if($i == $elementos)
					break;
			};

			$ids = rtrim($ids,",") . ")";

			$canciones = Cancion::
				leftJoin("interprete", "interprete.id","=", "cancion.interprete_id")->
				leftJoin("album", "cancion.album_id", "=", "album.id")->
				leftJoin("genero", "cancion.genero_id", "=", "genero.id")->
				whereRaw ("cancion.id in " . $ids)->
				get(array("cancion.id", "cancion.nombre as titulo", "interprete.nombre as interprete", "cancion.id", "album.nombre as album"));

			$patron = "/[^\w\(\)\. áéíóúÁÉÍÓÚñÑüÜ&]/";
			$json = "[";

			foreach ($canciones as $key => $cancion) {
				$json .= '{"Id":"'.$cancion->id.'", "Titulo":"' . preg_replace($patron, "", $cancion->titulo) . '","Interprete":"' . preg_replace($patron, "", @$cancion->interprete) .'", "Album":"' . preg_replace($patron, "", @$cancion->album)  . '"},';
			}
			$json = rtrim($json, ",");
			$json .= "]";

			$respuesta = Response::make($json);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}
	}
?>
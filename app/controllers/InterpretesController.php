<?php
	class InterpretesController extends BaseController {
		public function getIndex() {
			$interpretes = Interprete::all();

			return View::make('interpretes/index', array("interpretes" => $interpretes));
		}

		public function getNuevo(){
			return View::make('interpretes/nuevo');
		}

		public function postNuevo() {
			$i = new Interprete();
			$i->nombre = Input::get("nombre");
			$i->save();
			return Redirect::to("interpretes/index");
		}

		public function getEditar($id) {
			$i = Interprete::find($id);

			return View::make('interpretes/nuevo', array("interprete" => $i));
		}

		public function postEditar($id) {
			$nombre = Input::get("nombre");
			
			$i = Interprete::findOrFail($id);
			$i->nombre = $nombre;
			$i->save();

			return Redirect::to("interpretes");
		}

		public function getLista(){
			return View::make('interpretes/lista');
		}

		public function getTodos($inicial){
			
			if($inicial != "xxxx"){
				$interpretes = Interprete::
					where("nombre", "like", $inicial . '%')->
					orderBy("nombre")->get();
			}
			else
			{
				$interpretes = Interprete::
					whereRaw ("nombre NOT REGEXP '^[[:alpha:]]'")->
					orderBy("nombre")->get();
			}
			$patron = "/[^\w\(\)\. &]/";

			$json = "[";
			foreach($interpretes as $i){
				$nombre = preg_replace($patron, "",  $i->nombre);
				if($nombre == "")
					$nombre = "Sin interprete";

				$json .= '{"Id":'.$i->id.', "Nombre":"'. $nombre .'"},';
			}

			$json = rtrim($json, ",");
			$json .= "]";

			$respuesta = Response::make($json);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}

		public function getCanciones($interprete_id)
		{
			$canciones = Cancion::
				where("interprete_id", "=", $interprete_id)->
				orderBy("nombre")->
				get();

			$patron = "/[^\w\(\)\. áéíóúÁÉÍÓÚñÑüÜ&]/";

			$json = "[";
			foreach($canciones as $i){

				$json .= '{"Id":'.$i->id.', "Titulo":"'. preg_replace($patron, "",  $i->nombre) .'"},';
				//$json .= '{"Id":'.$i->id.', "Titulo":"'. $i->titulo.'"},';
			}

			$json = rtrim($json, ",");
			$json .= "]";

			$respuesta = Response::make($json);
			$respuesta->header("Content-type","application/json");
			return $respuesta;
		}
	}
?>
<?php

class SesionController extends BaseController {
	public function getLogin(){
		return View::make("sesion/login");
	}

	public function postLogin(){
		$usuario = Input::get("usuario");
		$contrasena = Input::get("contrasena");

		if (Auth::attempt(array('username' => $usuario, 'password' => $contrasena)))
		{
			$usr = Usuario::find($usuario);
			if(!$usr){
				//El usuario no ha entrado a la aplicación, hay que crear su "perfil"
				$usr = new Usuario();
				$usr->id = $usuario;
				$usr->nombre = $usuario;
				$usr->save();

				$lista = new ListaReproduccion();
				$lista->usuario_id = $usuario;
				$lista->nombre = "Principal";
				$lista->save();
			}

		    return Redirect::action("ReproductorController@getIndex");
		}
		else
		{
		    return View::make("sesion/login", array("error" => "Nombre de usuario o contraseña incorrectos"));
		}
	}

	public function getLogout(){
		Auth::logout();
		return Redirect::action("ReproductorController@getIndex");
	}
}

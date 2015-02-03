var modulo = angular.module("musicaApp",["ngRoute"], function($interpolateProvider){
	$interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

modulo.config(["$routeProvider", function($routeProvider){
	$routeProvider
	.when("/", {
		templateUrl : "/canciones/explorar"
	})
	.when("/explorar",{
		templateUrl : "/canciones/explorar"
	})
	.when ("/busqueda", {
		templateUrl : "/canciones/resultados-busqueda"
	})
	.when("/subir", {
		templateUrl : "/canciones/subir"
	})
	.when("/login", {
		templateUrl : "/sesion/login"
	})
	.when("/ayuda", {
		templateUrl : "/reproductor/ayuda"
	})
	;
}]);

modulo.controller("MusicaController", ["$scope", "$http", function($scope, $http) {
	$scope.canciones = [];
	$scope.listaReproduccion = [];
	$scope.listas = [];
	$scope.ultimas = [];
	$scope.aleatorios = [];
	$scope.interpretes = [];
	$scope.cancionesInterprete = [];

	$scope.actual = 0;

	$scope.titulo = "Sin titulo";
	$scope.interprete = "Sin intérprete";

	var urlAjax = "";
	var audioPlayer = document.getElementById("audio");
	var errores = 0;
	var lista_actual = null;
	var reportarError = function(data,status,header,config){
		alert("Error [" + data.error.type + "]: " + data.error.message);
	}

	audioPlayer.onended = function(){
		console.log("ended");
		/*$scope.listaReproduccion[$scope.actual].reproduciendo = false;

		$scope.actual ++;
		if($scope.actual >= $scope.listaReproduccion.length){
			$scope.actual = 0;
		}

		var cancion = $scope.listaReproduccion[$scope.actual];
		$scope.reproducir(cancion);*/
		siguienteCancion();
	}

	audioPlayer.onabort = function(){
		console.log("aborted");
		$scope.listaReproduccion[$scope.actual].reproduciendo = false;
	}

	audioPlayer.onerror = function(){
		errores++;
		if(errores >= 10){
			alert("Han ocurrido muchos errores de reproducción, por favor verifique el funcionamiento de la aplicación");
			return;
		}
		var cancion = $scope.listaReproduccion[$scope.actual];
		mostrarNotificacion("Reproductor","Ocurrió un error al reproducir " + cancion.Titulo + ", reproduciendo la siguiente");
		siguienteCancion();
	}

	audioPlayer.onplay = function(){
	}

	siguienteCancion = function(){
		var cancion_actual = $scope.listaReproduccion[$scope.actual];
		cancion_actual.reproduciendo = false;

		$scope.actual ++;
		if($scope.actual >= $scope.listaReproduccion.length){
			$scope.actual = 0;
		}

		var cancion = $scope.listaReproduccion[$scope.actual];
		$scope.reproducir(cancion);
	}

	$scope.buscarCanciones = function(){
		if($scope.busqueda.length < 0)
			return;

		$http({
				url : "/canciones/busqueda/" + $scope.busqueda,
				method : "GET"/*,
				params : {
					term : $scope.busqueda
				}*/
			})
			.success(function(data){
				$scope.canciones = data;
				location.href="#/busqueda";
			})
			.error(function(data, status, headers, config){
				alert("error:" + data);
			});
	}

	$scope.agregarCancion = function (cancion){
		if(lista_actual == null){
			//Si no ha seleccionado una lista, no ir al servidor
			$scope.listaReproduccion.push(cancion);

			if($scope.listaReproduccion.length == 1)
			{
				//Si estamos agregando la primera canción, reproducirla
				$scope.reproducir(cancion);
			}

			return ;
		}

		$http.post("/listas/cancion", {
			"lista_id" : lista_actual.Id,
			"cancion_id" : cancion.Id,
			"orden" : lista_actual.length + 1
		})
		.success(function(data){
			if(data.Correcto){
				cancion.DetalleId = data.DetalleId;
				$scope.listaReproduccion.push(cancion);

				if($scope.listaReproduccion.length == 1)
				{
					//Si estamos agregando la primera canción, reproducirla
					$scope.reproducir(cancion);
				}
			}
			else
			{
				alert("Error al agregar la canción: " + data.Mensaje);
			}
		})
		.error(reportarError);
	}

	$scope.eliminarCancion = function(cancion) {

		$http.post("/listas/eliminar-cancion", {
			"detalle_id" : cancion.DetalleId
		})
		.success(function (data) {
			if(data.Correcto){
				if(cancion.reproduciendo) {
					audioPlayer.pause();
					audioPlayer.src = null;
					$scope.actual = 0;
					$scope.titulo = "";
					$scope.interprete = "";
				}

				eliminarCancionArreglo(cancion, $scope.listaReproduccion);
			}
			else
			{
				alert("Error al eliminar la canción: " + data.Mensaje);
			}
		})
		.error(reportarError);

	}

	getIndiceCancion = function(cancion, arreglo){
		for(var i=0;i<arreglo.length;i++){
			elem = arreglo[i];
			if(elem.Id == cancion.Id)
				return i;
		}

		return -1;
	}

	eliminarCancionArreglo = function(cancion, arreglo){
		var indice = getIndiceCancion(cancion, arreglo);
		if(indice >= 0){
			arreglo.splice(indice,1);
		}
	}

	$scope.reproducir = function(cancion){
		var id = cancion.Id;

		var $boton = $(this);

		$http({
			url : urlAjax,
			method : "POST",
			data : {
				"id" : id
			}
		})
		.success (function(data){
			if(data.correcto == true){
				var url = data.url;

				audioPlayer.pause();
				audioPlayer.src = url;
				audioPlayer.play();

				$scope.interprete = data.interprete;
				$scope.titulo = data.titulo;

				indice = getIndiceCancion(cancion, $scope.listaReproduccion);

				$scope.actual = indice;

				cancion.reproduciendo = true;

				mostrarNotificacion("Reproductor de música", "Reproduciendo " + data.interprete + " - " + data.titulo);
			}
		})
		;
	}

	cargarListas = function (){
		$http.get("/listas/listas")
		.success( function(data){
			$scope.listas = data;
			if(data.length > 0)
				$scope.abrirLista(data[0]);

			$scope.listas.push({"Id" : 0, "Nombre":"Aleatoria"});
		})
		.error(function(data,status,header,config){
			alert("Error cargando las listas de reproducción: " + data)
		});
	}

	$scope.guardarNuevaLista = function(){
		$http.post("/listas/nueva", {
			"nombre" : $scope.nombreNuevaLista
		})
		.success( function(data){
			if(data.Correcto == true){
				$scope.listas.push(data.lista);
				$scope.nombreNuevaLista = "";
			}
		})
		.error(function(data,status,header,config){
			alert("Error guardando la lista de reproducción: " + data)
		});
		$("#nueva-lista").slideUp();
	}

	$scope.abrirLista = function(lista){
		$http.get("/listas/canciones/" + lista.Id)
		.success( function(data){
			errores = 0;
			$scope.listaReproduccion = data;
			if(lista_actual != null)
				lista_actual.reproduciendo = false;

			lista.reproduciendo = true;
			lista_actual = lista;

			if($scope.listaReproduccion.length > 0)
				$scope.reproducir($scope.listaReproduccion[0]);
		})
		.error(function(data,status,header,config){
			alert("Error cargando las listas de reproducción: " + data)
		});
	}

	$scope.mostrarNuevaLista = function(){
		$("#nueva-lista").slideToggle();
		$("#nueva-lista input").focus();
	}

	$scope.cargarInterpretes = function(car){
		$http.get("/interpretes/todos/" + car)
		.success(function(data){
			$scope.interpretes = data;
			$scope.cancionesInterprete = [];
			$("#contenedor").scrollTop(0);
		})
		.error(reportarError);
	}

	$scope.cargarCancionesInterprete = function($i){
		$http.get("/interpretes/canciones/" + $i.Id)
		.success(function(data){
			$scope.cancionesInterprete = data;
			$("#contenedor").scrollTop(0);
		})
		.error(reportarError);
	}

	$scope.inicializar = function(opciones){
		urlAjax = opciones.urlAjax;
		cargarListas();
	}

	mostrarNotificacion = function(titulo, mensaje){
			// Let's check if the browser supports notifications
		if (!("Notification" in window)) {
			//No soporta las notificaciones
			return;
		}

			// Let's check if the user is okay to get some notification
		else if (Notification.permission === "granted") {
			// If it's okay let's create a notification
			var notification = new Notification(titulo, {
				body : mensaje,
				lang : "es-MX"
			});
		}

		// Otherwise, we need to ask the user for permission
		// Note, Chrome does not implement the permission static property
		// So we have to check for NOT 'denied' instead of 'default'
		else if (Notification.permission !== 'denied') {
			Notification.requestPermission(function (permission) {
				// If the user is okay, let's create a notification
				if (permission === "granted") {
					var notification = new Notification(titulo, {
						body : mensaje,
						lang : "es-MX"
					});
				}
			});
		}

		setTimeout(function(){
			if(notification != null && notification != undefined)
				notification.close();
		}, 3000);

		// At last, if the user already denied any notification, and you 
		// want to be respectful there is no need to bother them any more.
	}
}])
;

modulo.controller("ExaminarController", ["$scope", "$http", function($scope, $http){
	$scope.ruta = "";
	$scope.rutas = [];
	$scope.status = "/home/musica/Music";

	$scope.procesarCarpeta = function(){
		$scope.status = "Buscando archivos...";

		$http.get("/canciones/arbol")
		.success(function(rutas){
			$scope.rutas = rutas;

			$scope.status = "Subiendo archivos...";

			angular.forEach(rutas, function(ruta, key){
				ruta.Status = "Procesando...";

				$http.post("/canciones/procesar",{
					"archivo" : ruta.Archivo
				})
				.success(function(resultado){
					ruta.Status = resultado.Mensaje;
				})
				.error(function(data, status, headers, config){
					ruta.Status = "Error grave: " + data;
				});
			});

			$scope.status = "Se han procesado todos los archivos...";
		});
	}
}]);
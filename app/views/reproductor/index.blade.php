<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Reproductor</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	{{HTML::script("public/js/jquery.js")}}
	{{HTML::style("public/css/bootstrap.min.css")}}
	{{HTML::script("public/js/bootstrap.min.js")}}
	{{HTML::script("public/js/angular.js")}}
	{{HTML::script("public/js/angular-route.min.js")}}
	{{HTML::script("public/js/musica-controller.js")}}
	{{HTML::style("public/css/reproductor.css")}}
</head>
<body ng-app="musicaApp" ng-controller="MusicaController" ng-init='inicializar({"urlAjax" : "<?php echo action("CancionesController@postRuta") ?>" })'>

	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
		        </button>
		        <a class="navbar-brand" href="javascript:">Reproductor de música</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	        	<form class="navbar-form navbar-left" role="Search">
					<div class="input-group">
						<span class="input-group-addon glyphicon glyphicon-search"></span>
						<input type="text" id="busqueda" name="busqueda" class="form-control" 
							placeholder="Escriba nombre de la canción o intérprete a buscar y haga click en buscar"
							ng-model="busqueda">
					</div>

					<button type="submit" ng-click="buscarCanciones()" class="btn btn-default">Buscar</button>
				</form>
				<ul class="nav navbar-nav navbar-right">
				<?php if(Auth::check()) {?>
					<li><a href="sesion/logout">Cerrar sesión de {{Auth::id()}}</a></li>
				<?php } else { ?>
					<li><a href="#/login">Iniciar sesión</a></li>
				<?php } ?>
					<li><a class="" href="#/busqueda">Búsqueda</a></li>
					<li><a class="" href="#/explorar">Explorar</a></li>
					<li><a class="" href="#/subir">Subir canciones</a></li>
					<li><a class="" href="#/ayuda">Ayuda</a></li>
				</ul>
	        </div>
		</div>
	</nav>

	<div class="container-fluid" id="contenedor">
		<div id="contenedor-vistas" ng-view></div>
		<div id="div-audio-player" class="div-audio-player">
			<div class="panel panel-default">
				<div class="panel-heading">
					<a class="panel-title" href="javascript:" data-toggle="collapse" data-target="#lista-reproduccion">Lista de reproducción (<% listaReproduccion.length %> canciones)</a>
				</div>
				<div id="lista-reproduccion" class="panel-collapse collapse">
					<div class="panel-body">

					<?php if(Auth::check()) {?>
						<div class="col-md-4">
							<h3 class="h3-reproductor">Mis listas
								<a class="btn btn-default" title="Nueva lista de reproducción"
									ng-click="mostrarNuevaLista()">
									<span class="glyphicon glyphicon-plus"></span>
								</a>
							</h3>
							<div id="nueva-lista" class="oculto">
								<div class="row">
									<div class="col-md-9">
										<input id="nombre-lista" 
											ng-model="nombreNuevaLista"
											type="text" 
											maxlength="200"
											placeholder="Escriba el nombre de la nueva lista" 
											class="form-control" />
									</div>
									<div class="col-md-3">
										<button type="button" ng-click="guardarNuevaLista()" class="btn btn-success">
											<span class="glyphicon glyphicon-ok"></span>
										</button>
									</div>
								</div>
							</div>

							<ul class="listas-reproduccion">
								<li ng-repeat="l in listas"><a href='' class="reproduciendo-<% l.reproduciendo %>" ng-click='abrirLista(l)'><% l.Nombre %></a></li>
							</ul>
						</div>
					<?php } ?>
						<div class="col-md-8">
							<h3 class="h3-reproductor">Canciones en la lista</h3>
							<div ng-repeat="cancion in listaReproduccion track by $index" class="elemento-lista-reproduccion reproduciendo-<% cancion.reproduciendo %>">
								<a class="btn btn-default" href="" ng-click="reproducir(cancion)">
									<span class="glyphicon glyphicon-play"></span>
									<span class="sr-only">Reproducir</span>
								</a>
								<a class="btn btn-default" href="" ng-click="eliminarCancion(cancion)">
									<span class="glyphicon glyphicon-remove"></span>
									<span class="sr-only">Quitar de la lista</span>
								</a>
								<span><strong><% cancion.Titulo %></strong></span>
								<span><% cancion.Interprete %></span>
							</div>
							<div ng-if="listaReproduccion.length == 0" class="alert alert-warning">
								No se han agregado elementos a la lista de reproducción
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="datos-cancion">
				<span><% interprete %></span> - 
				<span><% titulo %></span>
			</div>
			<audio id="audio" controls class="audio-player" preload="metadata">

			</audio>
		</div>
	</div> <!-- contenedor -->

</body>
</html>

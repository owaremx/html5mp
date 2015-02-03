<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Servidor de Música</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	{{HTML::script("public/js/jquery.js")}}
	{{HTML::style("public/css/bootstrap.min.css")}}
	{{HTML::script("public/js/bootstrap.min.js")}}
	@yield('head')
</head>
<body ng-app="musicaApp">
	<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Cambiar navegación</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{action('ReproductorController@getIndex')}}">Reproductor de música</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="{{action('ReproductorController@getIndex')}}"> Reproductor </a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


	<div class="container-fluid">
		<div class="jumbotron">
			<h1>Servidor de música</h1>
		</div>
		@yield('contenido-principal')
		<footer>
			&copy; ORFIS
		</footer>
	</div>
</body>
</html>
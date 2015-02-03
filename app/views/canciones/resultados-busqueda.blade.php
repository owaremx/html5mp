<div id="contenedor-busqueda" role="tabpanel" class="tab-pane active">
	<h3>Resultados de la búsqueda</h3>
	<div id="contenedor-resultados-busqueda">
		<div ng-repeat="cancion in canciones" class="resultado-busqueda">
			<div class="clear">
				<img src="{{asset('public/img/musica.png')}}" alt="" class="img-circle img-thumbnail img-musica-busqueda" />
				<div>
					<span class="display-block"><strong><% cancion.Titulo %></strong></span>
					<span class="display-block"><% cancion.Interprete %></span>
					<span class="display-block"><% cancion.Album %></span>
				</div>
			</div>
			<div class="clear text-right">
				<a href="" ng-click="agregarCancion(cancion)" class="btn btn-default" title="Agregar a la lista de reproducción">
					<span class="glyphicon glyphicon-plus">
				</a>
			</div>
		</div>
		<div ng-if="canciones.length == 0">

			<div class="alert alert-warning">
				No se encontraron resultados
			</div>

			<div ng-repeat="cancion in aleatorio" class="resultado-busqueda">
				<img src="{{asset('public/img/musica.png')}}" alt="" class="img-circle img-thumbnail img-musica-busqueda" />
				<div>
					<span class="display-block"><strong><% cancion.Titulo %></strong></span>
					<span class="display-block"><% cancion.Interprete %></span>
					<span class="display-block"><% cancion.Album %></span>
				</div>
			</div>
		</div>
	</div>
</div><!-- contenedor-busqueda -->
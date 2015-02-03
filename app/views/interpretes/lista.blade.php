<div class="col-md-1">
	<ul class="ul-lista-interpretes">
		<li><a href="" ng-click="cargarInterpretes('xxxx')">Otros</a></li>
		<?php
			$abc = range('A', 'Z');
			foreach ($abc as $key => $value) {
				echo '<li><a href="" ng-click="cargarInterpretes(\''.$value.'\')">'.$value.'</a></li>';
			}
		?>
	</ul>
</div>
<div class="col-md-5" id="lista-interpretes">
	<ul class="ul-lista-interpretes">
		<li ng-repeat="i in interpretes"><a href='' ng-click="cargarCancionesInterprete(i)">
			<% i.Nombre %>
		</a></li>
	</ul>
</div>
<div class="col-md-6">
	<ul class="ul-canciones-interprete" id="canciones-interprete">
		<li ng-repeat="c in cancionesInterprete"><a href='' ng-click='agregarCancion(c)' class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></a> <% c.Titulo %></li>
	</ul>
</div>
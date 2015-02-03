@extends("plantilla-principal")
@section("head")
	{{HTML::script("public/js/canciones.js")}}
	<script type="text/javascript">
		$(document).ready(function(){
			canciones.inicializar({urlAjax : '<?php echo action("CancionesController@postRuta") ?>'});
		});
	</script>
@stop
@section("contenido-principal")
	<h2>Lista de canciones</h2>
	<a href='{{action("CancionesController@getNueva")}}'>Nueva canción</a>
	<div class="row" id="div-lista">
		<div class="col-md-8">
			<h3>Todas las canciones</h3>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Play</th>
						<th>Intérprete</th>
						<th>Título</th>
						<th>Disco</th>
						<th>Año</th>
					</tr>
				</thead>
				<tbody>
					<?php
						if(isset($canciones) && count($canciones)>0){
							for($i=0;$i<count($canciones);$i++){
					?>
						<tr>
							<td>
								<a href="javascript:" class="boton-play btn btn-success" data-id='{{ @$canciones[$i]->id }}'>
									<span class="glyphicon glyphicon-play"></span>
									<span class="visible-md-inline visible-lg-inline">Reproducir</span>
								</a>
							</td>
							<td>{{ @$canciones[$i]->interprete->nombre }}</td>
							<td>{{ $canciones[$i]->nombre }}</td>
							<td>{{ @$canciones[$i]->album->nombre }}</td>
							<td>{{ $canciones[$i]->anio }}</td>
						</tr>
					<?php
							}
						}
						else{
							echo "<tr><td colspan='4'>No se han registrado canciones</td></tr>";
						}
					?>
				</tbody>
			</table>
		</div>
		<div class="col-md-4">
			
		</div>
	</div>

	<div class="row">
		<span id="nombre-cancion">&nbsp;</span>
		<div id="botones-reproduccion" class="btn-group">
			<button type="button" class="btn btn-default">
				<span class="glyphicon glyphicon-backward"></span>
				
			</button>
			<button type="button" class="btn btn-default" id="btn-reproducir">
				<span class="glyphicon glyphicon-play"></span>
				
			</button>
			<button type="button" class="btn btn-default" id="btn-pausa">
				<span class="glyphicon glyphicon-pause"></span>
				
			</button>
			<button type="button" class="btn btn-default">
				<span class="glyphicon glyphicon-forward"></span>
				
			</button>
		</div>
		<audio id="audio" style="visible:false">
		</audio>
	</div>
@stop
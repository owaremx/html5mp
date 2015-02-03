@extends('plantilla-principal')

@section('contenido-principal')
	<h2>Intérpretes</h2>

<a href='{{action("InterpretesController@getNuevo")}}'>Nuevo</a>

	<table class="table table-hover table-striped">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php
				for($i=0; $i < count($interpretes); $i++) {
					$ruta = action("InterpretesController@getEditar", array($id=$interpretes[$i]->id));
					echo"<tr>
							<td>" . $interpretes[$i]->nombre . "</td>
							<td><a href='$ruta'>Modificar</a></td>
						</tr>";
				}
			?>
		</tbody>
	</table>

	<pre>
	<?php 
		require_once(app_path() . "/includes/getid3/getid3.php");	
		$getID3 = new getID3();

		$info = $getID3->analyze("/home/virtual/Music/lugar.mp3");

		getid3_lib::CopyTagsToComments($info);

		if(isset($info["comments"]))
			echo "SI";
		else
			echo "NO";
	?>
	</pre>
@stop
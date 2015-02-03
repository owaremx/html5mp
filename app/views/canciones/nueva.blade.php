@extends("plantilla-principal")
@section("contenido-principal")
	<h2>Nueva cancion</h2>
	{{Form::model(@$cancion, array('action'=>'CancionesController@getNueva', 'files' => true))}}
		<div class="row">
			{{Form::label("genero_id","Género:", array("class" => "col-md-2"))}}
			<div class="col-md-5">
				{{Form::select('genero_id', $generos, @$cancion->genero_id, array("class"=>"form-control"))}}
			</div>
		</div>
		<div class="row">
			{{Form::label("interprete_id","Intérprete:", array("class" => "col-md-2"))}}
			<div class="col-md-5">
				{{Form::select('interprete_id', $interpretes, @$cancion->interprete_id, array("class"=>"form-control"))}}
			</div>
		</div>
		<div class="row">
			{{Form::label("nombre","Título:", array("class" => "col-md-2"))}}
			<div class="col-md-5">
				{{Form::text("nombre", @$cancion->nombre, array("class" => "form-control"))}}
			</div>
		</div>
		<div class="row">
			{{Form::label("anio","Año:", array("class" => "col-md-2"))}}
			<div class="col-md-5">
				{{ Form::text("anio", @$cancion->anio, array("class" => "form-control")) }}
			</div>
		</div>
		<div class="row">
			{{ Form::label("archivo", "Archivo MP3:", array("class" => "col-md-2")) }}
			<div class="col-md-5">
				
				<input type="file" name="archivo[]" multiple="false" id="archivo" accept=".mp3" />
			</div>
		</div>
		<div clas="row">
			{{Form::button("<span class='glyphicon glyphicon-save'></span> Guardar", array("class"=>"btn btn-default col-md-offset-2", "type"=>"submit"))}}
		</div>
	{{Form::close()}}
@stop
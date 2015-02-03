@extends('plantilla-principal')

@section('contenido-principal')
	<?php
		if(isset($interprete)){
			//Significa que está modificando
			$form = Form::open(array('action' => array('InterpretesController@getEditar', $interprete->id)));
			$modificar = true;
		}
		else
		{
			$form = Form::open(array('action' => array('InterpretesController@getNuevo')));
			$modificar = false;
		}
	?>

	<h2>Nuevo intérprete</h2>

	{{ $form }}
		<div>
			{{Form::label("nombre","Nombre:", array("class"=>"col-md-2"))}}
			<div class="col-md-10">
				{{Form::text("nombre", @$interprete->nombre, array("class" => "form-control"))}}
			</div>
		</div>
		{{Form::button("Guardar", array('class'=>'btn btn-default', 'type'=>'submit'))}}
	{{ Form::close() }}
@stop
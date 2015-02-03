
	<h2>Entrar</h2>
	{{Form::open()}}
	<div class="alert alert-info">
		Inicie sesión con su nombre de usuario y contraseña de red
	</div>

	<?php 
		if(isset($error)){
	?>
		<div class="alert alert-danger">
			{{$error}}
		</div>
	<?php
		}
	?>

	<div class="row form-group">
		{{ Form::label("usuario", "Nombre de usuario", array("class"=>"col-md-2 label-control")) }}
		<div class="col-md-10">
		{{ Form::text("usuario", "", array("class"=>"form-control")) }}
		</div>
	</div>
	<div class="row form-group">
		{{ Form::label("contrasena", "Contraseña", array("class"=>"col-md-2 label-control")) }}
		<div class="col-md-10">
			<input type="password" name="contrasena" id="contrasena" class="form-control" />
		</div>
	</div>
	<div class="row form-group">
		<div class="col-md-2"></div>
		<div class="col-md-10">
			<button type="submit" class="btn btn-default">Entrar</button>
		</div>
	</div>
	{{Form::close()}}

@extends("plantilla-principal")
@section("head")
	{{HTML::script("public/js/angular.js")}}
	{{HTML::script("public/js/musica-controller.js")}}
@stop
@section("contenido-principal")
	<h2>Examinar carpeta</h2>
	<div ng-App="musicaApp" ng-controller="ExaminarController">
		<form>
			<div class="row">
				<label for="txtCarpeta" clas="col-md-2">Capeta:</label>
				<div class="col-md-9">
					<input type="text" class="form-control" id="txtCarpeta" name="txtCarpeta" value="~/Music" ng-model="ruta"/>
				</div>
			</div>
			<div class="row">
				<span class="col-md-2"></span>
				<div class="col-md-10">
					<button type="button" class="btn btn-default" ng-click="procesarCarpeta()">Examinar carpeta</button>
				</div>
			</div>
		</form>
		<div style="max-height:400px; overflow:auto">
			<strong>Estatus: <% status %></strong>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Archivo</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="ruta in rutas">
						<td><% ruta.Archivo %></td>
						<td><% ruta.Status %></td>
					</tr>
				</tbody>
			</table>
		</div>
		<ul>
			
		</ul>
	</div>
@stop

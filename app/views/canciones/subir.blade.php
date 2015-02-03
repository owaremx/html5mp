	<h2>Subir archivos</h2>
	<div class="alert alert-info">
		<strong>Recomendaciones para los archivos que va a subir</strong>
		<ul>
			<li>Se admiten archivos mp3, m4a y mp4</li>
			<li>Se recomienda revisar que los archivos vengan correctamente etiquetadas con su id3 
			(darle click derecho al archivo > propiedades > detalles y establecer los datos del intérprete y título de la canción)
			para que sean correctamente etiquetadas y sean fáciles de encontrar. Evite subir archivos que no han sido etiquetados correctamente
			(como los que extrae de un CD y quedan como track01 o pista01 sin haber sido etiquetados, 
			revise con el procedimiento que ya se mencionó) ya que no serán fáciles de encontrar.</li>
		</ul>
	</div>
	<div>
		<label for="fuCanciones">Seleccione el archivo</label>
		<div>
			<input type="file" multiple="true" id="fuCanciones" />
		</div>
	</div>

	{{HTML::style("public/js/async-upload/css/upload.css")}}
	{{HTML::script("public/js/async-upload/js/upload.js")}}
	<script type="text/javascript">
		$(document).ready(function(){
			$("#fuCanciones").fileUpload({
				url: '{{action("CancionesController@postMultiNueva")}}'
			});
		});
	</script>
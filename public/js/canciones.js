var canciones = {
	cancionActual : null,
	audioPlayer : null,

	btnPlay_click : function(evt){
		var id = $(this).attr("data-id");

		var $boton = $(this);

		$.ajax({
			type : "POST",
			dataType : "json",
			url : canciones.urlAjax,
			data : {
				"id" : id
			},
			success : function(data){
				if(data.correcto == true){
					var url = data.url;

					canciones.audioPlayer.pause();
					canciones.audioPlayer.src = url;
					canciones.audioPlayer.play();

					$("#nombre-cancion").text(data.titulo);
				}
			}
		});		
	},

	btnPausa_click:function(evt){
		canciones.audioPlayer.pause();
	},

	btnReproducir_click:function(evt){
		canciones.audioPlayer.play();
	},

	btnAgregar_click:function(evt){
		var scope = angular.element(document.getElementById("div-lista")).scope();
		scope.$apply(function(){
			scope.agregarCancion({"id":3,"Interprete":"Interprete Prueba","Titulo":"Título de prueba"});
		});
	},
	inicializar:function(opciones){
		canciones.urlAjax = opciones.urlAjax;
		canciones.audioPlayer = document.getElementById("audio");

		$(".boton-play").on("click", canciones.btnPlay_click);
		//$(".boton-agregar").on("click", canciones.btnAgregar_click);

		$("#btn-pausa").on("click", canciones.btnPausa_click);
		$("#btn-reproducir").on("click", canciones.btnReproducir_click);

		canciones.audioPlayer.addEventListener("error", function(evt){
			$("#nombre-cancion").text($("#nombre-cancion").text() + "[Error]");
		});

		console.log("iniciado");
	}
};

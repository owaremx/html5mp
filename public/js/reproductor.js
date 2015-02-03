var reproductor = {
	cancionActual : null,
	audioPlayer : null,

	btnPlay_click : function(evt){
		var id = $(this).attr("data-id");

		var $boton = $(this);

		$.ajax({
			type : "POST",
			dataType : "json",
			url : reproductor.urlAjax,
			data : {
				"id" : id
			},
			success : function(data){
				if(data.correcto == true){
					var url = data.url;

					reproductor.audioPlayer.pause();
					reproductor.audioPlayer.src = url;
					reproductor.audioPlayer.play();

					$("#nombre-cancion").text(data.titulo);
				}
			}
		});		
	},

	btnPausa_click:function(evt){
		reproductor.audioPlayer.pause();
	},

	btnReproducir_click:function(evt){
		reproductor.audioPlayer.play();
	},

	inicializar:function(opciones){
		reproductor.urlAjax = opciones.urlAjax;
		reproductor.audioPlayer = document.getElementById("audio");

		$(".boton-play").on("click", reproductor.btnPlay_click);
		$(".boton-agregar").on("click", reproductor.btnAgregar_click);

		$("#btn-pausa").on("click", reproductor.btnPausa_click);
		$("#btn-reproducir").on("click", reproductor.btnReproducir_click);

		reproductor.audioPlayer.addEventListener("error", function(evt){
			$("#nombre-cancion").text($("#nombre-cancion").text() + "[Error]");
		});
		
		console.log("iniciado");
	}
};

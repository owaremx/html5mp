(function ($) {
    $.fn.fileUpload = function (opciones) {
        return this.each(function () {
            var input = this;

            var solicitudes = [];
            var conteo = 0;

            var defaults = $.extend({
                url: "Default.aspx",
                param_destino : "archivo_subir",
            }, opciones);

            var subir = function (archivo, div, params) {
                var formData = new FormData();
                formData.append(params.param_destino, archivo);

                $.ajax({
                    url: params.url,  //Pagina que recibe el archivo
                    type: 'POST',
                    xhr: function () {
                        var myXhr = $.ajaxSettings.xhr();
                        solicitudes[conteo] = { "xhr": myXhr };

                        if (myXhr.upload) { // Verificar si existe la propiedad upload
                            myXhr.upload.id = div.find(".fu-btn-cancelar").attr("data-id");
                            myXhr.upload.addEventListener('progress', function (evt) {
                                if (evt.lengthComputable) {
                                    var porcentaje = Math.round((evt.loaded * 100) / evt.total);

                                    div.find('.fu-progreso').text(porcentaje + " % Subido");
                                }
                            }, false); // Manejador del evento de cambio de progreso
                        }

                        return myXhr;
                    },
                    success: function (data) {
                        if (data.Correcto) {
                            div.find(".fu-progreso").text("Archivo subido correctamente");
                        }
                        else
                            div.find(".fu-progreso").text("Error al subir el archivo: " + data.Mensaje);

                        div.find(".fu-btn-cancelar").remove();
                    },
                    error: function (err, status, mensaje) {
                        div.find(".fu-progreso").text("Error al enviar el archivo: " + mensaje);
                    },
                    // Form data
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }

            var btnExaminar = $("<button type='button' class='fu-btn-examinar btn btn-info'>Seleccionar archivos</button>").appendTo($(this).parent());

            $(this).on("change", function (evt) {
                for (var i = 0; i < this.files.length; i++) {
                    var div = $("<div class='fu-contenedor-datos'></div>");
                    var lblNombre = $("<span class='fu-nombre'>Nombre</span>").appendTo(div);
                    var lblTamano = $("<span class='fu-tamano'>Tamaño</span>").appendTo(div);
                    //var lblTipo = $("<span class='fu-tipo'>Tipo</span>").appendTo(div);
                    var lblProgreso = $("<span class='fu-progreso'></span>").appendTo(div);
                    var btnCancelar = $("<button type='button' class='fu-btn-cancelar btn btn-danger'>Cancelar</button>").appendTo(div);

                    //Obtener los detalles del archivo
                    var archivo = this.files[i];

                    div.appendTo($(this).parent());

                    lblNombre.text(archivo.name);
                    lblTamano.text(Math.round(archivo.size / 1024) + " KB");
                    //lblTipo.text(archivo.type);
                    lblProgreso.text("Iniciando...");

                    btnCancelar.attr("data-id", conteo);

                    btnCancelar.on("click", function () {
                        var xhr = solicitudes[$(this).attr("data-id")].xhr;
                        xhr.abort();
                        $(this).parent().remove();
                    });

                    subir(archivo, div, defaults);

                    conteo++;
                    div.appendTo($(this).parent());
                }

                $(this).val(null);
            });

            //Ocultar el input
            $(this).css("display", "none");

            //Agregar el boton de examinar
            btnExaminar.appendTo($(this).parent());
            btnExaminar.on("click", function () { $(input).click(); });
        });
    }
})(jQuery);

function foto_leer_comentarios(idfoto, page) {
	comentarios = ajax_post({
		data : "foto_leer_comentarios=1&idfoto=" + idfoto + "&page="+page, //TODO
		retrieve : true,
		visible : false
	});
	$("#comentarios").html(comentarios);
	//alert(JSON.stringify(comentarios));
}

function enviar_comentario() {
	comentario = $("[name='foto_comentario']").val();
	if (comentario) {
		ajax_post({
			data : "foto_comentario=" + comentario + "&idfotos=" + idfoto,
			reload : true,
		});
	}
}

function foto_principal() {
	ajax_post({
		data : "foto_principal=" + idfoto
	});
}

//Una vez se cargan todos los archivos
$(window).load(function() {
	//Redimensiona el ancho y alto del div del fondo para centrar la imagen
	$("#foto_marco_medio").width($("#foto").width());
	$(".barra_izq_centro").eq(0).height($("#foto").height() + 155 + "px");
	$(".barra_izq_centro").find("div.marco").eq(0).height($("#foto").height() + 90 + "px");

});
$(document).ready(function() {

	//Teclas navegacion rapida
	$("body").keypress(function(event) {
		//Evitamos problemas con la edicion de comentarios
		if (document.activeElement != "[object HTMLBodyElement]") {
			return;
		}
		if (event.keyCode == 40) {
			//event.preventDefault();
			//location.href = tecla_primera;
		} else if (event.keyCode == 37) {
			event.preventDefault();
			location.href = tecla_anterior;
		} else if (event.keyCode == 39) {
			event.preventDefault();
			location.href = tecla_siguiente;
		} else if (event.keyCode == 38) {
			//event.preventDefault();
			//location.href = tecla_ultima;
		}
	});

	$("#lista_etiquetados li,#foto_marco div.etiquetado").hover(function() {
		etiqueta_id = $(this).attr("class").match(/etiqueta_[0-9]{1,}/gim)[0];
		$("#lista_etiquetados li." + etiqueta_id).css("font-weight", "bold");

		width = $("#foto_marco div." + etiqueta_id + " div.etiqueta_nombre").innerWidth() / 2 - 15;
		$("#foto_marco div." + etiqueta_id + " div.etiqueta_nombre").css({
			"visibility" : "visible",
			"right" : width
		});
	}, function() {
		etiqueta_id = $(this).attr("class").match(/etiqueta_[0-9]{1,}/gim)[0];
		$("#lista_etiquetados li." + etiqueta_id).css("font-weight", "normal");

		$("#foto_marco div." + etiqueta_id + " div.etiqueta_nombre").css({
			"visibility" : "hidden"
		});
	});
});
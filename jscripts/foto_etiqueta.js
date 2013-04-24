//Definimos variables globales
var click;
var move;
var x_centrado;
var y_centrado;
var etiqueta_editar;
//var idfotos;

function foto_cancelar_edicion() {
	location.reload();
}

function etiqueta_borrar(label, value, icon) {
	if (etiqueta_editar != 1)
		return false;
	//lo borramos del panel etiquetado
	$("#lista_etiquetados").find("li").each(function() {
		if ($(this).text().search(label)!=-1) {
			$(this).remove();
		}
	});

	lista_amigos.push({
		value : value,
		label : label,
		icon: icon
	});

	// buscamos el nombre del amigo seleccionado
	for ( i = 0; i < lista_etiquetados.length; i++) {
		if (lista_etiquetados[i].label == label) {
			lista_etiquetados.splice(i, 1);
			// y lo quitamos del array
			break;
		}
	}
	$("#etiqueta_" + value).remove();

}

function fotos_post() {
	string_envio = "";
	for ( i = 0; i < lista_etiquetados.length; i++) {
		string_envio += lista_etiquetados[i].value + ",";
		string_envio += lista_etiquetados[i].x + ",";
		string_envio += lista_etiquetados[i].y + ",";
	}
	string_envio = string_envio.substring(0, string_envio.length - 1);

	titulo = $("input[name='foto_titulo']").val();
	album = $("select[name='foto_album']").val();

	//Si hay cambio de album a la siguiente foto
	if (idalbum != album && idalbum != "0" && album != "NULL") {
		if (tecla_siguiente) {
			nextUrl = "fotos.php" + tecla_siguiente;
			reload = false;
		} else {
			nextUrl = "album.php?iduser=" + iduser + "&idalbum=" + idalbum;
			reload = false;
		}
	} else {
		nextUrl = false;
		reload = true;
	}
	//ajax_post("foto_edicion=1&idfotos=" + idfoto + "&etiquetas=" + string_envio + "&titulo=" + titulo + "&idalbum=" + album, recarga, nextUrl);
	ajax_post({
		data : "foto_edicion=1&idfotos=" + idfoto + "&etiquetas=" + string_envio + "&titulo=" + titulo + "&idalbum=" + album,
		reload : reload,
		nextUrl : nextUrl
	});
}

function etiqueta_editar() {
	//Ocultamos cuadros originales y mostramos inputs para edicion
	$(".barra_der,#foto_titulo").find(".edicion").css("display", "inline-block");
	$(".barra_der,#foto_titulo").find(".original").css("display", "none");
	$("#lista_etiquetados li div").css("display", "inline-block");
	etiqueta_editar = 1;
	$("form button").show();
	//TODO: cambiar visual lista etuiquetados
	move = 1;
	onclick = $("#foto").attr("onclick");
	//desactivamos click
	$("#foto").removeAttr("onclick");

	$("#foto_marco").mouseenter(function(e) {
		etiqueta_crear();
		$("#foto_marco").mousemove(function(e) {
			if (move == 1) {
				fl = this.offsetLeft;
				fr = this.offsetLeft + parseInt($(this).css("width").match(/[0-9]{1,}/gi)[0]);
				ft = this.offsetTop;
				fb = this.offsetTop + parseInt($(this).css("height").match(/[0-9]{1,}/gi)[0]);

				//raton fuera de la foto
				if (e.pageX < fl || e.pageX > fr || e.pageY < ft || e.pageY > fb) {
					$("#etiqueta").hide();
				}
				//alert(e.pageX);
				var p = $("#foto").position();
				//$("p:last").text( "left: " + position.left + ", top: " + position.top );

				//Coordenadas de la etuiqueta
				x = e.pageX - this.offsetLeft;
				y = e.pageY - this.offsetTop;

				//Coordenadas de la etuiqueta centrada al raton
				h = $("#etiqueta").css("height").match(/[0-9]{1,}/gi)[0] / 2;
				w = $("#etiqueta").css("width").match(/[0-9]{1,}/gi)[0] / 2;
				y_centrado = y + p.top - h + "px";
				x_centrado = x + p.left - w + "px";
				$("#etiqueta").css({
					"top" : y_centrado,
					"left" : x_centrado
				});
			}
		});

		$("#etiqueta").click(function(e) {
			move = 0;
			click = 1;
			$(".ui-widget").find("*").show();
			$(".ui-autocomplete-input").focus();
			$( ".ui-autocomplete-input" ).autocomplete("search");

		});

		$("#foto").click(function(e) {//si click=1 y pincha fuera de la etiqueta desparaliza etiqueta
			if (move == 0 && click == 1) {
				move = 1;
				click = 0;
				$("form input,label").show();
				//$("form #tags").focus();
				//$( "form #tags" ).autocomplete("search");
			}
		});

	});

	//CANCELAR EDICION
	$(window).keypress(function(event) {
		//alert(event.keyCode);
		//Evitamos problemas con la edicion de comentarios
		/*if (document.activeElement != "[object HTMLBodyElement]") {
		 return;
		 }*/
		//TODO
		/*if (event.keyCode == 27) {
			location.reload(true);
			alert(location.href);
			//foto_cancelar_edicion();
		}*/
	});

}

function etiqueta_fijar(id, name) {
	$("#foto_marco").append("<div class='etiquetado etiqueta_" + id + "' id='etiqueta_" + id + "' style='left:" + x_centrado + ";top:" + y_centrado + ";'><div class='etiqueta_nombre'>" + name + "</div></div>");
	$("#tags").hide();
	$("body").focus();
	move = 1;
	click = 0;
}

function etiqueta_crear() {
	if ($("#etiqueta").length < 1) {
		$("#foto_marco").append("<div id='etiqueta'></div>");
	} else if (click != 1) {
		move = 1;
		$("#etiqueta").show();
	}
}


$(document).ready(function() {
	$( "#tags" ).click(function(e){
			$( "#tags" ).autocomplete("search");
			$("#ui-id-1").show();
	});
});

//elimina gente etiquetada del la lista_amigos
function amigos_actualizar() {
	$("#lista_etiquetados li div").each(function() {
		//alert($(this).attr('onclick').match(/'[0-9]{1,}'/gim)[0]);
		id = $(this).attr('onclick').match(/'[0-9]{1,}'/gim)[0].match(/[0-9]{1,}/gim)[0];
		//alert(id);
		// buscamos el nombre del amigo seleccionado
		for ( i = 0; i < lista_amigos.length; i++) {
			if (lista_amigos[i].value == id) {
				lista_amigos.splice(i, 1);
				// y lo quitamos del array
				break;
			}
		}
	});
}

$(function() {
	$("#tags").autocomplete({
		minLength : 0,
		source : lista_amigos,
		create : function(event, ui) {
			amigos_actualizar();
			//elimina gente etiquetada del select
			return false;
		},
		focus : function(event, ui) {
			//al focus sobre un resultado
			return false;
		},
		select : function(event, ui) {
			//AL PULSAR UN RESULTADO
			//lo añadimos a los etiquetados
			lista_etiquetados.push({
				value : ui.item.value,
				label : ui.item.label,
				icon : ui.item.icon,
				x : x_centrado,
				y : y_centrado
			});
			$("#lista_etiquetados").append(
				"<li class='etiqueta_" + ui.item.value + "'>"+
				"<img src='"+ui.item.icon+"' class='autocomplete_img'>" + ui.item.label + "<div onclick=\"etiqueta_borrar('" + ui.item.label + "','" + ui.item.value + "')\" style='display:inline-block;'></div></li>");
			$("#tags").val("");

			// buscamos el nombre del amigo seleccionado
			for ( i = 0; i < lista_amigos.length; i++) {
				if (lista_amigos[i].label == ui.item.label) {
					lista_amigos.splice(i, 1);
					// y lo quitamos del array
					break;
				}
			}

			//efectos de raton y divs
			etiqueta_fijar(ui.item.value, ui.item.label);
			$(".ui-widget").find("*").hide();
			return false;
		}
	}) .data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" ).append( 
			"<a>"+
				"<img src='"+ item.icon + "' class='autocomplete_img'>"+
				"<div class='autocomplete_label'>" + item.label + "</div>"+
			"</a>" ).appendTo( ul );
	};
	
	
	/*.data("autocomplete")._renderItem = function(ul, item) {
		return $("<li>").data("item.autocomplete", item).append("<a>" + item.label + "</a>").appendTo(ul);
	};*/
});

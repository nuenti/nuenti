chat_mode_home = false;

$(window).ready(function() {
	if (chat_estado == 1) {
		chat_leer();
		timeOutChatLeer = window.setInterval(chat_leer, 5000);
		chat_contactos();
		timeOutChatContactos = window.setInterval(chat_contactos, 10000);
	}
});

$("#mensajes").find(".texto").live({
	mouseenter: function() {
		$(this).find(".fecha").css("visibility","visible");
	},
	mouseleave: function() {
		$(this).find(".fecha").css("visibility","hidden");
	}
});

function chat_turn(modo) {
	if (modo == "1") {
		ajax_post({
			data : "chat_estado=1",
			visible : false
		});
		$("#chat_estado").html("<p style='cursor:pointer;' onclick=\"chat_turn('0')\">Desactivar Chat</p>");

		chat_leer();
		timeOutChatLeer = window.setInterval(chat_leer, 5000);
		chat_contactos();
		timeOutChatContactos = window.setInterval(chat_contactos, 10000);

		chat_estado = 1;
	} else {
		//Cerramos conversaciones activas
		$(".chat_ventana,.chat_ventana_min").remove();
		//Desactivamos el chat
		ajax_post({
			data : "chat_estado=0",
			visible : false
		});
		$("#chat_contactos").html("");
		$("#chat_estado").html("<p style='cursor:pointer;' onclick='chat_turn(\"1\")'>Activar Chat</p>");

		timeOutChatLeer = window.clearInterval(timeOutChatLeer);
		timeOutChatContactos = window.clearInterval(timeOutChatContactos);
		chat_estado = 0;
	}
}

function chat_contactos() {
	$.ajax({
		type : "POST",
		url : "post.php",
		data : {
			chat_contactos : "1"
		}
	}).done(function(msg) {
		$("#chat_contactos").html(msg);
	});
}

function chat_toggle() {
	if (chat_mode_home != true) {
		if ($("#chat_padre_contactos").css("display") == "none") {
			$("#chat_padre_contactos").css({
				"display" : "inline-block"
			});
		} else {
			$("#chat_padre_contactos").hide();
		}
	}
}

function chat_conv_init(iduser, nombre, img) {
	//Minimizamos conversaciones abiertas
	$(".chat_ventana").each(function() {
		user = $(this).attr("iduser");
		chat_conv_mini(user);
	});
	if ($("#chat_conv_" + iduser).length < 1) {
		//$("#chat_anclaje").append("<div id='chat_conv_" + iduser + "_min' class='chat_ventana_min' onclick=\"chat_conv_show('" + iduser + "')\">" + "<img src='" + img + "' alt='" + nombre + "'/>" + "<div class='mensajes'></div>" + "</div>" + "<div id='chat_conv_" + iduser + "' class='chat_ventana' iduser='" + iduser + "'>" + nombre + "<div class='boton cerr' onclick=\"chat_conv_cerrar('" + iduser + "')\"></div>" + "<div class='boton max' onclick=\"chat_conv_max('" + iduser + "')\"></div>" + "<div class='boton mini' onclick=\"chat_conv_mini('" + iduser + "')\"></div>" + "<div id='mensajes'></div>" + "<textarea name='mensaje' onkeypress=\"chat_press_enter(event,this,'" + iduser + "')\" /></textarea>" + "<button type='button' onclick=\"chat_enviar('" + iduser + "')\">Enviar</button>" + "</div>");
		chat_ventana = "<div id='chat_conv_" + iduser + "_min' class='chat_ventana_min' onclick=\"chat_conv_show('" + iduser + "')\">" +
							"<img src='" + img + "' alt='" + nombre + "'/>" + 
							"<div class='mensajes'></div>" + 
						"</div>" + 
						"<div id='chat_conv_" + iduser + "' class='chat_ventana' iduser='" + iduser + "'>" + nombre + 
							"<div class='boton cerr' onclick=\"chat_conv_cerrar('" + iduser + "')\"></div>" + 
							"<div class='boton max' onclick=\"chat_conv_max('" + iduser + "')\"></div>" + 
							"<div class='boton mini' onclick=\"chat_conv_mini('" + iduser + "')\"></div>" + 
							"<div id='mensajes'><div style='text-align:center;'><button onclick=\"chat_leer_prev('" + iduser + "')\">Ver anteriores</button></div></div>" + 
							"<textarea name='mensaje' onkeypress=\"chat_press_enter(event,this,'" + iduser + "')\" /></textarea>" + 
							"<button type='button' onclick=\"chat_enviar('" + iduser + "')\">Enviar</button>" + 
						"</div>";
		
		$("#chat_anclaje").append(chat_ventana);
		$("#chat_conv_" + iduser).find("textarea").focus();
	} else {
		chat_conv_show(iduser);
		$("#chat_conv_" + iduser).find("textarea").focus();
	}
	chat_toggle();
}

function chat_conv_show(emisor) {
	//Minimizamos conversaciones abiertas
	$(".chat_ventana").each(function() {
		user = $(this).attr("iduser");
		chat_conv_mini(user);
	});
	$("#chat_conv_" + emisor).css({
		"display" : "inline-block"
	});
	$("#chat_conv_" + emisor).show();
	$("#chat_conv_" + emisor + "_min").hide();

	$("#chat_conv_" + emisor).find("textarea").focus();
}

function chat_opciones() {
	if ($("#chat_opciones_lista").css("display") == "none") {
		$("#chat_opciones_lista").css({
			"display" : "inline-block"
		});
	} else {
		$("#chat_opciones_lista").hide();
	}
}

function chat_enviar(iduser) {
	var fecha = new Date()
	var hora = fecha.getHours()
	var minuto = fecha.getMinutes()

	if (hora < 10) {hora = "0" + hora}
	if (minuto < 10) {minuto = "0" + minuto}

	fecha = hora + ":" + minuto;

	mensaje = $("#chat_conv_" + iduser).find("textarea").val();
	if (mensaje.length == 0)	return false;
	mensaje_f = "<div class='mensaje_propio'><div class='texto'>" + mensaje + "<div class='fecha'>" + fecha + "</div></div></div>";
	$("#chat_conv_" + iduser).find("textarea").val("");
	
	idchat = ajax_post({
		data : "chat_enviar=1&receptor="+iduser+"&mensaje="+mensaje,
		visible : false,
		retrieve : true
	});
	
	mensaje_f = "<div idchat='"+ idchat +"' class='mensaje_propio'><div class='texto'>" + mensaje + "<div class='fecha'>" + fecha + "</div></div></div>";
	$("#chat_conv_" + iduser).find("#mensajes").append(mensaje_f);
}

function chat_press_enter(e, t, iduser) {
	tecla = (document.all) ? e.keyCode : e.which;
	//alert(tecla);
	if (tecla == 13) {
		e.preventDefault();
		chat_enviar(iduser);
		$("#chat_conv_" + iduser).find("textarea").val("");
	}
}

function chat_leer() {
	$.ajax({
		type : "POST",
		cache : false,
		url : "post.php",
		data : {
			chat_leer : "1"
		}
	}).done(function(msg) {
		$("#chat_conv_tmp").html(msg);

		$("#chat_conv_tmp").find("div").each(function() {
			idchat = $(this).attr("idchat");
			emisor = $(this).attr("iduser");
			nombre = $(this).attr("nombre");
			fecha = $(this).attr("fecha");
			img = $(this).attr("img");
			
			mensaje = $(this).html();
			mensaje_f = "<div idchat='"+ idchat +"' class='mensaje_ajeno'><div class='texto'>" + $(this).html() + "<div class='fecha'>" + fecha + "</div></div></div>";
			
			
			
			if ($("#chat_conv_" + emisor).css("display") == "none") {
				//minimizada
				$("#chat_conv_" + emisor + "_min").find(".mensajes").css("display","inline-block");
				$("#chat_conv_" + emisor).find("#mensajes").append(mensaje_f);
			} else if ($("#chat_conv_" + emisor).length > 0) {
				//maximizada
				$("#chat_conv_" + emisor).find("#mensajes").append(mensaje_f);
			} else {
				//sin iniciar
				chat_conv_init(emisor, nombre, img);
				chat_toggle();
				//minimizar lista contactos
				$("#chat_conv_" + emisor).find("#mensajes").append(mensaje_f);
				//minimizar conversa
				chat_conv_mini(emisor);
				//mostrar simbolo mensajes
				$("#chat_conv_" + emisor + "_min").find(".mensajes").css("display","inline-block");
			}
			
			// Deslizando verticalmente la conversacion
			//$("#chat_conv_"+emisor).find("#mensajes").scrollTop($("#chat_conv_"+emisor).find("#mensajes").prop("scrollHeight"))
			$("#chat_conv_" + emisor).find("#mensajes").animate({
				scrollTop : $("#chat_conv_" + emisor).find("#mensajes").prop("scrollHeight")
			}, 3000);
		});
	});
}

function chat_leer_prev(iduser) {
	msg_antiguo = $("#chat_conv_"+iduser).find("div[class^='mensaje']:first");
	idchat = $(msg_antiguo).attr("idchat");
	$.ajax({
		type : "POST",
		cache : false,
		url : "post.php",
		data : {
			chat_leer_prev : "1",
			idchat : idchat,
			iduser : iduser
		}
	}).done(function(msg) {
		if(msg==""){
			//TODO: Advertir que no hay mas msgs
		}else{
			$("#chat_conv_" + iduser).find("#mensajes").find("div:first").after(msg);
		}
	});
}

function chat_conv_cerrar(emisor) {
	$("#chat_conv_" + emisor).remove();
}

function chat_conv_mini(emisor) {
	$("#chat_conv_" + emisor).hide();
	$("#chat_conv_" + emisor + "_min").css({
		"display" : "inline-block"
	});
	$("#chat_conv_" + emisor + "_min").find(".mensajes").hide();
}

function chat_conv_max(emisor) {
	if($("#chat_conv_" + emisor).css("width")=="300px"){
		$("#chat_conv_" + emisor).css({
			"width" : "400px",
			"height" : "500px"
		});
		
		$("#chat_conv_" + emisor).find("#mensajes").css({
			"width" : "400px",
			"height" : "400px"
		});
		
		$("#chat_conv_" + emisor).find("textarea").css({
			"width" : "321px"
		});
	}else{
		$("#chat_conv_" + emisor).css({
			"width" : "300px",
			"height" : "300px"
		});
		
		$("#chat_conv_" + emisor).find("#mensajes").css({
			"width" : "298px",
			"height" : "200px"
		});
		
		$("#chat_conv_" + emisor).find("textarea").css({
			"width" : "225px"
		});
	}	
}
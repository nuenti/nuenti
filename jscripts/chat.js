chat_mode_home = false;

$(window).ready(function() {
	if (chat_estado == 1) {
		chat_leer();
		timeOutChatLeer = window.setInterval(chat_leer, 5000);
		chat_contactos();
		timeOutChatContactos = window.setInterval(chat_contactos, 10000);
		
		// Auto iniciar conversaciones
		if(typeof(Storage)!=="undefined"){
			if (sessionStorage["open_convs"] ) {
				var open_convs = JSON.parse(sessionStorage["open_convs"]);
				for(i=0;i<open_convs.length;i++){
					chat_conv_init(open_convs[i].iduser, open_convs[i].nombre, open_convs[i].img, "onload");
					chat_leer_prev(open_convs[i].iduser);
					last_iduser = open_convs[i].iduser;
				}
				// Icono conversacion activa
				$("#chat_conv_" + last_iduser+"_min").addClass("activa");
			}
		}
	}
});

$("#mensajes").find(".texto").live({
	mouseenter: function() {
		$(this).find(".fecha").toggle();
	},
	mouseleave: function() {
		$(this).find(".fecha").toggle();
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
		$("#chat_padre_contactos").toggle();
		bottom = $("#chat_padre_contactos").height();
		$("#chat_padre_contactos").css("bottom",bottom+30);
	}
	if($("#chat_padre_contactos").is(":visible")){
		$(".chat_ventana").each(function() {
			user = $(this).attr("iduser");
			chat_conv_mini(user);
		});
	}
}

function chat_conv_init(iduser, nombre, img, modo) {
	//Minimizamos conversaciones abiertas
	$(".chat_ventana").each(function() {
		user = $(this).attr("iduser");
		chat_conv_mini(user);
	});
	
	//Creamos una conversacion nueva
	if ($("#chat_conv_" + iduser).length < 1) {
		chat_ventana = "<div id='chat_conv_" + iduser + "_min' class='chat_ventana_min activa' onclick=\"chat_conv_show('" + iduser + "')\">" +
							"<img src='" + img + "' alt='" + nombre + "'/>" + 
							"<div class='mensajes'></div>" + 
						"</div>" + 
						"<div id='chat_conv_" + iduser + "' class='chat_ventana' iduser='" + iduser + "'>" + nombre + 
							"<div class='boton cerr' onclick=\"chat_conv_cerrar('" + iduser + "')\"></div>" + 
							"<div class='boton max' onclick=\"chat_conv_resize('" + iduser + "')\"></div>" + 
							"<div class='boton mini' onclick=\"chat_conv_mini('" + iduser + "')\"></div>" + 
							"<div id='mensajes'><div style='text-align:center;'><button type='button' class='azul' onclick=\"chat_leer_prev('" + iduser + "')\"><span><b>Ver anteriores</b></span></button></div></div>"+
							"<div class='input'>"+
								"<span>"+
									"<input name='mensaje' type='text' onkeypress=\"chat_press_enter(event,this,'" + iduser + "')\" placeholder='Escribe tu mensaje' autofocus>"+
								"</span>"+
							"</div>"+
						"</div>";
		
		$("#chat_anclaje").append(chat_ventana);
		$("#chat_conv_" + iduser).find("input").focus();
	//Mostramos una conversacion existente
	} else {
		chat_conv_show(iduser);
	}
	
	if(modo == "normal"){
		chat_toggle();
		
		// Añadimos la conversacion a las existentes
		if(typeof(Storage)!=="undefined"){
			if (!sessionStorage["open_convs"] ) {
				sessionStorage["open_convs"] = JSON.stringify([]);
			}
			var open_convs = JSON.parse(sessionStorage["open_convs"]);
			
			new_conv = {
				iduser:iduser,
				nombre:nombre,
				img:img
			};
			open_convs.push(new_conv);
			
			//Evitamos valores duplicados
			open_convs_limpia = new Array();
			for(i=0;i<open_convs.length;i++){
						repetido = false;
						for(i2=0;i2<open_convs_limpia.length;i2++){
							if(open_convs[i].iduser == open_convs_limpia[i2].iduser)
								repetido = true;
					}
					if(repetido == false)
						open_convs_limpia.push(open_convs[i]);
			}
			sessionStorage["open_convs"] = JSON.stringify(open_convs_limpia);
		}
	}
}

function chat_conv_show(emisor) {
	$("#chat_padre_contactos").hide();
	
	//Minimizamos conversaciones abiertas
	$(".chat_ventana:not('#chat_conv_" + emisor+"')").hide();
	$(".chat_ventana_min:not('#chat_conv_" + emisor+"_min')").removeClass("activa");
	
	//Toggle current conv
	$("#chat_conv_" + emisor).toggle(300, 'linear');
	$("#chat_conv_" + emisor).find("input").focus();
	$("#chat_conv_" + emisor+"_min").toggleClass("activa");
	
	
	// Seteamos la ultima conversacion abierta
	if(typeof(Storage)!=="undefined"){
		var open_convs = JSON.parse(sessionStorage["open_convs"]);
	
		//Traspasamos valores y metemos la ultima conv al final del array
		open_convs_limpia = new Array();
		for(i=0;i<open_convs.length;i++){
			if(open_convs[i].iduser != emisor){
				open_convs_limpia.push(open_convs[i]);
			}else{
				last_conv = {
					iduser:open_convs[i].iduser,
					nombre:open_convs[i].nombre,
					img:open_convs[i].img
				};
			}
		}
		open_convs_limpia.push(last_conv);
		sessionStorage["open_convs"] = JSON.stringify(open_convs_limpia);
	}
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

	mensaje = $("#chat_conv_" + iduser).find("input[name='mensaje']").val();
	if (mensaje.length == 0)	return false;
	mensaje_f = "<div class='mensaje_propio'><div class='texto'>" + mensaje + "<div class='fecha'>" + fecha + "</div></div></div>";
	$("#chat_conv_" + iduser).find("input[name='mensaje']").val("");
	

	
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
				chat_conv_init(emisor, nombre, img, 'normal');
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

function chat_conv_mini(emisor) {
	$("#chat_conv_" + emisor).hide();
	$("#chat_conv_" + emisor + "_min").removeClass("activa");
	$("#chat_conv_" + emisor + "_min").find(".mensajes").hide();
}

function chat_conv_resize(emisor) {
	$("#chat_conv_" + emisor).toggleClass("maximizado");
}

function chat_conv_cerrar(emisor) {
	$("#chat_conv_" + emisor).remove();
	$("#chat_conv_" + emisor+"_min").remove();
	
	//Limpiamos las conversaciones abiertas
	if(typeof(Storage)!=="undefined"){
		var open_convs = JSON.parse(sessionStorage["open_convs"]);
		open_convs_limpia = new Array();
		for(i=0;i<open_convs.length;i++){
			if(open_convs[i].iduser != emisor){
				open_convs_limpia.push(open_convs[i]);
			}
			sessionStorage["open_convs"] = JSON.stringify(open_convs_limpia);
		}
	}
}
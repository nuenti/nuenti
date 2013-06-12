chat_mode_home = false;
$(document).ready(function() {
	//sessionStorage["open_convs"] = JSON.stringify([]);//TODO: fallo al recargar sesiones¿?
	//var open_convs = JSON.parse(sessionStorage["open_convs"]);
	//alert(JSON.stringify(open_convs));
			
	if (chat_estado == 1) {
		// Auto iniciar conversaciones
		if(typeof(Storage)!=="undefined"){
			if (sessionStorage["open_convs"] ) {
				var open_convs = JSON.parse(sessionStorage["open_convs"]);
				for(i=0;i<open_convs.length;i++){
					chat_conv_init(open_convs[i].iduser, open_convs[i].nombre, open_convs[i].img, "auto");
					
					//Cargamos los mensajes anteriores
					chat_leer_prev(open_convs[i].iduser);
					
					//La maximizamos
					if(open_convs[i].maximizada == true)
						$("#chat_conv_" + open_convs[i].iduser).addClass("maximizada");
					
					// La hacemos visible
					if(open_convs[i].activa == true){
						$("#chat_conv_" + open_convs[i].iduser+"_min").addClass("activa");
						$("#chat_conv_" + open_convs[i].iduser).show();
					}
					
					// Tiene mensajes sin leer
					if(open_convs[i].msg == true){
						new_message(open_convs[i].iduser, open_convs[i].nombre, "auto");
						
					}
				}
			}
		}
	}
});

$(window).ready(function() {
	if (chat_estado == 1) {
		//Actualizando la ultima conexion
		online_keep();
		timeOutOnlineKeep = window.setInterval(online_keep, 30000);
		
		chat_leer();
		timeOutChatLeer = window.setInterval(chat_leer, 5000);
		chat_contactos();
		timeOutChatContactos = window.setInterval(chat_contactos, 10000);
		
		$("#mensajes").find(".texto").live({
			mouseenter: function() {
				$(this).find(".fecha").toggle();
			},
			mouseleave: function() {
				$(this).find(".fecha").toggle();
			}
		});
	}
});


function chat_turn(modo) {
	if (modo == "1") {
		ajax_post({
			data : "chat_estado=1",
			visible : false
		});
		
		$("#chat #activar").hide();
		$("#chat #encabezado").show();

		chat_leer();
		timeOutChatLeer = window.setInterval(chat_leer, 5000);
		chat_contactos();
		timeOutChatContactos = window.setInterval(chat_contactos, 10000);

		chat_estado = 1;
	} else {
		$("#chat #activar").show();
		$("#chat #encabezado").hide();
		
		//Cerramos conversaciones activas
		$(".chat_ventana,.chat_ventana_min").remove();
		//Desactivamos el chat
		ajax_post({
			data : "chat_estado=0",
			visible : false
		});
		$("#chat_contactos").html("");
		//$("#chat_estado").html("<p style='cursor:pointer;' onclick='chat_turn(\"1\")'>Activar Chat</p>");
		
		if (chat_mode_home != true) {
			bottom = $("#chat_padre_contactos").height();
			$("#chat_padre_contactos").css("bottom",bottom+30);
		}
		
		timeOutChatLeer = window.clearInterval(timeOutChatLeer);
		timeOutChatContactos = window.clearInterval(timeOutChatContactos);
		chat_estado = 0;
		
		//Limpiar convs abiertas
		sessionStorage["open_convs"] = JSON.stringify([]);
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
		if (chat_mode_home != true) {
			bottom = $("#chat_padre_contactos").height();
			$("#chat_padre_contactos").css("bottom",bottom+30);
		}
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
	if(modo == "normal"){
		$(".chat_ventana").each(function() {
			user = $(this).attr("iduser");
			if(user != iduser)
				chat_conv_mini(user);
		});
	}
	
	//Creamos una conversacion nueva
	//TODO: estado del contacto
	if ($("#chat_conv_" + iduser).length < 1) {
		chat_ventana = "<div id='chat_conv_" + iduser + "_min' class='chat_ventana_min' onclick=\"chat_conv_show('" + iduser + "', 'normal')\">" +
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
		if(modo == 'normal' || modo == "msg"){
			chat_conv_show(iduser, 'normal');
			
			// Añadimos la conversacion a las existentes
			if(typeof(Storage)!=="undefined"){
				if (!sessionStorage["open_convs"] ) {
					sessionStorage["open_convs"] = JSON.stringify([]);
				}
				var open_convs = JSON.parse(sessionStorage["open_convs"]);
				
				if(modo == "normal"){
					new_conv = {
						iduser:iduser,
						nombre:nombre,
						img:img,
						activa:true,
						maximizada:false
					};
				}else if(modo == "msg"){
					new_conv = {
						iduser:iduser,
						nombre:nombre,
						img:img,
						activa:false,
						maximizada:false
					};
				}
				
				open_convs.push(new_conv);
				sessionStorage["open_convs"] = JSON.stringify(open_convs);
			}
		}
	//Mostramos una conversacion existente
	} else {
		chat_conv_show(iduser, 'normal');
	}
}

function chat_conv_show(emisor, modo) {
	$("#chat_padre_contactos").hide();
	
	//Minimizamos conversaciones abiertas
	$(".chat_ventana:not('#chat_conv_" + emisor+"')").hide();
	$(".chat_ventana_min:not('#chat_conv_" + emisor+"_min')").removeClass("activa");
	$("#chat_conv_" + emisor+"_min").find(".mensajes").hide();
	
	//Activa -> minimizar
	if($("#chat_conv_" + emisor+"_min").hasClass("activa")){
		chat_conv_mini(emisor);

	//Minimiza -> Abrir
	}else{
		$("#chat_conv_" + emisor+"_min").toggleClass("activa");
		$("#chat_conv_" + emisor).css({bottom:"-60px", display: "inline-block"});
		
		//Toggle current conv
		if($("#chat_conv_" + emisor).hasClass("maximizada")){
			$("#chat_conv_" + emisor).animate(
				{"bottom":"530px"}, 
				500
			);
		}else{
			$("#chat_conv_" + emisor).show();
			$("#chat_conv_" + emisor).animate(
				{"bottom":"330px"}, 
				500
			);
		}
		$("#chat_conv_" + emisor).find("input").focus();
		
		//Scroll down
		$("#chat_conv_"+emisor).find("#mensajes").scrollTop($("#chat_conv_"+emisor).find("#mensajes").prop("scrollHeight"));
	}
	
	if(modo == "normal"){
		// Configuramos como activa la ultima conversacion (o no)
		if(typeof(Storage)!=="undefined"){
			var open_convs = JSON.parse(sessionStorage["open_convs"]);

			for(i=0;i<open_convs.length;i++){
				if(open_convs[i].iduser == emisor){
					if($("#chat_conv_" + emisor+"_min").hasClass("activa")){
						open_convs[i].activa = true;
					}else{
						open_convs[i].activa = false;
					}
				// Las demas conversaciones estan cerradas
				}else{
					open_convs[i].activa = false;
				}
			}
			sessionStorage["open_convs"] = JSON.stringify(open_convs);
		}
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
		var chats = new Array();
		$("#chat_conv_tmp").find("div").each(function() {
			idchat = $(this).attr("idchat");
			emisor = $(this).attr("iduser");
			nombre = $(this).attr("nombre");
			fecha = $(this).attr("fecha");
			img = $(this).attr("img");
			
			mensaje = $(this).html();
			mensaje_f = "<div idchat='"+ idchat +"' class='mensaje_ajeno'><div class='texto'>" + $(this).html() + "<div class='fecha'>" + fecha + "</div></div></div>";
			
			// Conver minimizada
			if ($("#chat_conv_" + emisor).is(":hidden")) {
				
				$("#chat_conv_" + emisor).find("#mensajes").append(mensaje_f);
				
				//si se reciben varios mensajes de golpe generar solo una alerta
				if($.inArray(emisor, chats) == -1){
					new_message(emisor, nombre, "normal");
				}
				
			// Conver activa
			} else if ($("#chat_conv_" + emisor).is(":visible")) {
				$("#chat_conv_" + emisor).find("#mensajes").append(mensaje_f);
				
			// Conver inexistente
			} else {
				//La iniciamos
				chat_conv_init(emisor, nombre, img, 'msg');
				
				//añadimos el msg
				$("#chat_conv_" + emisor).find("#mensajes").append(mensaje_f);
				
				//si se reciben varios mensajes de golpe generar solo una alerta
				if($.inArray(emisor, chats) == -1){
					new_message(emisor, nombre, "normal");
				}
			}
			
			// Deslizando verticalmente la conversacion
			//$("#chat_conv_"+emisor).find("#mensajes").scrollTop($("#chat_conv_"+emisor).find("#mensajes").prop("scrollHeight"))
			$("#chat_conv_" + emisor).find("#mensajes").animate({
				scrollTop : $("#chat_conv_" + emisor).find("#mensajes").prop("scrollHeight")
			}, 3000);
			
			chats.push(emisor);
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
	// Conv no activa
	$("#chat_conv_" + emisor + "_min").removeClass("activa");
	//$("#chat_conv_" + emisor + "_min").find(".mensajes").hide();
	
	if(modo = 'normal'){
		//Animacion minimizar
		$("#chat_conv_" + emisor).animate(
			{"bottom":"-60px"}, 
			500,
			function(){
				//Al acabar la ocultamos por si las moscas
				$(this).hide();
				$(this).css("bottom","");
			}
		);
		
		// Seteamos como minimizada
		if(typeof(Storage)!=="undefined"){
			if (sessionStorage["open_convs"] ) {
				var open_convs = JSON.parse(sessionStorage["open_convs"]);
				for(i=0;i<open_convs.length;i++){
					if(open_convs[i].iduser == emisor)
						open_convs[i].activa = false;
				}
				sessionStorage["open_convs"] = JSON.stringify(open_convs);
			}
		}
	}
}

function chat_conv_resize(emisor) {
	//Toggle class
	$("#chat_conv_" + emisor).toggleClass("maximizada");
		
	//Seteamos la conv como maximizada
	if(typeof(Storage)!=="undefined"){
		var open_convs = JSON.parse(sessionStorage["open_convs"]);
		for(i=0;i<open_convs.length;i++){
			if(open_convs[i].iduser == emisor){
				if($("#chat_conv_" + emisor).hasClass('maximizada')){
					open_convs[i].maximizada = true;
					$("#chat_conv_" + emisor).css({bottom:"530px"}); //importante, anula la class original
				}else{
					open_convs[i].maximizada = false;
					$("#chat_conv_" + emisor).css("bottom",""); //usamos el valor de las class original
				}
			}
		}
		sessionStorage["open_convs"] = JSON.stringify(open_convs);
	}
}

function chat_conv_cerrar(emisor) {
	//Cerramos las ventanas
	$("#chat_conv_" + emisor).remove();
	$("#chat_conv_" + emisor+"_min").remove();
	
	//Limpiamos las conversaciones abiertas
	if(typeof(Storage)!=="undefined"){
		var open_convs = JSON.parse(sessionStorage["open_convs"]);
		open_convs_limpia = new Array();
		for(i=0;i<open_convs.length;i++){
			if(open_convs[i].iduser != emisor)
				open_convs_limpia.push(open_convs[i]);
		}
		sessionStorage["open_convs"] = JSON.stringify(open_convs_limpia);
	}
}

//Notificamos el mensaje nuevo para una conversacion minimizada o cerrada
function new_message(emisor, nombre, modo){
	//TODO: probar parpadeo
	
	//Mostrar bocadillo verde
	$("#chat_conv_" + emisor + "_min").find(".mensajes").show();
	
	
	if(modo == "normal"){
		
		//Animacion ventana minimizada
		$("#chat_conv_" + emisor + "_min").effect( "bounce", "slow" );
		
		// Parpadeo title
		if(document.hasFocus()==false){
			var title = document.title;
			new_message_interval = setInterval(function() {
				
				if(document.title != title){
					document.title = title;
				}else{
					document.title = nombre;
				}
				
				if(document.hasFocus()==true){
					clearInterval(new_message_interval);
					document.title = title;
				}		
			}, 2000);
		}
		
		//Seteamos la conv como no leida
		if(typeof(Storage)!=="undefined"){
			var open_convs = JSON.parse(sessionStorage["open_convs"]);
			for(i=0;i<open_convs.length;i++){
				if(open_convs[i].iduser == emisor)
					open_convs[i].msg = true;
			}
			sessionStorage["open_convs"] = JSON.stringify(open_convs);
		}
	}
}
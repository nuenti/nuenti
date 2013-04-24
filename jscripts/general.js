function ajax_post(p) {
	
	if (!p.url)
		p.url = "post.php";
		
	if (p.async!=false)
		p.async = true;
		
	if (p.visible!=false)
		p.visible = true;

	var ajax = $.ajax({
		type : "POST",
		url : p.url,
		data : p.data,
		cache : false,
		async : false
	});
	var promise = ajax.done(function(msg) {
	 //alert(msg);
	 return msg;
	 });

	var promise = ajax.always(function(msg) {
		$("#ajax_cargando_padre").remove();
		if (p.visible == true) {
			$("body").append("<div id='ajax_cargando_padre'><div id='ajax_cargando'><img src='css/loading.gif'></div></div>");
		}
	});

	var promise = ajax.fail(function(msg) {
		ajax_post_fail();
	});

	var promise = ajax.done(function(msg) {
		ajax_post_fail();
		return false;
		if (msg.length == 0 && !p.retrieve){
			if(p.visible == true) {
				$("#ajax_cargando").addClass("ajax_ok");
				$("#ajax_cargando").html("<img src='css/ok.png'>");
				$("#ajax_cargando_padre").fadeIn(function() {
					setTimeout(function() {
						$("#ajax_cargando").fadeOut("slow");
					}, 1000);
				});
				$("#ajax_cargando").click(function(){
					$(this).remove();
				});
			}
			if (p.reload)
				window.setTimeout((function() {
					window.location.reload();
				}), 1000);

			if (p.nextUrl)
				window.setTimeout((function() {
					window.location = nextUrl;
				}), 1000);

		} else if (msg == "ERROR") {
			ajax_post_fail();
		} else if (msg != "ERROR") {
			if (p.retrieve == true) {
				$("#ajax_cargando_padre").remove();
				return msg;
			} else {
				ajax_post_fail(msg);
			}
		}
	});
	return ajax.responseText;
	//return ajax;
}

function ajax_post_fail(msg) {
	if (msg) {
		$("#ajax_cargando").addClass("ajax_fail");
		$("#ajax_cargando").html("<img src='css/advertencia.png'><div class='texto'>Ha ocurrido un error:<br><br>" + msg + "<br><br><a onclick='ajax_post_aceptar()'>Aceptar</a></div>");
	} else {
		$("#ajax_cargando").addClass("ajax_fail");
		$("#ajax_cargando").html("<img src='css/advertencia.png'><div class='texto'>Ha ocurrido un error, int&eacute;ntalo de nuevo o ponte en contacto con nosotros.<br><br><a onclick='ajax_post_aceptar()'>Aceptar</a></div>");
	} 
	$("#ajax_cargando").css("width","500px");
}

function ajax_post_aceptar() {
	$("#ajax_cargando").fadeIn(function() {
		setTimeout(function() {
			$("#ajax_cargando_padre").fadeOut("slow");
		}, 0);
	});
}


$(document).ready(function() {
	// Muestra y oculta los menús
	$('#menu-altern ul li:has(ul)').hover(function(e) {
		$(this).find('ul').fadeIn();
	}, function(e) {
		$(this).find('ul').fadeOut();
	});

	// Señala en el menu horizontal la pagina actual
	var url = location.href.match(/[a-z0-9_-]{1,}.php/gi);
	$("#menudrop").find("a").each(function() {
		if ($(this).attr("href") == url) {
			//alert(url);
			$(this).css({
				"color" : "white"
			});
			$(this).parent().css({
				"background-color" : "#3869A0"
			});
		}
	});
}); 
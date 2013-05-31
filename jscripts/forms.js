function toggle_label(e) {
	if ($(e).is("input")) {
		//llamada mediante $.each
		if ($(e).attr("type") != "radio" && $(e).attr("type") != "checkbox") {
			if ($(e).val().length > 0) {
				$("label[for='" + $(e).attr("id") + "']").css("display", "none");
			} else {
				$("label[for='" + $(e).attr("id") + "']").css("display", "inline");
			}
		}
	} else {
		//llamada mediante evento
		if (e.target.type != "radio" && e.target.type != "checkbox") {
			if (e.target.value.length > 0 || $(e.target).is(":focus")) {
				$("label[for='" + e.target.id + "']").css("display", "none");
			} else {
				$("label[for='" + e.target.id + "']").css("display", "inline");
			}
		}
	}
}

$(window).load(function() {
	//Al cargar la pagina se revisa si hay inputs rellenos
	$("input").each(function() {
		toggle_label($(this));
	});
	
	//Al cambiar los inputs toggleamos el label
	$("input").bind({
		keyup : function(e) {
			toggle_label(e);
		},
		change : function(e) {
			toggle_label(e);
		},
		focus : function(e) {
			toggle_label(e);
		},
		blur : function(e) {
			toggle_label(e);
		}
	});
});
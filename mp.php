<?php
	require("inc/verify_login.php");
	
	if($_GET['modo'] == "enviar"){
		head("MPs Enviar");
		estructura("seccion_mp_enviar");
		require("inc/mps/enviar.inc.php");
	}elseif($_GET['modo'] == "recibidos" OR !$_GET['modo']){
		head("MPs Recibidos");
		estructura("seccion_mp_recibidos");
		require("inc/mps/recibidos.inc.php");
	}elseif($_GET['modo'] == "enviados"){
		head("MPs Enviados");
		estructura("seccion_mp_enviados");
		require("inc/mps/enviados.inc.php");
	}
	
	function estructura($seccion){
		global $link;
		echo "<body id='{$seccion}'>";
		require("inc/estructura.inc.php");
		print "<div class='barra_izq'>
					<div class='marco_small lista_enlaces' style='padding: 0;'>
						<ul>
							<li><a href='mp.php?modo=enviar'>Enviar mensaje</a></li>
							<li><a href='mp.php?modo=recibidos'>Mensajes Recibidos</a></li>
							<li><a href='mp.php?modo=enviados'>Mensajes Enviados</a></li>
						</ul>
					</div>
				</div>
				<script>
					$(document).ready(function() {
						// Señala en el menu vertical la pagina actual
						var url = location.href.match(/[a-z0-9_-]{1,}.php[/?seccion=a-z]{0,}/gi);
						$('.lista_enlaces').find('a').each(function() {
							if ($(this).attr('href') == url) {
								//alert(url);
								$(this).css({
									'color' : 'white'
								});
								$(this).parent().css({
									'background-color' : '#3869A0'
								});
							}
						});
					});
				</script>
				<div class='barra_centro_der'>
					<div class='marco'>
				";
	}
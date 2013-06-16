<?php
session_start();
require('inc/config.php');

if(isset($_SESSION['idsesion']) AND !$_GET['activacion'])
	header("Location: inicio.php");


if($_POST['email']){
	//TODO: blowfish
	//COMPROBAR DATOS LOGIN
	$login=mysqli_query($link,"SELECT * FROM usuarios WHERE email='".$_POST['email']."' AND password='".sha1($_POST['password'])."' AND activacion = '1'");

	//LOGIN OK
	if(mysqli_num_rows($login)==1){
		$codigo_temp=rand(0, 99999999999);
		$r_login=mysqli_fetch_assoc($login);
		mysqli_query($link,"UPDATE usuarios SET idsesion='".$codigo_temp."' WHERE idusuarios='".$r_login['idusuarios']."'");
		
		// Log de acceso con IP & fecha
		mysqli_query($link,"INSERT INTO accesos (usuarios_idusuarios, ip, fecha) VALUES ('{$r_login["idusuarios"]}','{$_SERVER["REMOTE_ADDR"]}',now())");
		$_SESSION['idsesion']=$codigo_temp;
		
		header( "Location: inicio.php" );
		die();
		// TODO: funcionalidad de URL al loguear.
		// TODO: duracion de la sesion al cerrar la pagina

	//LOGIN FAIL
	}else{
		//COMPROBAR ACTIVACION CUENTA
		$sql = "SELECT * FROM usuarios WHERE email='".$_POST['email']."' AND activacion != '1'";
		$q_activacion = mysqli_query($link, $sql);
	
		//CUENTA NO ACTIVADA
		if(mysqli_num_rows($q_activacion)==1){
			$error = "activacion";
		}else{
			$error = "datos";
		}
	}
	
}

head("Login");
echo "<script type='text/javascript' src='jscripts/login_js.php'></script>";
echo "<script type='text/javascript' src='jscripts/forms.js'></script>";
?>
<body id="seccion_login">
		<div class="centrar">
			<div id="titulo">
				<img src="css/logosocial.png">
				<h1><?php echo Sitio; ?></h1>
			</div>
			
			<?php
			if($error){
				echo "<div class='centrar'><div class='error_ajustable'>";
				if($error == "datos"){
					print "El email o la contraseña introducidos son incorrectos.<br>
							<a href='otros.php?restore_pass=1'>¿Quieres recuperar la contrase&ntilde;a?</a>
						";
				}elseif($error == "activacion"){
					echo "La cuenta no est&aacute; activada a&uacute;n, revisa tu cuenta de email";
				}
				echo "</div></div>";
			}
			
			if($_GET['activacion']){
				if($_GET['activacion'] == "ok"){
					echo "<div class='centrar'><div class='ok_ajustable'>La cuenta se ha activado correctamente, ahora puedes entrar con tus datos</div></div>";
				}elseif($_GET['activacion'] == "fail"){
					echo "<div class='centrar'><div class='error_ajustable'>La activacion de la cuenta ha fallado</div></div>";
				}elseif($_GET['activacion'] == "fail_email"){
					print "<div class='centrar'><div class='error_ajustable'>Se ha producido un error al enviar el correo</div></div>";
				}
			}
			?>	
				<form id='form_login' method='POST' action='login.php'>
					Email: 					
						<div class="input">
							<span>
								<input id="email" name="email" class="validable" type="text" value="<?php echo $_POST['email']; ?>" maxlength='40' autofocus placeholder="ejemplo@mail.com">
							</span>
						</div><br>
					
					Clave:  					
						<div class="input">
							<span>
								<input id="password" name="password" class="validable" type="password" value="" maxlength='30' placeholder="Contrase&ntilde;a">
							</span>
						</div><br>
					<button type='button' name='registro' value='Registrarse' class="azul" onclick="validador('submit')"><span><b>Entrar</b></span></button>
					<button type='button' class="azul" onclick="location.href='registro.php';"><span><b>Registrarse</b></span></button>
				  </form>
			</div>
		</div>
		<div id="creditos">
			<div>
				<p>Social &copy; 2012 - 2013</p>
				<p>Social Inc.&#8482;</p>
			</div>
		</div>
</body>
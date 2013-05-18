<?php
session_start();
require('inc/config.php');
if(isset($_SESSION['idsesion'])) {
	header("Location: inicio.php");
}
head("Login - Social");
echo "<script type='text/javascript' src='jscripts/valida_login.php'></script>";

if($_POST['email']){
	$login=mysqli_query($link,"SELECT * FROM usuarios WHERE email='".$_POST['email']."' AND password='".sha1($_POST['password'])."'");

	//LOGIN OK
	if(mysqli_num_rows($login)==1){
		$codigo_temp=rand(0, 99999999999);
		$r_login=mysqli_fetch_assoc($login);
		mysqli_query($link,"UPDATE usuarios SET idsesion='".$codigo_temp."' WHERE idusuarios='".$r_login['idusuarios']."'");
		
		// Log de acceso con IP & fecha
		mysqli_query($link,"INSERT INTO accesos (usuarios_idusuarios, ip, fecha) VALUES ('{$r_login["idusuarios"]}','{$_SERVER["REMOTE_ADDR"]}',now())");
		$_SESSION['idsesion']=$codigo_temp;
		session_write_close();
		header( "Location: inicio.php" );
		//echo "LOGIN OK";
		/*if($_POST['logincookie']==true){
			$fecha=time()+60*60*24*30;
		}else{
			$fecha=0;
		}*/

		// TODO: funcionalidad de URL al loguear.
		//HAY URL?
		/*if($_COOKIE['tienda_url']){
			$url = $_COOKIE['forocochesurl'];
			setcookie("tienda_url", $_COOKIE['forocochesurl'], time()-36000000);
			header( "Location: ".$url );
		}else{
			header( "Location: index.php?log" );
		}*/

	//LOGIN FAIL
	}else{
		$error=1;
	}
}
?>


		<h1 id="logo">Social</h1>
		<?php	if($error==1){	?>
				<div class="centrar">
					<div class='error'>El email o la contraseña son incorrectos</div>
				</div>
		<?php	}	?>
		<div class="centrar">
			<div class="marco login_form">
				<form id='form_login' method='POST' action='login.php'>
					Email: <input type='text' class="validable" size='20' name='email' value="<?php echo $_POST['email']; ?>" autofocus><br>
					Clave: <input type='password' class="validable" size='20' name='password'><br>
					<!--Recordar datos: <input name='logincookie' type='checkbox' value='true'>
					<br>-->
				  <button type="button" onclick="validador('submit');">Entrar</button>
				  <button type='button' onclick="location.href='registro.php';">Registrarse</button>
				  </form>
			</div>
		</div>
		<div id="creditos">
			<div>
				<p>Social &copy; 2012</p>
				<p>Social Inc.&#8482;</p>
			</div>
		</div>
</body>
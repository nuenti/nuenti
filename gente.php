<?php
	require("inc/verify_login.php");
	head("Gente");
	echo "<body id='seccion_gente'>";
	require("inc/estructura.inc.php");
?>
<div class='barra_izq_centro' style="width: 710px;">
	<div class="marco">
				<script>
					function peticion_enviar(idusuario){
						ajax_post({
							data: "peticion_amistad_enviar=1&idusuario="+idusuario
						});
						$("#resultado"+idusuario).find(".estado_amistad button").attr({"disabled":"true", onclick:""});
						$("#resultado"+idusuario).find(".estado_amistad button b").text("Peticion enviada");
					}
					
					function cambiar_pagina(number){
						$("input[name='page']").val(number);
						$("form[name='busqueda']").submit();
					}
					
					$(document).ready(function(){
						// Marcar la opcion del select elegido
						$("select[name='provincia']").find("option[value='<?php echo $_POST['provincia']; ?>']").attr("selected","true");
					});
				</script>
				
				<?php			
				$sql="SELECT *,
					FLOOR(DATEDIFF(CURDATE(),fnac)/365) AS edad,
						(SELECT count(*)
						FROM amigos WHERE user1='".$global_idusuarios."' AND user2=idusuarios OR  user2='".$global_idusuarios."' AND user1=idusuarios
						) AS amigo,
						(SELECT count(*)
						FROM peticiones WHERE emisor='".$global_idusuarios."' AND receptor=idusuarios OR  receptor='".$global_idusuarios."' AND emisor=idusuarios
						) AS enviada
					FROM usuarios LEFT JOIN fotos ON idfotos_princi=idfotos";
				$where="";
				if($_POST['nombre']){
					$where.=" AND nombre LIKE '%{$_POST['nombre']}%'";
				}
				if($_POST['apellidos']){
					$where.=" AND apellidos LIKE '%{$_POST['apellidos']}%'";
				}
				if($_POST['edad_menor']){
					$where.=" AND fnac <= date_add(now(), INTERVAL -{$_POST['edad_menor']} YEAR)";
				}
				if($_POST['edad_mayor']){
					$where.=" AND fnac >= date_add(now(), INTERVAL -{$_POST['edad_mayor']} YEAR)";
				}
				if($_POST['provincia']){
					$where.=" AND provincia = '{$_POST['provincia']}'";
				}
				if($_POST['sexo']){
					$where.=" AND sexo = '{$_POST['sexo']}'";
				}
				
				$where = " WHERE idusuarios!='$global_idusuarios'$where";
				$where_backup = $where;
				
				if(!$_POST['page']) $_POST['page']=1;
				$limit = ($_POST['page'] - 1) * 5;
				$where .= " LIMIT $limit, 5";
				$sql .= $where;
				
				//TODO: Debug
				/*echo "<pre>";
				print_r($_POST);
				echo "</pre>";
				echo "$sql<br>";*/
				
				echo "<div id='resultados'>";
				$q_search = mysqli_query($link,$sql);
				if(mysqli_num_rows($q_search)){
					error_mysql();
					while($r_search = mysqli_fetch_assoc($q_search)){
						//print_r($r_search);
						
						//Estado de la amistad
						if($r_search['amigo']){
							$estado_amistad="";
							$nombre = "<a href='perfil.php?id={$r_search['idusuarios']}' class='link'>{$r_search['nombre']} {$r_search['apellidos']}</a>";
						}elseif($r_search['enviada']){
							$estado_amistad="<button type='button' disabled class='verde'><span><b>Peticion enviada</b></span></button>";
							$nombre = $r_search['nombre']." ".$r_search['apellidos'];
						}else{
							$estado_amistad="<button type='button' class='verde' onclick=\"peticion_enviar('{$r_search['idusuarios']}')\"><span><b>Agregar</b></span></button>";
							$nombre = $r_search['nombre']." ".$r_search['apellidos'];
						}
						
						// Imprimimos los resultados
						//TODO: Añadir estado a los resultados
						print "
							<div class='resultado' id='resultado{$r_search['idusuarios']}'>
								<div class='img'>
									<img alt='foto principal' src='{$r_search['archivo']}' />
								</div>
								<div class='datos'>
									<div class='nombre'>{$nombre}</div>
									<div class='info'>
										{$r_search['edad']} Años<br>
										".IdProvincia($r_search['provincia'])."<br>
									</div>
								</div>
								<div class='right'>
									<div class='estado_amistad'>
										{$estado_amistad}
									</div>
									<div class='mp'>
										<button type='button' class='azul' onclick=\"location.href='mp.php?modo=enviar&amp;receptor={$r_search['idusuarios']}'\"><span><b>Enviar MP</b></span></button>
									</div>
								</div>
							</div>
						";
					}
					
					//Numeracion paginas
					$q_search_nums = mysqli_query($link, "SELECT COUNT(*) AS total FROM usuarios $where_backup");
					$r_search_nums = mysqli_fetch_assoc($q_search_nums);
			
					$siguiente = $_POST['page'] + 1;
					$anterior = $_POST['page'] - 1;
					$ultima = ceil($r_search_nums['total'] / 5);
			
					//BARRA NAVEGACION
					echo "<div id='barra_navegacion'>";
						if ($_POST['page'] > 1) {
							echo "<img class='flecha_back_top' src='css/flechas/flecha_left_top.jpg' onclick=\"cambiar_pagina(1);\">";
							echo "<img class='flecha_back' src='css/flechas/flecha_left.jpg' onclick=\"cambiar_pagina($anterior);\">";
						}
						echo "<div class='texto'>".$_POST['page']." de ".$ultima."</div>";
						
					if ($_POST['page'] < $ultima) {
							echo "<img class='flecha_next' src='css/flechas/flecha_right.jpg' onclick=\"cambiar_pagina($siguiente);\">";
							echo "<img class='flecha_next_top' src='css/flechas/flecha_right_top.jpg' onclick=\"cambiar_pagina($ultima);\">";
						}
					echo "</div>";
				}else{
					print "<div class='resultado'>No se han encontrado resultados</div>";
				}
				echo "</div>";
		?>
	</div>
</div>
<div class="barra_der" style="width: 270px;">
	<div class="marco_small">
		<h3>Busqueda</h3>
		<form name="busqueda" method='post' action='gente.php'>
				<div class="input">
					<span>
						<input name="nombre" type="text" value="<?php echo $_POST['nombre']; ?>" placeholder="Nombre">
					</span>
				</div><br>
				
				<div class="input">
					<span>
						<input name="apellidos" type="text" value="<?php echo $_POST['apellidos']; ?>" placeholder="Apellidos">
					</span>
				</div><br>
				
				Edad entre:
				<div class="input">
					<span class="select">
						<select name="edad_menor">
							<option value="">-</option>
							<?php
								for($i=18;$i<100;$i++){
									echo "<option";
									if($_POST['edad_menor']==$i){ echo " selected";}
										echo ">{$i}</option>";
								}
							?>
						</select>
					</span>
				</div>y<div class="input">
					<span class="select">
						<select name="edad_mayor">
							<option value="">-</option>
							<?php
								for($i=18;$i<100;$i++){
									echo "<option";
									if($_POST['edad_mayor']==$i){ echo " selected";}
										echo ">{$i}</option>";
								}
							?>
						</select>
					</span>
				</div><br>
				
				<div style="word-spacing: 30px;">Sexo: 
					<input type="radio" name="sexo" value="h" id="sexo_hombre" <?php if($_POST['sexo'] == "h") echo "checked"; ?>/>
					<label for="sexo_hombre" class="label_radio label_Sexo"> </label><label for="sexo_hombre">Hombre</label>
					<input type="radio" name="sexo" value="m" id="sexo_mujer" <?php if($_POST['sexo'] == "m") echo "checked"; ?>/>
					<label for="sexo_mujer" class="label_radio label_Sexo"></label><label for="sexo_mujer">Mujer</label>
				</div><br>
				
				
				
				<?php //TODO: Amigo, Amigo de amigos, Todos ?>
				
				Provincia: 
				<div class="input">
					<span class="select">
						<select name="provincia">
							<?php require("inc/select_provincias.html"); ?>
						</select>
					</span>
				</div>
				<input type="hidden" name='page' value="1">
				<input type="hidden" name='busqueda' value="true">
				<button type='submit' class="azul"><span><b>Buscar</b></span></button>
			</form>
	</div>
</div>

<?php
require ("inc/chat.php");
?>
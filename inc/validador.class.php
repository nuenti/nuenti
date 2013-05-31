<?php
/*
* llamada a la clase
* envio de condiciones de multiples campos
* llamada a generar js donde se quiera
* llamada a generar php donde se quiera
*/

class Validador{

	public $name;
	public $alias;
	public $obligatorio;
	public $min;
	public $max;
	public $semejante;
	public $expr;
	public $formato;
	public $campos = array();
	public $tipo;

	public function SetFromArray($arr){
		//Destruimos los atributos de un campo ya generado
		unset($this->name);
		unset($this->alias);
		unset($this->obligatorio);
		unset($this->min);
		unset($this->max);
		unset($this->semejante);
		unset($this->expr);
		unset($this->formato);
		unset($this->tipo);

		//Recoge los datos desde el array campos y setea los atributos
		foreach ($arr as $key => $value) {
			$this->$key = $value;
		}

	}
	//Recoge los datos
	public function SetInput($arr){
		//almacenamos los datos del campo en un array
		array_push($this->campos, $arr);
	}

	//Imprime el codigo validador JavaScript
	public function GeneraValidadorJS(){

		print "
				var ayuda = {};	//Declaro objeto global
				var validado = 0; //
				array_campos = new Array(); //Lista de campos validables

				//FUNCION VALIDADOR
				function validador(option){
					//texto del error general
					$('#valida_error').html('El formulario contiene campos rellenados err&oacute;neamente.<br>Situe el raton sobre un campo rojo para ver mas detalles');
					
					//Limpiamos la lista de campos para evitar duplicidades
					for(i=0;i<=array_campos.length;i++){
						array_campos.pop();
					}
					validado = 1;
		";

		foreach($this->campos as $t){

			$this->SetFromArray($t);
			if($this->campos){
				print("\n\n
					////////////\tCAMPO: ".$this->name."
					//Seteamos los objetos js
					valid_error=0;
					ayuda.".$this->name." = {};
					ayuda.".$this->name.".mensaje = '';
					array_campos.push('".$this->name."'); //Para posterior validacion

				");
				$help_div = "ayuda.".$this->name.".mensaje";

				//Si no hay alias, se asigna a este el name
				if(!$this->alias){
					$this->alias=$this->name;
				}

				
				$campo="if($(\"[name='".$this->name."']\")"; //Para ahorrar trabajo abajo
				$check = array(); //Array con todas las comprobaciones javascript

				// OBLIGATORIO
				if($this->obligatorio){
					array_push($check, $campo.".val().length<1){
						".$help_div." += 'El campo \"".$this->alias."\" es obligatorio<br>';
						valid_error=1;
					}\n\n");
				}

				// MIN
				if($this->min){
					array_push($check,$campo.".val().length<".$this->min."){
						".$help_div." += 'El campo \"".$this->alias."\" debe contener al menos ".$this->min." caracteres<br>';
						valid_error=1;
					}\n");
				}
				
				// SEMEJANTE
				if($this->semejante){
					$semejantes=preg_split("/,/", $this->semejante);
					array_push($check, $campo.".val()!=$(\"[name='".$semejantes[0]."']\").val() || $(\"input[name='".$this->name."']\").val().length<1){
						".$help_div." += 'El campo ".$semejantes[1]." y \"".$this->alias."\" no coinciden<br>';
						valid_error=1;
					}\n");
				}

				// FORMATO
				if($this->formato){
					array_push($check, $campo.".val().search(/".$this->formato."/g)==-1){
						".$help_div." += 'El contenido del campo \"".$this->alias."\" no tiene un formato valido<br>';
							valid_error=1;
					}\n");
				}
				
				// RADIO
				//TODO: solo se activa al segundo cambio
				if($this->tipo == "radio"){
					array_push($check,"if(!$(\"input[name='".$this->name."']\").is(':checked')){
						".$help_div." += 'Debes seleccionar una de las casillas de \"".$this->alias."\"<br>';
						valid_error=1;
					}\n");
				}

				// CHECKBOX
				if($this->tipo == "checkbox"){
					array_push($check,"if(!$(\"input[name='".$this->name."']\").is(':checked')){
						".$help_div." += 'Debes marcar la casilla \"".$this->alias."\"<br>';
						valid_error=1;
					}\n");
				}
				
				// SELECT
				if($this->tipo == "select"){
					$campo = "$(\"select[name='".$this->name."']\")";
					array_push($check,"
					if({$campo}.val()=='0' || {$campo}.val()=='NULL' || {$campo}.val()=='' ||  {$campo}.text()==''){
						".$help_div." += 'Debes seleccionar una opcion de \"".$this->alias."\"<br>';
						valid_error=1;
					}\n");
				}
				
				// MANEJO CLASSES
				if($this->tipo == "checkbox"){
					array_push($check, "
						if(valid_error==1){
							$(\"[type='checkbox'][name='".$this->name."']\").addClass('checkbox_error');
							$(\"[type='checkbox'][name='".$this->name."']\").removeClass('checkbox_ok');
						}else{
							$(\"[type='checkbox'][name='".$this->name."']\").removeClass('checkbox_error');
							$(\"[type='checkbox'][name='".$this->name."']\").addClass('checkbox_ok');
						}
					");
				}elseif($this->tipo == "radio"){
					array_push($check, "
						if(valid_error==1){
							$(\"label.label_".$this->name."\").addClass('radio_error');
							$(\"label.label_".$this->name."\").removeClass('radio_ok');
						}else{
							$(\"label.label_".$this->name."\").removeClass('radio_error');
							$(\"label.label_".$this->name."\").addClass('radio_ok');
						}
					");
				}elseif($this->tipo == "select"){
					array_push($check, "
						if(valid_error==1){
							$(\"[name='".$this->name."']\").addClass('input_error');
							$(\"[name='".$this->name."']\").removeClass('input_ok');
						}else{
							$(\"[name='".$this->name."']\").removeClass('input_error');
							$(\"[name='".$this->name."']\").addClass('input_ok');
						}
					");
				}else{
					array_push($check, "
						if(valid_error==1){
							$(\"[name='".$this->name."']\").parent().addClass('input_error');
							$(\"[name='".$this->name."']\").parent().removeClass('input_ok');
						}else{
							$(\"[name='".$this->name."']\").parent().addClass('input_ok');
							$(\"[name='".$this->name."']\").parent().removeClass('input_error');
						}
					");
				}
				foreach($check as $c){
					print $c;
				}

			}else{
				echo "<h3>Debes introducir el nombre del campo</h3>";
			}
		}
		print "
		
						//Comprobamos que no haya errores en los inputs
						form_ok = 1;
						for(i=0;i<array_campos.length;i++){
							var help_div; //fix ie
							help_div = eval('ayuda.' + array_campos[i] + '.mensaje');
							if(help_div.length > 0){
								form_ok = 0;
							}
						}
						
					// Se ha pulsado un boton, no la validacion auto
					if(option == 'submit'){
						//Si no ha errores enviar
						if(form_ok == 1){
							$('#valida_error').hide();
							$('form').submit();
						}else{
							$('#valida_error').css('display','inline-block');
						}
					}else{
						if(form_ok == 1){
							$('#valida_error').hide();
							return 'form_ok';
						}else{
							$('#valida_error').css('display','inline-block');
							return 'form_fail';
						}
					}

				}
				//Fin funcion Validador()

					$(document).ready(function(){

						// Si no existe el help_div se inserta
						if($(\"#help_div\").length<1){
							$('body').append(\"<div id='help_div'></div>\");
						}
						
						// MOSTRAR AYUDA
						function ayuda_mostrar(t){
							if(validado == 1){
								/* DEPENDIENDO DEL DIV SE USA UNA VARIABLE */
								if($(t).is('div')){
									help_div = eval('ayuda.' + $(t).find('input,select').attr('name') + '.mensaje');
								}else if($(t).parent().is('div')){
									help_div = eval('ayuda.' + $(t).parent().find('input,select').attr('name') + '.mensaje');
								}
								if(help_div.length>0){ /* LA VARIABLE NO ESTA VACIA */
									$('#help_div').html(help_div);
									$('#help_div').show();
									help_div =	'';
								}
							}
						}
						
						// OCULTAR AYUDA
						function ayuda_ocultar(){
							$('#help_div').html(\"\");
							$('#help_div').hide();
						}
						
						// TOGGLE AYUDA
						function ayuda_toggle(t){
							if(help_div.length<1){
								ayuda_ocultar();
							}else{
								ayuda_mostrar(t);
							}
						}
						
					
						// EVENTOS SOBRE CAMPOS
						$(\"div.input,span.select,label,div.checkbox\").bind({
							mouseenter:function() {
								if(validado == 1){
									ayuda_mostrar(this);
									$(\".validable,label\").mousemove(function(e){
										var x = e.pageX + 15;
										var y = e.pageY - 15;
										$('#help_div').css({'left':x,'top':y});
									});
								}
							},
							mouseleave:function() {
								if(validado == 1){
									ayuda_ocultar();
								}
							},
							keyup:function(event) {
								if(validado == 1){
									validador();
									ayuda_toggle(this);
								}
								if(!$(this).is('textarea')){
									if (event.which == 13) {
										validador('submit');
									}
								}
							},
							focusout:function() {
								if(validado == 1){
									validador();
									ayuda_ocultar();
								}
							},
							click:function(e) {
								if(validado == 1){
									//Chapuza del siglo, marcamos el radio antes de pasar el validador
									if(e.target.tagName=='LABEL'){
										id = $(e.target).attr('for');
										if($(\"input[type='radio']#\"+id).length){
											$(\"input[type='radio']#\"+id).attr('checked',true);
										}
									}
									validador();
									ayuda_toggle(this);
								}
							},
							change:function() {
								if(validado == 1){
									validador();
									ayuda_toggle(this);
								}
							}});
						
					});
			";
	}


	//Genera el codigo validador PHP
	public function GeneraValidadorPHP(){
		foreach($this->campos as $t){

			$this->SetFromArray($t);

			if($this->campos){
				if(!$mensaje){
					$mensaje="";
				}
				$campo=$_POST[$this->name];
				//print($campo);
				//print_r($t);
				//print(strlen($campo)."<br>");
				$mensaje.="<br><b>".$this->name.": ".$campo."</b><br>";

				//Obligatorio
				if($this->obligatorio){
					if(strlen($campo)<1){
						$mensaje.="El campo \"".$this->name."\" es obligatorio<br/>";
					}

				}

				//Min
				if($this->min){
					if(strlen($campo)<$this->min){
						$mensaje.="El campo \"".$this->name."\" debe contener al menos ".$this->min." caracteres<br/>";
					}
				}

				//Max
				if($this->max){
					if(strlen($campo)>$this->max){
						$mensaje.="El campo \"".$this->name."\" debe contener como maximo ".$this->max." caracteres<br/>";
					}
				}

				//Radio
				if($this->radio){
					unset($radiook);
					foreach (preg_split("/,/", $this->radio) as $key => $value) {
						if(strlen($_POST[$value])){
							$radiook="1";
						}
					}
					if(!$radiook){
						$mensaje.="Debes seleccionar una de las opciones del campo \"".$this->name."\"<br/>";
					}
				}

				//Semejante
				if($this->semejante){
					$semejantes=preg_split("/,/", $this->semejante);
					$semejante_anterior=$_POST[$semejantes[0]];
					foreach ($semejantes as $key => $value) {
						//echo "\$_POST[".$value."]:".$_POST[$value];
						if($_POST[$value]!=$semejante_anterior){
							$semejante_error="1";
						}
						$semejante_anterior=$_POST[$value];
					}
					if($semejante_error){
						$mensaje.="Los campos \"".$this->semejante."\" no coinciden<br/>";
					}
				}

				//Expr
				if($this->expr){
					if(preg_match($this->expr, $campo)==0){
						$mensaje.="El campo \"".$this->name."\" contiene caracteres invalidos<br/>";
					}
				}
				//Formato
				if($this->formato){
					if(!preg_match('/'.$this->formato.'/i', $campo)){
						$mensaje.="El contenido del campo \"".$this->name."\" no tiene un formato valido<br/>";
					}
				}

			}else{
				echo "<h3>Debes introducir el nombre del campo</h3>";
			}

		}

		if(strlen($mensaje)>0){
			//echo(""<h3>CODIGO VALIDADOR PHP----></h3>"".$mensaje."<h3><---CODIGO VALIDADOR PHP</h3>");
			echo "El validador PHP no esta de acuerdo.";
		}
	}
}
?>
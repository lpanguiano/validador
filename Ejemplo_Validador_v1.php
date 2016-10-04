<!DOCTYPE HTML> 
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body> 

<?php
require('../ddd/clases/usuario.class.php');
//$usuario = new usuario;
//error_reporting(0);  // Desactivar toda notificación de error
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ALL ^ E_NOTICE);

// variables de error
$nameErr = $emailErr = $sexoErr = $websiteErr = $telErr = $fechaErr = $observErr = "";
$name    = $email    = $sexo    = $website    = $tel    = $fecha    = $observ    = "";

function Validador($arrCampos, $arrReglas){
  // Requisitos : 
  // require('../ddd/clases/usuario.class.php');
  //    Incluir la(s) clase(ses) del modelo 
  //    que se usara para buscar el dato unico
  // y en unique:{param} 
  //    param: debe ser el nombre de la clase
  // "name" => "requerido|alfanumerico|unico:usuario",
  // ejecutara usuario->unico(name) 
  // busca si name es unico en la tabla usuario
  
  $reglaSimple[1]="";
  $Mensajes = [
    "requerido"    => "Campo requerido. ",
    "alfa"         => "Solo letras de la a a la z son permitidos. ",
    "alfanumerico" => "Solo letras de la a a la z y numeros son permitidos. ",
    "bool"         => "Se requiere un dato booleano. ",
    "email"        => "Se requiere un email. ",
    "en"           => "Debe ser alguno de '$reglaSimple[1]'. ",
    "entero"       => "Se requiere un número entero. ",
    "existe"       => "El dato no existe. ",
    "fecha"        => "Se requiere una fecha aaaa-mm-dd (2016-12-31). ",
    "float"        => "Se requiere un valor con punto flotante (10.01, 1e7). ",
    "max"          => "Se requiere un valor numérico de $reglaSimple[1] como maximo. ",
    "min"          => "Se requiere un valor numérico de $reglaSimple[1] como minimo. ",
    "tel"          => "Formato valido =>  (032)555-5555. ",
    "texto"        => "Caracteres permitidos: +&@#\/%?=~_|!:,.; ",
    "unico"        => "El dato ya existe. ",
    "www"          => "Formato valido =>  http => //www.site.com/directorio/archivo.ext ",
   ];

	echo "<pre>";
  var_dump($arrCampos); echo "<br>";
  var_dump($arrReglas); echo "<br><br>";

  // Se inicializan los campos de error para retornar el aviso
  // porque salta el aviso NOTICE de php
  foreach ($arrCampos as $NomCampo => $value) {
    $campoError= $NomCampo . "Err";
    $arrCampos[$campoError] = "";  
  }

	foreach ($arrReglas as $campo => $restricciones) {
		$reglas = explode("|", $restricciones);
    echo "<br><br>campo=$campo<br>";
		foreach ($reglas as $reglaKey => $reglaCompleta) {
      echo "reglaCompleta=$reglaCompleta<br>";
      $reglaSimple = explode(':', $reglaCompleta);
      $Error="";
      if (empty($arrCampos[$campo]) and $reglaSimple[0] == 'requerido') {// si esta vacio y fue requerido
        $Error = $Mensajes["requerido"];
      }
      else{
  			switch ($reglaSimple[0]) {// si no tuviera ninguna regla aun asi se limpia el campo en el default de este switch
  				case 'alfa':
              if (!preg_match("/^[a-zA-Z]*$/",$arrCampos[$campo])) {
                $Error = $Mensajes["alfa"];
              }
              break;
          case 'alfanumerico':
              if (!preg_match("/^[a-zA-Z0-9]*$/",$arrCampos[$campo])) {
                $Error = $Mensajes["alfanumerico"];
              }
              break;
          case 'bool':
              if (!is_bool($arrCampos[$campo])) {
                $Error = $Mensajes["bool"];
              }
              break;
          case 'email':
              if (!preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $arrCampos[$campo])) {
                $Error = $Mensajes["email"];
              }
              break;
          case 'en':
              if ( !empty($arrCampos[$campo]) ) {
                if ( stristr($reglaSimple[1], $arrCampos[$campo]) === FALSE ){
                  $Error = $Mensajes["en"];
                }
              }
              break;
          case 'entero':
              if (!preg_match("/^[0-9]*$/",$arrCampos[$campo]) and $arrCampos[$campo] < 65) {
                $Error = $Mensajes["entero"];
              }
              break;
          case 'existe':
              $obj = new $reglaSimple[1];
              if ( $obj->usuarioUnico($arrCampos[$campo]) ) {
                $Error = $Mensajes["existe"];
              }
              else{
                  // OK. el texto $arrCampos[$campo] ya existe en la tabla $reglaSimple[1]   
              }
              break;
          case 'fecha': 
              if (!preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/",$arrCampos[$campo]) and $arrCampos[$campo] < 65) {
                $Error = $Mensajes["fecha"];
              }
              break;
          case 'float':
              if (!is_float($arrCampos[$campo])) {
                $Error = $Mensajes["float"];
              }
              break;
          case 'max':
              if (is_numeric($arrCampos[$campo])) {
                if ($arrCampos[$campo] > $reglaSimple[1]) {
                  $Error = $Mensajes["max"];
                }
              }
              else{
                $Error = $Mensajes["max"];
              }
              break;
          case 'min':
              if (is_numeric($arrCampos[$campo])) {
                if ($arrCampos[$campo] < $reglaSimple[1]) {
                  $Error = $Mensajes["min"];
                }              
              }
              else{
                $Error = $Mensajes["min"];
              }
              break;
          case 'tel':
              if (!preg_match('/^(\(?[0-9]{3,3}\)?|[0-9]{3,3}[-. ]?)[ ][0-9]{3,3}[-. ]?[0-9]{4,4}$/', $arrCampos[$campo])) {
                $Error = $Mensajes["tel"];
              }
              break;
          case 'texto':
              if (!preg_match("/^[a-zA-Z0-9 +&@#\/%?=~_|!:,.;]*$/",$arrCampos[$campo])) {
                $Error = $Mensajes["texto"];
              }
              else{
                $arrCampos[$campo] = LimpiarInput($arrCampos[$campo]);
              }
              break;
          case 'unico':
              $obj = new $reglaSimple[1];
              if ( $obj->usuarioUnico($arrCampos[$campo]) ) {
                  // OK. el texto $arrCampos[$campo] SI es unico en la tabla $reglaSimple[1]   
              }
              else{
                $Error = $Mensajes["unico"];
              }
              break;
          case 'www':
              if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arrCampos[$campo])) {
                $Error = $Mensajes["www"];
              }
              break;

          default:
			        $arrCampos[$campo] = LimpiarInput($arrCampos[$campo]); //limpia el dato de caractere extraños ;-)
              break;
        }
      }
      $campoError= $campo . "Err";
      $arrCampos[$campoError] .= $Error;
    }
  }
  echo "<br><br>";
  var_dump($arrCampos);
  echo "<br><br>Fin</pre>";
  return $arrCampos;
}
function LimpiarInput($Dato) {
   $Dato = trim($Dato);
   $Dato = stripslashes($Dato);
   $Dato = htmlspecialchars($Dato);
   return $Dato;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
/// falta in 
   $arrReglas = [
    "name"    => "requerido|alfa|max:20|min:1|unico:usuario",
    "email"   => "requerido|email",
    "tel"     => "requerido|tel",
    "fecha"   => "requerido|fecha",
    "website" => "requerido|en:uno,dos,tres",
    "observ"  => "requerido|alfa",
		"sexo"    => "requerido"
   ];

   $arrCampos = [
		"name"    => $_POST["name"],
    "email"   => $_POST["email"],
    "tel"     => $_POST["tel"],
    "fecha"   => $_POST["fecha"],
    "website" => $_POST["website"],
    "observ"  => $_POST["observ"],
		"sexo"    => $_POST["sexo"],
	//"sexo"  => 0
	];

  $arrCampos = Validador($arrCampos, $arrReglas);
// saca las variables del arreglo a las locales ejem $emailErr   = $arrCampos["emailErr"];
  foreach ($arrCampos as $NomCampo => $value) {
    $$NomCampo = $arrCampos[$NomCampo];
  }


}


?>

<h2>PHP Form Validation Example2</h2>
<p><span class="error">* required field.</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
   Name:  <input type="text" name="name" value="<?php echo $name;?>">    <span class="error"><?php echo $nameErr;?></span>
   <br><br>
   E-mail: <input type="text" name="email" value="<?php echo $email;?>"> <span class="error"><?php echo $emailErr;?></span>
   <br><br>
   Tel:   <input type="text" name="tel" value="<?php echo $tel;?>">      <span class="error"><?php echo $telErr;?></span>
   <br><br>
   Fecha  <input type="date" name="fecha" value="<?php echo $fecha;?>">  <span class="error"><?php echo $fechaErr;?></span>
   <br><br>
   Website: <input type="text" name="website" value="<?php echo $website;?>"><span class="error"><?php echo $websiteErr;?></span>
   <br><br>
   observ: <textarea name="observ" rows="5" cols="40"><?php echo $observ;?></textarea><span class="error"><?php echo $observErr;?></span>
   <br><br>
   sexo:
   <input type="radio" name="sexo" <?php if (isset($sexo) && $sexo=="female") echo "checked";?>  value="female">Female
   <input type="radio" name="sexo" <?php if (isset($sexo) && $sexo=="male")   echo "checked";?>  value="male">Male
   <span class="error">* <?php echo $sexoErr;?></span>
   <br><br>
   <input type="submit" name="submit" value="Enviar"> 
</form>

<?php
echo "<h2>las variables:</h2><br>";
echo "<br>name = $name";
echo "<br>email = $email";
echo "<br>tel = $tel";
echo "<br>fecha = $fecha";
echo "<br>website = $website";
echo "<br>observ = $observ";
echo "<br>sexo = $sexo";
echo "<br>submit = $submit";
?>

</body>
</html>
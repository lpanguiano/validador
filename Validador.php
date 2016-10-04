<?php
class Validador {
	private $reglaSimple;

	private $Mensajes = [
	"requerido"    => "Campo requerido. ",
	"alfa"         => "Solo letras de la a a la z son permitidos. ",
	"alfanumerico" => "Solo letras de la a a la z y numeros son permitidos. ",
	"bool"         => "Se requiere un dato booleano. ",
	"decimal"      => "Se requiere una cantidad numerica. ",
	"email"        => "Se requiere un email. ",
	"en"           => "Debe ser alguno de ",
	"entero"       => "Se requiere un número entero. ",
	"existe"       => "El dato no existe. ",
	"fecha"        => "Se requiere una fecha aaaa-mm-dd (2016-12-31). ",
	"fechahora"    => "Se requiere una fecha aaaa-mm-dd hh-mm-ss (2016-12-31 12:59:59). ",
	"float"        => "Se requiere un valor con punto flotante (10.01, 1e7). ",
	"hora"         => "Se requiere una hora hh-mm-ss (12:59:59). ",
	"id"           => "Id invalido.",
	"max"          => "Se requiere un valor numérico maximo de ",
	"min"          => "Se requiere un valor numérico minimo de ",
	"tel"          => "Formato valido =>  (032)555-5555. ",
	"texto"        => "Caracteres permitidos: +&@#\/%?=~_|!:,.; ",
	"unico"        => "El dato ya existe. ",
	"www"          => "Formato valido =>  http => //www.site.com/directorio/archivo.ext ",
	];

	function __construct() {		        
    }
    
    public function mensajes($MensajesEditados){
    	$this->Mensajes = $MensajesEditados + $this->Mensajes;
    }

    /**
     * Valida los campos s en $arrCampos con las reglas de $arrReglas
     * @method  Certificar
     * @param   [Array]     $arrCampos  [campos a ser ingresados]
     * @param   [Array]     $arrReglas  [reglas de de formato para cada campo]
     * return   [Array]		$arrCampos  [array con los mensajes de error de cada campo]
     */
    
	public function Certificar($arrCampos, $arrReglas){
		$iErrores=0;
		$reglaSimple[1]="";

		// Se inicializan los campos de error para retornar el aviso
		// porque salta el aviso NOTICE de php
		foreach ($arrCampos as $NomCampo => $value) {
			$campoError= $NomCampo . "Err";
			$arrCampos[$campoError] = "";  
		}

		foreach ($arrReglas as $campo => $restricciones) {
			$reglas = explode("|", $restricciones);
			foreach ($reglas as $reglaKey => $reglaCompleta) {
				$reglaSimple = explode(':', $reglaCompleta);
				$Error="";
				if (empty($arrCampos[$campo]) and $reglaSimple[0] == 'requerido') {// si esta vacio y fue requerido
					$Error = $this->Mensajes["requerido"];
				}
	      		else{
		  			switch ($reglaSimple[0]) {// si no tuviera ninguna regla aun asi se limpia el campo en el default de este switch
		  				case 'alfa':
							if (!preg_match("/^[a-zA-Z]*$/",$arrCampos[$campo])) {
								$Error = $this->Mensajes["alfa"];
							}
							break;
						case 'alfanumerico':
							if (!preg_match("/^[a-zA-Z0-9]*$/",$arrCampos[$campo])) {
								$Error = $this->Mensajes["alfanumerico"];
							}
							break;
						case 'bool':
							if (!is_bool($arrCampos[$campo])) {
								$Error = $this->Mensajes["bool"];
							}
							break;
						case 'decimal':
							//if (!preg_match("/^[0-9]+(\.[0-9][0-9]?)?$/",$arrCampos[$campo]) ) {
							if (!preg_match("/^-?\d+(\.\d+)?$/",$arrCampos[$campo]) ) {
								$Error = $this->Mensajes["decimal"];
							}
							break;
						case 'email':
							if (!preg_match("/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/", $arrCampos[$campo])) {
								$Error = $this->Mensajes["email"];
							}
							break;
						case 'en':
							if ( !empty($arrCampos[$campo]) ) {
								if ( stristr($reglaSimple[1], $arrCampos[$campo]) === FALSE ){
								  $Error = $this->Mensajes["en"] . $reglaSimple[1];
								}
							}
						break;
						case 'entero':
							if (!preg_match("/^[0-9]*$/",$arrCampos[$campo]) ) { 
								$Error = $this->Mensajes["entero"];
							}
							break;
						case 'existe':
							$obj = new $reglaSimple[1];
							if ( $obj->usuarioUnico($arrCampos[$campo]) ) {
								$Error = $this->Mensajes["existe"];
							}
							else{
							  // OK. el texto $arrCampos[$campo] ya existe en la tabla $reglaSimple[1]   
							}
							break;
						case 'fecha': 
							if (!preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/",$arrCampos[$campo]) ) { 
								$Error = $this->Mensajes["fecha"];
							}
							break;
						case 'fechahora': 
							if (!preg_match("/^\d{4}-\d{1,2}-\d{1,2} \d{2}:\d{2}:\d{2}$/",$arrCampos[$campo]) ) { 
								$Error = $this->Mensajes["fechahora"];
							}
							break;
						case 'float':
							if (!is_float($arrCampos[$campo])) {
								$Error = $this->Mensajes["float"];
							}
							break;
						case 'hora': 
							if (!preg_match("/^\d{2}:\d{2}:\d{2}$/",$arrCampos[$campo]) ) { 
								$Error = $this->Mensajes["hora"];
							}
							break;
						case 'id':
							if (!preg_match("/^[0-9]*$/",$arrCampos[$campo]) and $arrCampos[$campo] > 0) {
								$Error = $this->Mensajes["id"];
							}
							break;
						case 'max':
							if (is_numeric($arrCampos[$campo])) {
								if ($arrCampos[$campo] > $reglaSimple[1]) {
									$Error = $this->Mensajes["max"] . $reglaSimple[1].".";
								}
							}
							else{
								$Error = $this->Mensajes["max"] . $reglaSimple[1].".";
							}
							break;
						case 'min':
							if (is_numeric($arrCampos[$campo])) {
								if ($arrCampos[$campo] < $reglaSimple[1]) {
								  $Error = $this->Mensajes["min"].".";
								}
							}
							else{
								$Error = $this->Mensajes["min"].".";
							}
							break;
						case 'tel':
							if (!preg_match('/^(\(?[0-9]{3,3}\)?|[0-9]{3,3}[-. ]?)[ ][0-9]{3,3}[-. ]?[0-9]{4,4}$/', $arrCampos[$campo])) {
								$Error = $this->Mensajes["tel"];
							}
							break;
						case 'texto':
							if (!preg_match("/^[a-zA-Z0-9 +&@#\/%?=~_|!:,.;]*$/",$arrCampos[$campo])) {
								$Error = $this->Mensajes["texto"];
							}
							else{
								$arrCampos[$campo] = $this->Purificar($arrCampos[$campo]);
							}
							break;
						case 'unico':
							$obj = new $reglaSimple[1];
							if ( $obj->Unico($arrCampos[$campo]) ) {
							  // OK. el texto $arrCampos[$campo] SI es unico en la tabla $reglaSimple[1]   
							}
							else{
								$Error = $this->Mensajes["unico"];
							}
							break;
						case 'www':
							if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arrCampos[$campo])) {
								$Error = $this->Mensajes["www"];
							}
							break;
						default:
						    $arrCampos[$campo] = $this->Purificar($arrCampos[$campo]); //limpia el dato de caractere extraños ;-)
							break;
					}
	      		}
				$campoError= $campo . "Err";
				$arrCampos[$campoError] .= $Error;
				if ($Error != "") { $iErrores++; }
	    	}
		}
		$arrCampos["Errores"]=$iErrores;// Total de errores entontrados 
		return $arrCampos;
	}

	public function Purificar($Dato) {
	   $Dato = trim($Dato);
	   $Dato = stripslashes($Dato);
	   $Dato = htmlspecialchars($Dato);
	   return $Dato;
	}
	
	public function EliminarErr($Datos){	// Elimina los campos de error 
	    foreach ($Datos as $key => $value) {
	        if ( substr_compare($key,"Err",-3,3) == 0 ) {
	            unset($Datos[$key]);
	        }
	    }
	    unset($Datos['Errores']);
	    return $Datos;
	}

}
?>
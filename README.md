# validador
Valida campos de formulario dependiendo de las reglas pasadas en un array. 

Uso Validador($arrCampos, $arrReglas)

Usod etallado

//reglas que debe tener cada campo
$arrReglas = [
    "name"    => "requerido|alfa|max:20|min:1|unico:usuario",
    "email"   => "requerido|email",
    "tel"     => "requerido|tel",
    "fecha"   => "requerido|fecha",
    "website" => "requerido|en:uno,dos,tres",
    "observ"  => "requerido|alfa",
		"sexo"    => "requerido"
   ];

// se deben pasar a un array los campos que seran validados
// el nombre de cada campo debe coincidir con su respectiva regla (en $arrRegla[])
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

// y asi se envia los campos y las reglas al validador
  $arrCampos = Validador($arrCampos, $arrReglas);
  
// por ultimo hay que sacar las variables de error
// (si el campo no coincide con la regla, se genera una variable del mismo nombre que el campo con terminacion "Err"
// que muestra cuales reglas no pasaron la validacion.
// estas nuevas variables ...Err deben se sacarse del arrCampos para mostrarlas en el HTML)
// para saca las variables del arreglo a las locales ejem $emailErr   = $arrCampos["emailErr"];
// hacer:

   foreach ($arrCampos as $NomCampo => $value) {
    $$NomCampo = $arrCampos[$NomCampo];
  }


// y ya se podran usar las variables del error en el html

...
<style>.error {color: #FF0000;}</style>
...
<body>
...
Name:   <input type="text" name="name"  value="<?php echo $name;?>">  <span class="error"><?php echo $nameErr;?></span>
E-mail: <input type="text" name="email" value="<?php echo $email;?>"> <span class="error"><?php echo $emailErr;?></span>
...


  
  

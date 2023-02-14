<?php 
session_start(); /// initialize session 
global $_SESSION, $USERS;

if ($_SESSION["logged"]=='')
{
	require '../themes/'.$tema.'/error.php';
}

require '../e-config.php';

$con = mysql_pconnect("$dbhost", "$dbuser", "$dbpassword");
if (!$con)
  {
  echo '<div id="consola">No es posible conectarse a la base de datos</div>';
  }
mysql_select_db("$dbname", $con);
mysql_set_charset('utf8',$con);

$sql="SELECT * FROM `usuarios` WHERE `usuario` = '$_SESSION[logged]' LIMIT 1";
$result = mysql_query($sql);
$row=mysql_fetch_array($result);
mysql_free_result($result);

if  ($row[tipo]==0) { header("Location: ../index.php"); }

else if ($row[tipo]!=0) : ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />

  <title>Panel de administración | make-training | Avon Manufactura Celaya</title>
  <meta name="author" content="avon" />
  <link rel="icon" type="image/vnd.microsoft.icon" href="../favicon.ico" />
  <link type="text/css" href="admin.css" rel="stylesheet" media="screen" />
</head>

<body>
<div id="wrap">
<h1>Subir un archivo a mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <small>(<a href="/make-training" target="_blank">Ver sitio</a>)</small></h1>
<img src="<?php $avatar='../users/'.$_SESSION[logged].'.jpg'; if (file_exists($avatar)) echo $avatar; else echo '/make-training/themes/default/images/default.png'; ?>" alt="avatar" width="32" height="32" class="alignleft" /> <p>Bienvenido, <br /><?php $sqlu="SELECT * FROM `usuarios` WHERE `usuario` = '$_SESSION[logged]' LIMIT 1";
$resultu = mysql_query($sqlu);
$rowu=mysql_fetch_array($resultu); echo $rowu[nombre]." ".$rowu[apellido]; mysql_free_result($resultu); ?> (<a href="../logout.php">Cerrar sesión</a>)</p><br />
<?php
echo '<a href="post.php?curso='.$_POST[curso].'&amp;action=editar">← Regresar</a>';
$target_path = "C:/xampp/htdocs/make-training/material/";

$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);
$var=basename($_FILES['uploadedfile']['name']);
$date=time();
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "<h2>El archivo <strong>".basename( $_FILES['uploadedfile']['name'])."<strong> se transfirio exitosamente.";
    mysql_query("INSERT INTO `elearning`.`material` (
`ID`,`nombre`,`descripcion`,`curso`,`fecha`) VALUES (NULL , '$var','$_POST[descripcion]','$_POST[curso]','$date');");
    }
    else{
    echo "Hubo un error al subir el archivo, por favor intenta de nuevo!";
}
 ?></div>
<!-- footer -->
        <div id="footer">
              <p class="aligncenter">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <?php echo $ver; ?> &copy; <?php echo date('o'); ?> Avon Planta Celaya Manufactura. Arquitectura del sistema por Ivan Barajas</p>
        </div><!-- /footer -->
</div><!--/#wrap-->

</body>
</html>
<?php endif; ?>
<?php 
session_start(); /// initialize session 
global $_SESSION, $USERS;
setlocale(LC_TIME, "esm");

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
<h1>Editar un archivo de mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <small>(<a href="/make-training" target="_blank">Ver sitio</a>)</small></h1>
<img src="<?php $avatar='../users/'.$_SESSION[logged].'.jpg'; if (file_exists($avatar)) echo $avatar; else echo '/make-training/themes/default/images/default.png'; ?>" alt="avatar" width="32" height="32" class="alignleft" /> <p>Bienvenido, <br /><?php $sqlu="SELECT * FROM `usuarios` WHERE `usuario` = '$_SESSION[logged]' LIMIT 1";
$resultu = mysql_query($sqlu);
$rowu=mysql_fetch_array($resultu); echo $rowu[nombre]." ".$rowu[apellido]; mysql_free_result($resultu); ?> (<a href="../logout.php">Cerrar sesión</a>)</p><br />

<a href="index.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a><a href="breviarios.php?action=upload" title="+ Subir un nuevo archivo"><img src="../themes/simplicio/notification_add.png" width="48" height="48" /></a><br />

<?php if ($_GET[action]==='editar') {
	$sql="SELECT * FROM `elearning`.`material` WHERE `material`.`ID` = '$_GET[id]' LIMIT 1";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	
	if (substr($row[nombre],-4) === ".jpg" || substr($row[nombre],-4) === ".JPG") $img=jpg;
	else if (substr($row[nombre],-4) === ".pdf" || substr($row[nombre],-4) === ".PDF") $img=pdf;
	else if (substr($row[nombre],-4) === ".avi" || substr($row[nombre],-4) === ".AVI") $img=mpg;
	else if (substr($row[nombre],-4) === ".mpg" || substr($row[nombre],-4) === ".MPG") $img=mpg;
	else if (substr($row[nombre],-4) === ".wmv" || substr($row[nombre],-4) === ".WMV") $img=mpg;
	else if (substr($row[nombre],-4) === ".ppt" || substr($row[nombre],-4) === ".PPT" || substr($row[nombre],-5) === ".pptx" || substr($row[nombre],-4) === ".PPTX" || substr($row[nombre],-5) === ".pps" || substr($row[nombre],-4) === ".PPS" || substr($row[nombre],-5) === ".ppsx" || substr($row[nombre],-5) === ".PPSX") $img=ppt;
	else if (substr($row[nombre],-4) === ".htm" || substr($row[nombre],-4) === ".HTM") $img=ppt;
	else if (substr($row[nombre],-4) === ".mp4" || substr($row[nombre],-4) === ".MP4" || substr($row[nombre],-4) === ".flv" || substr($row[nombre],-4) === ".FLV") $img=mpg;
	  
	else $img=doc;
	  
		echo '<img src="/make-training/themes/default/images/ext/'.$img.'.png" alt="'.$img.'" width="64" height="64" /><h2 class="title">'.$row[nombre].'</h2><form action="breviarios.php" method="POST">
		<input type="hidden" name="id" value="'.$_GET[id].'" />
		<input type="hidden" name="action" value="edit-do" />
<strong>Descripción</strong>: <textarea name="descripcion" rows="10" cols="40">'.$row[descripcion].'</textarea><br />
<input type="submit" value="Guardar" />
</form>';
}

else if ($_POST[action]==='edit-do')
{
	$sql="SELECT * FROM `elearning`.`material` WHERE `material`.`ID` = '$_POST[id]' LIMIT 1";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	
	if ($_POST[descripcion]!=$row[descripcion]) {
	mysql_query("UPDATE `elearning`.`material` SET `descripcion` = '$_POST[descripcion]' WHERE `material`.`ID` ='$_POST[id]'");
	echo '<p>Se actualizo el material correctamente</p>';
	}
}

else if($_GET[action]==='upload') {
	echo '<h3>Subir un nuevo archivo…</h3>
	<form enctype="multipart/form-data" action="breviarios.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="128000000" />
<input type="hidden" name="action" value="upload-do" />
Elige el archivo a subir: <input name="uploadedfile" type="file" /><br />
<strong>Descripción</strong>: <textarea name="descripcion" rows="10" cols="40"></textarea><br />
<input type="submit" value="Subir" />
</form>
<p class="media-upload-size">Tamaño máximo de subida de archivos: 128MB</p>
<p class="upload-flash-bypass">Estás usando la subida de archivos mediante el navegador</a>.</p>';
}

else if($_POST[action]==='upload-do') {
	$target_path = "C:/xampp/htdocs/make-training/material/";

$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);
$var=basename($_FILES['uploadedfile']['name']);
$date=time();
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo "<h2>El archivo <strong>".basename( $_FILES['uploadedfile']['name'])."</strong> se transfirio exitosamente.";
    mysql_query("INSERT INTO `elearning`.`material` (
`ID`,`nombre`,`descripcion`,`curso`,`fecha`) VALUES (NULL , '$var','$_POST[descripcion]','2','$date');");
    }
    else echo "Hubo un error al subir el archivo, por favor intenta de nuevo!";
}

else if ($_GET[action]==='borrar') {
	mysql_query("DELETE FROM `elearning`.`material` WHERE `material`.`ID` = '$_GET[id]'");
	echo '<h2 class="title">Se ha borrado el material correctamente.</h2>';
}

?>
        
<div id="footer">
              <p class="aligncenter">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <?php echo $ver; ?> &copy; <?php echo date('o'); ?> Avon Planta Celaya Manufactura. Arquitectura del sistema por Ivan Barajas</p>
        </div><!-- /footer -->
</div><!--/#wrap-->
</body>
</html>
<?php endif; ?>
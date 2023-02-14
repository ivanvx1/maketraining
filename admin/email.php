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
<h1>Notificación por correo electronico de curso de mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training</h1>
<br />

<?php if ($_GET[action]==='notif-curso') {
	$sql="SELECT * FROM cursos WHERE ID = '$_GET[curso]' LIMIT 1";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	printf('<strong>Curso</strong>: %s (%s).',$row[nombre],$row[clave]);
	
	echo '<form action="email.php" method="post">
<input type="checkbox" name="almacen" value="1" checked />Almacén<br />
<input type="checkbox" name="procesos" value="1" checked />Procesos<br />
<input type="checkbox" name="envasado" value="1" checked />Envasado<br />
<input type="checkbox" name="mantenimiento" value="1" checked />Mantenimiento<br />
<input type="checkbox" name="ci" value="1" checked />Mejora Continua<br />
<input type="checkbox" name="admin" value="1" checked />Administración Manufactura<br />
<hr />
	<input type="hidden" id="action" name="action" value="email-do" />
	<input type="hidden" id="curso" name="curso" value="'.$_GET[curso].'" />
	<input type="submit" id="submit" name="submit" value="Enviar" />
</form>';
}

else if($_POST[action]=='email-do') {
	$sql="SELECT * FROM cursos WHERE ID = '$_POST[curso]' LIMIT 1";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	
// subject
$subject = 'Nuevo curso en make-Training: '.$row[nombre].' ('.$row[clave].')';

// message
$message = '<html>
<head>
  <title>Nuevo curso en make-Training: '.$row[nombre].' ('.$row[clave].')</title>
</head>
<body>
  <h1>Nuevo curso en mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training: '.$row[nombre].' ('.$row[clave].')</h1>
  
  <p><span style="font-family:helvetica,arial,sans-serif; font-size:12px;">Hay un nuevo curso disponible en mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training. Puedes verlo siguiendo este <a href="'.$url.'curso.php?curso='.$row[ID].'">link</a>.</p>
  
  <p>Atte. el equipo de mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training</span></p>
  
 <br />
<p><span style="font-family:helvetica,arial,sans-serif; font-size:10px;">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training '.$ver.'. &copy; '.date('o').'. Avon Planta Celaya Manufactura. Arquitectura del sistema por Iván Barajas.<br />El contenido de los cursos es responsabilidad del coordinador de capacitación de cada área. </p>
<p><span style="color:green;">Cuida el planeta. No imprimas si no lo necesitas.</span></p>

<p>Este un email generado automaticamente por el sistema. No respondas a esta dirección. Si tienes dudas o comentarios, contacta a tu coordinador de capacitación.</p>

<br />
</body>
</html>';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

// multiple recipients
	$to  = 'everardo.perez@avon.com' . ', '; // note the comma
	if ($_POST[almacen]=='1') {
		$sql="SELECT * FROM usuarios WHERE proceso = 'Manufactura' AND area = 'Almacen'";
		$result = mysql_query($sql);
		while($row=mysql_fetch_array($result)) {
			$to .= $row[email] . ', ';
		}
		mysql_free_result($result);
	}
	
	if ($_POST[procesos]=='1') {
		$sql="SELECT * FROM usuarios WHERE proceso = 'Manufactura' AND area = 'Procesos'";
		$result = mysql_query($sql);
		while($row=mysql_fetch_array($result)) {
			$to .= $row[email] . ', ';
		}
		mysql_free_result($result);
	}

	if ($_POST[envasado]=='1') {
		$sql="SELECT * FROM usuarios WHERE proceso = 'Manufactura' AND area = 'Envasado'";
		$result = mysql_query($sql);
		while($row=mysql_fetch_array($result)) {
			$to .= $row[email] . ', ';
		}
		mysql_free_result($result);
	}
	
	if ($_POST[mantenimiento]=='1') {
		$sql="SELECT * FROM usuarios WHERE proceso = 'Manufactura' AND area = 'Mantenimiento'";
		$result = mysql_query($sql);
		while($row=mysql_fetch_array($result)) {
			$to .= $row[email] . ', ';
		}
		mysql_free_result($result);
	}
	
	if ($_POST[ci]=='1') {
		$sql="SELECT * FROM usuarios WHERE proceso = 'Manufactura' AND area = 'Mejora Continua'";
		$result = mysql_query($sql);
		while($row=mysql_fetch_array($result)) {
			$to .= $row[email] . ', ';
		}
		mysql_free_result($result);
	}
	
	if ($_POST[admin]=='1') {
		$sql="SELECT * FROM usuarios WHERE proceso = 'Manufactura' AND area = 'Administración Manufactura'";
		$result = mysql_query($sql);
		while($row=mysql_fetch_array($result)) {
			$to .= $row[email] . ', ';
		}
		mysql_free_result($result);
	}
	
// Mail it
mail($to, $subject, $message, $headers);

echo '<h2>Se ha enviado la notificación a los usuarios correctamente</h2>';
}
?>
        
<div id="footer">
              <p class="aligncenter">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <?php echo $ver; ?> &copy; <?php echo date('o'); ?> Avon Planta Celaya Manufactura. Arquitectura del sistema por Ivan Barajas</p>
        </div><!-- /footer -->
</div><!--/#wrap-->
</body>
</html>
<?php endif; ?>
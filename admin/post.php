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
  <link rel="stylesheet" type="text/css" href="includes/cleditor/jquery.cleditor.css" />
    <script type="text/javascript" src="includes/cleditor/jquery.min.js"></script>
    <script type="text/javascript" src="includes/cleditor/jquery.cleditor.min.js"></script>
	<script language="javascript" src="../includes/calendar/calendar.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $(".input").cleditor();
      });
    </script>
  
  <?php if ($_GET[action]==='editar') { echo'<script language="javascript">
function confirmSubmit()
{
var agree=confirm("¿Estás seguro de eliminar esta pregunta? Esto eliminará también todas las respuestas relacionadas con la pregunta, el proceso es irreversible.");
if (agree)
return true ;
else
return false ;
}
</script>';
}
else if ($_POST[action]==='update-qya') {
	echo '<script type="text/javascript">
<!--
function delayer(){
    window.location = "'.$url.'admin/post.php?curso='.$_POST[curso].'&amp;action=editar"
}
//-->
</script>';}?>
</head>

<body>
<div id="wrap">
<h1>Editar un curso de mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <small>(<a href="/make-training">Ver sitio</a>)</small></h1>
<img src="<?php $avatar='../users/'.$_SESSION[logged].'.jpg'; if (file_exists($avatar)) echo $avatar; else echo '/make-training/themes/default/images/default.png'; ?>" alt="avatar" width="32" height="32" class="alignleft" /> <p>Bienvenido, <br /><?php $sqlu="SELECT * FROM `usuarios` WHERE `usuario` = '$_SESSION[logged]' LIMIT 1";
$resultu = mysql_query($sqlu);
$rowu=mysql_fetch_array($resultu); echo $rowu[nombre]." ".$rowu[apellido]; mysql_free_result($resultu); ?> (<a href="../logout.php">Cerrar sesión</a>)</p><br />

<?php if ($_GET[action]==='editar') {
	$sql="SELECT * FROM cursos WHERE ID = '$_GET[curso]' LIMIT 1";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	$clave=$row[clave];
	mysql_free_result($result);
	$count=1;
			
	echo '<a href="index.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a><a href="post.php?action=add-qya&amp;curso='.$_GET[curso].'" title="+ Agregar una pregunta"><img src="../themes/simplicio/notification_add.png" width="48" height="48" /></a>';
	
	if ($rowu[tipo]=="1" || $rowu[tipo]=="2") echo '<a onclick="window.open(\'email.php?action=notif-curso&amp;curso='.$_GET[curso].'\', \'mywindow\', \'location=1,status=1,scrollbars=1,width=600,height=600\')" title="Enviar notificación por correo electronico"><img src="../themes/simplicio/mail_send.png" width="48" height="48" /></a>';
	
	echo '<div id="sidebar" style="width:30%;" class="alignright">
	<form action="post.php" method="post">
	<strong>Nombre del Curso</strong><br /><input type="text" id="nombre" name="nombre" value="'.$row[nombre].'"/>
	<h3>Clave: '.$clave.'</h3>
	<input type="text" id="clave" name="clave" value="'.$row[clave].'" /><br /><br />';
	if ($rowu[tipo]=="1" || $rowu[tipo]=="2") {$output=str_replace('value="'.$row[proceso].'"','selected value="'.$row[proceso].'"','<strong>Proceso</strong>: <select name="proceso">
	<option value="Manufactura">Manufactura</option>
  <option value="iso14000">ISO-14000</option>
	</select>');
	echo $output; }
	
	else if ($rowu[tipo]=="3") echo '<input type="hidden" name="proceso" value="Soporte" />';
	
	$output=str_replace('value="'.$row[modulo].'"','selected value="'.$row[modulo].'"','<strong>Modulo</strong> <select name="modulo">
	<option value="1">1</option>
  	<option value="2">2</option>
  	<option value="3">3</option>
  	<option value="4">4</option>
  	<option value="5">5</option>
  	<option value="6">6</option>
  	<option value="7">7</option>
  	<option value="e">Especifico</option>
	</select>
	<br />');
	echo $output;
	
	echo '<h3>Fecha de publicación:</h3>';
	require_once('../includes/calendar/classes/tc_calendar.php');
	  $date1_default = strftime('%d-%m-%Y', $row[fecha]);
	  $myCalendar = new tc_calendar("date1", true, false);
	  $myCalendar->setIcon("../includes/calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($date1_default))
            , date('m', strtotime($date1_default))
            , date('Y', strtotime($date1_default)));
	  $myCalendar->setPath("../includes/calendar/");
	  $myCalendar->setYearInterval(2011, 2015);
	  $myCalendar->dateAllow('2011-01-01', '2015-12-31');
	  $myCalendar->setDateFormat('j F Y');
	  $myCalendar->setAlignment('left', 'bottom');
	  $myCalendar->writeScript();
	
	echo '<br /><p><strong>Instrucciones</strong>:</p>
	<textarea class="input" id="instrucciones" name="instrucciones" rows="8" cols="40">'.$row[instrucciones].'</textarea>
	<hr />
	<input type="hidden" id="action" name="action" value="edit-do" />
	<input type="hidden" id="curso" name="curso" value="'.$_GET[curso].'" />
	<input type="submit" id="submit" name="submit" value="Actualizar" />
	</form>
	<br />
	<img src="../themes/simplicio/document_delete.png" width="48" height="48" /> <a onclick="return confirmSubmit()" href="post.php?curso='.$_GET[curso].'&amp;action=borrar" title="Borrar el curso">Borrar el Curso</a> 
	</div><!--/sidebar-->';
	
	echo '<div id="content" style="width:65%;">
	<h2>Preguntas: Curso "'.$row[nombre].'"(<a href="../curso.php?curso='.$row[ID].'">Ver curso</a>)</h2>
	<table>
	<tr>
	<th>no.</th>
	<th>Pregunta</th>
	<th></th>
	</tr>';
	$sql="SELECT * FROM qya WHERE tipo = '0' AND curso = '$_GET[curso]' ORDER BY `qya`.`ID` ASC";
	$result = mysql_query($sql);
	$odd=0;
	while ($row=mysql_fetch_array($result))
	{
	if ($odd==0) echo '<tr>';
		else echo '<tr class="odd">';
	echo '<td>'.$count.'<td><a href="post.php?action=pregunta&amp;id='.$row[ID].'">'.$row[contenido].'</a></td>
	<td><a onclick="return confirmSubmit()" href="post.php?pregunta='.$row[ID].'&amp;action=borrar-qya" title="Borrar la pregunta y sus respuestas">Borrar</a></td>
	</tr>';
	$count++;
	if ($odd==0) $odd=1;
		else $odd=0;
		}
	mysql_free_result($result);
	
	echo '</table>
	<h2>Material de apoyo</h2>';
	
	$sql="SELECT * FROM material WHERE curso = '$_GET[curso]' ORDER BY `material`.`ID` ASC";
	$result = mysql_query($sql);
	$media=0;
	while ($row=mysql_fetch_array($result))
	{
		if (substr($row[nombre],-4) === ".jpg" || substr($row[nombre],-4) === ".JPG") $img=jpg;
		else if (substr($row[nombre],-4) === ".pdf" || substr($row[nombre],-4) === ".PDF") $img=pdf;
		else if (substr($row[nombre],-4) === ".avi" || substr($row[nombre],-4) === ".AVI") $img=mpg;
		else if (substr($row[nombre],-4) === ".mpg" || substr($row[nombre],-4) === ".MPG") $img=mpg;
		else if (substr($row[nombre],-4) === ".wmv" || substr($row[nombre],-4) === ".WMV") $img=mpg;
		else if (substr($row[nombre],-4) === ".ppt" || substr($row[nombre],-4) === ".PPT" || substr($row[nombre],-5) === ".pptx" || substr($row[nombre],-4) === ".PPTX" || substr($row[nombre],-5) === ".pps" || substr($row[nombre],-4) === ".PPS" || substr($row[nombre],-5) === ".ppsx" || substr($row[nombre],-5) === ".PPSX") $img=ppt;
		else if (substr($row[nombre],-4) === ".htm" || substr($row[nombre],-4) === ".HTM") $img=ppt;
		else if (substr($row[nombre],-4) === ".mp4" || substr($row[nombre],-4) === ".MP4" || substr($row[nombre],-4) === ".flv" || substr($row[nombre],-4) === ".FLV") $img=mpg;
	  
	  	else $img=doc;
	  
		echo '<img src="/make-training/themes/default/images/ext/'.$img.'.png" alt="'.$img.'" width="32" height="32" /> <a target="_blank" href="/make-training/material/'.$row[nombre].'">'.$row[nombre].'</a> <a href="post.php?action=borrar-media&amp;id='.$row[ID].'" title="Borrar">X</a> | ';
	}
	
	mysql_free_result($result);
	
	echo '<h3>Subir un nuevo archivo…</h3>
	<form enctype="multipart/form-data" action="upload.php" method="POST">
<input type="hidden" name="MAX_FILE_SIZE" value="128000000" />
<input type="hidden" id="curso" name="curso" value="'.$_GET[curso].'" />
Elige los archivos a subir: <input name="uploadedfile" type="file" /><br />
<input type="submit" value="Subir" />
</form>
<p class="media-upload-size">Tamaño máximo de subida de archivos: 128MB</p>
<p class="upload-flash-bypass">Estás usando la subida de archivos mediante el navegador</a>.</p>
<p class="howto">Después de subir un archivo, puedes agregar el título y la descripcion.</p><br />';
$count=1;
echo '<h3>Usuarios</h3>
<table>
	<tr>
	<th>no.</th>
	<th>Asociado</th>
	<th>Usuario</th>
	<th>Calif</th>
	<th>Fecha</th>
	<th>Examen</th>
	<th>Acción</th>
	</tr>';

	$sqlu="SELECT * FROM `calif` WHERE `curso` = '$_GET[curso]'";
	$resultu = mysql_query($sqlu);
	$odd=0;
	while ($rowu=mysql_fetch_array($resultu))
	{
	if ($odd==0) { echo '<tr>'; $odd=1;}
		else {echo '<tr class="odd">'; $odd=0;}
	$sqla="SELECT * FROM usuarios WHERE id = '$rowu[usuario] LIMIT 1'";
	$resulta=mysql_query($sqla);
	$rowa=mysql_fetch_array($resulta);
	$fecha=strftime('%d/%b/%y %H:%M', $rowu[fecha]);
	
	echo '<tr>
	<td>'.$count.'</td>
	<td>'.$rowa[asociado].'</td>
	<td><a href="'.$url.'admin/user.php?action=editar&id='.$rowa[id].'&usuario='.$rowa[usuario].'" title="Panel de usuario de '.$rowa[nombre].' '.$rowa[apellido].'">'.$rowa[nombre].' '.$rowa[apellido].'</a></td>
	<td>'.$rowu[calif].'</td>
	<td>'.$fecha.'</td>
	<td><a href="'.$url.'examenes/'.$clave.'-'.$rowu[usuario].'.pdf" target="_blank" title="Descargar examen"><img src="'.$url.'themes/default/images/ext/pdf.png" alt="pdf" width="32" height="32" /></a></td>
	<td><a href="post.php?action=quitar-usuario&amp;curso='.$_GET[curso].'&amp;id='.$rowu[id].'" title="Borrar">Brindar nueva oportunidad</a></td>
	</tr>';
$count++;}
echo '</table></div>';

}
else if ($_GET[action]==='add') {

	echo '<a href="index.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>
	<form action="post.php" method="post">
		<ul>
			<li><strong>Nombre del Curso</strong><br /><input type="text" id="nombre" name="nombre" /></li>
			<li><strong>Clave</strong><br /><input type="text" id="clave" name="clave" /></li>
			<li><strong>Instructor</strong><br /><input type="text" id="instructor" name="instructor" /></li>
			<hr />
			
			<li><strong>Instrucciones</strong><br /><textarea class="input" id="instrucciones" name="instrucciones" rows="8" cols="60"></textarea></li>
			';
			
	if ($rowu[tipo]=="1" || $rowu[tipo]=="2") echo '<br /><li><strong>Proceso</strong>: <select name="proceso">
	<option value="Manufactura">Manufactura</option>
  <option value="iso14000">ISO-14000</option>
	</select>
	</li>';
	
	else if ($rowu[tipo]=="3") echo '<input type="hidden" name="proceso" value="Soporte" />';
	
	echo '<br /><li><strong>Modulo</strong> <select name="modulo">
	<option value="1">1</option>
  	<option value="2">2</option>
  	<option value="3">3</option>
  	<option value="4">4</option>
  	<option value="5">5</option>
  	<option value="6">6</option>
  	<option value="7">7</option>
  	<option value="e">Especifico</option>
	</select></li>
	<br />
	<li><strong>Fecha de Publicación</strong><br/>';
	require_once('../includes/calendar/classes/tc_calendar.php');
	$date1_default = strftime('%d-%m-%Y', time());
	  $myCalendar = new tc_calendar("date1", true, false);
	  $myCalendar->setIcon("../includes/calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($date1_default))
            , date('m', strtotime($date1_default))
            , date('Y', strtotime($date1_default)));
	  $myCalendar->setPath("../includes/calendar/");
	  $myCalendar->setYearInterval(2011, 2015);
	  $myCalendar->dateAllow('2011-01-01', '2015-12-31');
	  $myCalendar->setDateFormat('j F Y');
	  $myCalendar->setAlignment('left', 'bottom');
	  $myCalendar->writeScript();
	
	echo '</li><br />	
	</ul>
		<hr />
		
		<input type="hidden" id="action" name="action" value="add-do" />
		<input type="submit" id="submit" name="submit" value="Guardar" />
		</form>';
}

else if ($_POST[action]==='add-do') {

	echo '<a href="post.php?action=add" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>';
	if ($_POST[nombre]=='' || $_POST[clave]=='' || $_POST[instructor]=='' || $_POST[proceso]=='')
		echo '<h2 class="title">Debe llenar todos los campos</h2>';
		
	else {$dateraw=explode("-",$_POST[date1]); $date=mktime(0,0,0,$dateraw[1],$dateraw[2],$dateraw[0]);
	mysql_query("INSERT INTO `elearning`.`cursos` (`id`, `nombre`, `clave`, `modulo`, `fecha`, `instrucciones`, `instructor`, `proceso`) VALUES (NULL,'$_POST[nombre]', '$_POST[clave]', '$_POST[modulo]','$date', '$_POST[instrucciones]', '$_POST[instructor]', '$_POST[proceso]')");
	echo '<h2 class="title">Se ha creado el curso correctamente.</h2>';
			}
}

else if ($_POST[action]==='edit-do') {
		echo '<a href="post.php?action=editar&curso='.$_POST[curso].'" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>';
	if ($_POST[nombre]=='' || $_POST[clave]=='' || $_POST[instrucciones]=='')
		echo '<h2 class="title">Los campos no pueden quedarse vacios</h2>';
		
	else {$sql="SELECT * FROM cursos WHERE ID = $_POST[curso] LIMIT 1";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	mysql_free_result($result); 
	$dateraw=explode("-",$_POST[date1]);
	$date=mktime(0,0,0,$dateraw[1],$dateraw[2],$dateraw[0]);
	
	if ($_POST[nombre]==$row[nombre]) $nombre=$row[nombre];
	else $nombre=$_POST[nombre];
	
	if ($_POST[clave]==$row[clave]) $clave=$row[clave];
	else $clave=$_POST[clave];
	
	if ($_POST[proceso]==$row[proceso]) $proceso=$row[proceso];
	else $proceso=$_POST[proceso];
	
	if ($_POST[modulo]==$row[modulo]) $modulo=$row[modulo];
	else $modulo=$_POST[modulo];
	
	if ($date==$row[fecha]) $date=$row[fecha];
	
	if ($_POST[instrucciones]==$row[instrucciones]) $instrucciones=$row[instrucciones];
	else $instrucciones=$_POST[instrucciones];
	
	mysql_query("UPDATE `elearning`.`cursos` SET `nombre` = '$nombre', `clave` = '$clave', `proceso` = '$proceso', `modulo` = '$modulo', `fecha` = '$date', `instrucciones` = '$instrucciones' WHERE `cursos`.`ID` ='$_POST[curso]'");
	
	echo '<h2 class="title">Se ha editado el curso correctamente.</h2>';
			}
	
}

else if ($_GET[action]==='borrar') {

	echo '<a href="index.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>';
	
	mysql_query("DELETE FROM `elearning`.`cursos` WHERE `cursos`.`ID` = '$_GET[curso]'");
	mysql_query("DELETE FROM `elearning`.`material` WHERE `curso`.`ID` = '$_GET[curso]'");
	mysql_query("DELETE FROM `elearning`.`qya` WHERE `qya`.`curso` = '$_GET[curso]'");
	echo '<h2 class="title">Se ha borrado el curso correctamente.</h2>';
}

else if ($_GET[action]==='borrar-media') {

	echo '<a href="index.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>';
	
	mysql_query("DELETE FROM `elearning`.`material` WHERE `material`.`ID` = '$_GET[id]'");
	echo '<h2 class="title">Se ha borrado el material correctamente.</h2>';
}

else if ($_GET[action]==='quitar-usuario') {

	echo '<a href="post.php?action=editar&amp;curso='.$_GET[curso].'" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>';
	
	mysql_query("DELETE FROM `elearning`.`calif` WHERE `calif`.`id` = '$_GET[id]'");
	
	echo '<h2 class="title">Se ha dado una nueva oportunidad correctamente.</h2>';
}

else if ($_GET[action]==='borrar-qya') {

	echo '<a href="index.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>';
	
	mysql_query("DELETE FROM `elearning`.`qya` WHERE `qya`.`ID` = '$_GET[pregunta]'");
	mysql_query("DELETE FROM `elearning`.`qya` WHERE `qya`.`pregunta` = '$_GET[pregunta]'");
	echo '<h2 class="title">Se ha borrado la pregunta y sus respuestas correctamente.</h2>';
}

else if ($_GET[action]==='add-qya') {
echo '<a href="post.php?curso='.$_GET[curso].'&amp;action=editar"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>

<h2>Pregunta</h2>
<form action="post.php" method="post">
<textarea class="input" id="pregunta" name="pregunta" rows="8" cols="60" ></textarea><hr />

<h2>Respuestas</h2>

<input type="radio" name="check" value="0" />
<textarea id="respuesta1" name="a0"></textarea>
<input type="radio" name="check" value="1" />
<textarea id="respuesta2" name="a1"></textarea>
<input type="radio" name="check" value="2" />
<textarea id="respuesta3" name="a2"></textarea>
<input type="radio" name="check" value="3" />
<textarea id="respuesta4" name="a3"></textarea><hr />

<h2>Valor</h2>
<input type="text" id="valor" name="valor" value="" /> <hr />

<input type="hidden" id="action" name="action" value="add-qya-do" />
<input type="hidden" id="curso" name="curso" value="'.$_GET[curso].'" />
		<input type="submit" id="submit" name="submit" value="Guardar" />
		</form>';

}

else if ($_POST[action]==='add-qya-do') {
	echo '<a href="post.php?action=editar&amp;curso='.$_POST[curso].'" title="Regresar al curso"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a><a href="post.php?action=add-qya&amp;curso='.$_POST[curso].'" title="+ Agregar una pregunta"><img src="../themes/hd/bullet_add.png" width="48" height="48" /></a>';
	
	if ($_POST[pregunta]!='') { mysql_query("INSERT INTO `elearning`.`qya` (`ID`, `contenido`, `tipo`, `curso`, `pregunta`, `check`, `valor`) VALUES (NULL,'$_POST[pregunta]', '0', '$_POST[curso]', '0', '0', '0')");
		echo '<h2 class="title">Se ha creado la pregunta "'.$_POST[pregunta].'" correctamente.</h2>';
		$qID=mysql_insert_id();
		
		$i=0;
		while ($i <= 3)
		{
			if ($i==0) $var=$_POST[a0];
			else if ($i==1) $var=$_POST[a1];
			else if ($i==2) $var=$_POST[a2];
			else if ($i==3) $var=$_POST[a3];
			
			if ($var!='') {
				if ($i==$_POST[check]) mysql_query("INSERT INTO `elearning`.`qya` (`ID`, `contenido`, `tipo`, `curso`, `pregunta`, `check`, `valor`) VALUES (NULL,'$var', '1', '$_POST[curso]', '$qID', '1', '$_POST[valor]')");
				else mysql_query("INSERT INTO `elearning`.`qya` (`ID`, `contenido`, `tipo`, `curso`, `pregunta`, `check`, `valor`) VALUES (NULL,'$var', '1', '$_POST[curso]', '$qID', '0', '0')");
				echo '<h2><strong>Respuesta</strong>: "'.$var.'" agregada.</h2>';
			}
			
			$i++;
		}
}

else echo '<h2 class="title">Las preguntas no puedes ir vacias.</h2>';

}

else if ($_GET[action]==='pregunta') {
	$sql="SELECT * FROM qya WHERE id = $_GET[id] LIMIT 1";
	$result = mysql_query($sql);
	$rowp=mysql_fetch_array($result);
	mysql_free_result($result);
	
	echo '<a href="post.php?curso='.$rowp[curso].'&amp;action=editar"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>
	
	<h2>Editar Pregunta: '.$rowp[contenido].'</h2>';
	
	$sql="SELECT * FROM qya WHERE tipo = '1' AND pregunta = '$_GET[id]' ORDER BY `qya`.`ID` ASC";
	$result = mysql_query($sql);
	
	echo '<form action="post.php" method="post">';
	echo '<textarea id="pregunta-'.$rowp[ID].'" name="pregunta" rows="8" cols="60" >'.$rowp[contenido].'</textarea>
	<input type="hidden" id="pregunta-id" name="qid" value="'.$rowp[ID].'" /><hr />
	<input type="hidden" id="pregunta-original" name="qorig" value="'.$rowp[contenido].'" />
	<h2>Respuestas</h2>';
	$i=0;
	while ($row=mysql_fetch_array($result))
		{
			echo "\n";
			echo '<textarea id="respuesta-'.$row[ID].'" name="a'.$i.'" >'.$row[contenido].'</textarea><input type="hidden" id="o'.$i.'" name="o'.$i.'" value="'.$row[ID].'" /><input type="hidden" id="e'.$i.'" name="e'.$i.'" value="'.$row[contenido].'" />';
			$i++;
		}
	mysql_free_result($result);
	
	$sql="SELECT * FROM qya WHERE tipo = '1' AND pregunta = '$_GET[id]' ORDER BY `qya`.`ID` ASC";
	$result = mysql_query($sql);
	$i=0;
	echo '<br />';
	while ($row=mysql_fetch_array($result))
		{
			echo '<input type="radio" name="check" value="'.$row[ID].'"';
			if ($row[check]==1) echo 'checked';
			echo '>';
			$i++;
			echo $i;
			if ($row[check]==1) {
			echo '<img src="../themes/simplicio/notification_done.png" width="48" height="48" />';
			$check=$row[ID];}
		}
	mysql_free_result($result);
	echo '	<hr />
			<input type="hidden" id="action" name="action" value="update-qya" />
			<input type="hidden" id="check" name="checkold" value="'.$check.'" />
			<input type="hidden" id="curso" name="curso" value="'.$rowp[curso].'" />
			<input type="submit" id="submit" name="submit" value="Guardar" />
			</form>';
}

else if ($_POST[action]==='update-qya') {
	
	if ($_POST[pregunta]!==''  && $_POST[pregunta]!==$_POST[qorig]) { mysql_query("UPDATE `elearning`.`qya` SET `contenido` = '$_POST[pregunta]' WHERE `qya`.`ID` =$_POST[qid]");
				echo '<h2><strong>Pregunta</strong>: "'.$_POST[qorig].'" actualizada por "'.$_POST[pregunta].'".</strong></h2>'; }
	
	if ($_POST[a0]!=='' && $_POST[a0]!==$_POST[e0]) { mysql_query("UPDATE `elearning`.`qya` SET `contenido` = '$_POST[a0]' WHERE `qya`.`ID` =$_POST[o0]");
				echo '<h2><strong>Respuesta</strong>: "'.$_POST[e0].'" actualizada por "'.$_POST[a0].'".</h2>'; }
				
	if ($_POST[a1]!=='' && $_POST[a1]!==$_POST[e1]) { mysql_query("UPDATE `elearning`.`qya` SET `contenido` = '$_POST[a1]' WHERE `qya`.`ID` =$_POST[o1]");
				echo '<h2><strong>Respuesta</strong>: "'.$_POST[e1].'" actualizada por "'.$_POST[a1].'".</h2>'; }
				
	if ($_POST[a2]!=='' && $_POST[a2]!==$_POST[e2]) { mysql_query("UPDATE `elearning`.`qya` SET `contenido` = '$_POST[a2]' WHERE `qya`.`ID` =$_POST[o2]");
				echo '<h2><strong>Respuesta</strong>: "'.$_POST[e2].'" actualizada por "'.$_POST[a2].'".</h2>'; }
				
	if ($_POST[a3]!=='' && $_POST[a3]!==$_POST[e3]) { mysql_query("UPDATE `elearning`.`qya` SET `contenido` = '$_POST[a3]' WHERE `qya`.`ID` =$_POST[o3]");
				echo '<h2><strong>Respuesta</strong>: "'.$_POST[e3].'" actualizada por "'.$_POST[a3].'".</h2>'; }
				
	if ($_POST[check]!=$_POST[checkold]) {
		mysql_query("UPDATE `elearning`.`qya` SET `check` = '1', `valor` = '10' WHERE `qya`.`ID` =$_POST[check]");
		mysql_query("UPDATE `elearning`.`qya` SET `check` = '0', `valor` = '0' WHERE `qya`.`ID` =$_POST[checkold]");
				echo '<h2><strong>Respuesta</strong>: "'.$_POST[e2].'" actualizada por "'.$_POST[a2].'".</h2>'; 
	}
	
	else echo "<h2>Ningun Cambio realizado.</h2>";
	
	echo '<div onLoad="setTimeout(\'delayer()\',2000)"><p>Espera un momento para regresar. Si no deseas esperar <a href="'.$url.'admin/post.php?curso='.$_POST[curso].'&amp;action=editar">haz click aqui</a>.</p></div>';
}

else if ($_POST[action]==='user-do') {
	
	$sql="SELECT * FROM `usuarios` WHERE `id` = '$_POST[id]' LIMIT 1";
	$result = mysql_query($sql);
	$row=mysql_fetch_array($result);
	mysql_free_result($result);
	
	if ($_POST[asociado]!=='' && $_POST[asociado]!==$row[asociado]) { mysql_query("UPDATE `elearning`.`usuarios` SET `asociado` = '$_POST[asociado]' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>No. de asociado cambiado de: <strong>"'.$row[asociado].'"</strong> por <strong>"'.$_POST[asociado].'".</strong></h2>'; }
	
	if ($_POST[nombre]!=='' && $_POST[nombre]!==$row[nombre]) { mysql_query("UPDATE `elearning`.`usuarios` SET `nombre` = '$_POST[nombre]' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>Nombre cambiado de: <strong>"'.$row[nombre].'"</strong> por <strong>"'.$_POST[nombre].'".</strong></h2>'; }
	
	if ($_POST[apellido]!=='' && $_POST[apellido]!==$row[apellido]) { mysql_query("UPDATE `elearning`.`usuarios` SET `apellido` = '$_POST[apellido]' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>Apellidos cambiados de: <strong>"'.$row[apellido].'"</strong> por <strong>"'.$_POST[apellido].'".</strong></h2>'; }
				
	if ($_POST[email]!=='' && $_POST[email]!==$row[email]) { mysql_query("UPDATE `elearning`.`usuarios` SET `email` = '$_POST[email]' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>Email cambiado de: <strong>"'.$row[email].'"</strong> por <strong>"'.$_POST[email].'".</strong></h2>'; }
				
	if ($_POST[proceso]!=='' && $_POST[proceso]!==$row[proceso]) { mysql_query("UPDATE `elearning`.`usuarios` SET `proceso` = '$_POST[proceso]' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>Proceso cambiado de: <strong>"'.$row[proceso].'"</strong> por <strong>"'.$_POST[proceso].'".</strong></h2>'; }
				
	if ($_POST[area]!=='' && $_POST[area]!==$row[area]) { mysql_query("UPDATE `elearning`.`usuarios` SET `area` = '$_POST[area]' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>Área cambiada de: <strong>"'.$row[area].'"</strong> por <strong>"'.$_POST[area].'".</strong></h2>'; }
				
	if ($_POST[genesis]!=='' && $_POST[genesis]!==$row[genesis]) { mysql_query("UPDATE `elearning`.`usuarios` SET `genesis` = '$_POST[genesis]' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>Cambiado Acceso a genesis</h2>'; }
				
	if ($_POST[pass1]!=='' && $_POST[pass1]==$_POST[pass2]) { $password=md5($_POST[pass1]); mysql_query("UPDATE `elearning`.`usuarios` SET `password` = '$password' WHERE `usuarios`.`id` =$_POST[id]");
				echo '<h2>Password cambiado</h2>'; }
	
	echo '<div onLoad="setTimeout(\'delayer()\',2000)"><p>Espera un momento para regresar. Si no deseas esperar <a href="'.$url.'admin/user.php?action=editar&id='.$row[id].'&usuario='.$row[usuario].'">haz click aqui</a>.</p></div>';
}

?>
        
<div id="footer">
              <p class="aligncenter">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <?php echo $ver; ?> &copy; <?php echo date('o'); ?> Avon Planta Celaya Manufactura. Arquitectura del sistema por Ivan Barajas</p>
        </div><!-- /footer -->
</div><!--/#wrap-->
</body>
</html>
<?php endif; ?>
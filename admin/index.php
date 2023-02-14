<?php 
session_start(); /// initialize session 
global $_SESSION, $USERS;
setlocale(LC_TIME, "esm"); 

if ($_SESSION["logged"]=='')
{
	header("Location: ../index.php");
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
<script language="javascript">
function confirmSubmit()
{
var agree=confirm("¿Estás seguro de eliminar este curso? Esto borrar todas las preguntas y el material relacionado con el curso. Este proceso es irreversible.");
if (agree)
return true ;
else
return false ;
}
</script>
</head>

<body>
<div id="wrap">
<h1>Administración de mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <small>(<a href="/make-training">Ver sitio</a>)</small></h1>
<img src="<?php $avatar='../users/'.$_SESSION[logged].'.jpg'; if (file_exists($avatar)) echo $avatar; else echo '/make-training/themes/default/images/default.png'; ?>" alt="avatar" width="32" height="32" class="alignleft" /> <p>Bienvenido, <br /><?php $sqlu="SELECT * FROM `usuarios` WHERE `usuario` = '$_SESSION[logged]' LIMIT 1";
$resultu = mysql_query($sqlu);
$rowu=mysql_fetch_array($resultu); echo $rowu[nombre]." ".$rowu[apellido]; mysql_free_result($resultu); ?> (<a href="../logout.php">Cerrar sesión</a>)</p><br />

<div id="users">
<h2>Usuarios</h2>
<img src="../themes/simplicio/user_manage.png" width="64" height="64" title="Ir al control de usuarios" /> <a href="users.php">Administración de usuarios</a>
</div>

<?php if ($row[tipo]!=0) {
	if ($_GET[orden]=='') $_GET[orden]='ASC';
	if ($_GET[filtro]=='') $_GET[filtro]='fecha';
	if ($row[tipo]==1 || $row[tipo]==2 || $row[tipo]==3) {
	$output=str_replace('filtro="'.$_GET[filtro].'&amp;orden='.$_GET[orden].'"','filtro="'.$_GET[filtro].'&amp;orden='.$_GET[orden].'"','<div id="cursos">
	<h2>Cursos</h2>
	<a href="post.php?action=add" title="+ Crear un nuevo curso"><img src="../themes/simplicio/folder_add.png" width="48" height="48" /></a><br />
	<table>
	<tr class="ui">
	<th><a href="index.php?filtro=nombre&amp;orden=ASC">Curso</a></th>
	<th><a href="index.php?filtro=clave&amp;orden=ASC">Clave</a></th>
	<th><a href="index.php?filtro=fecha&amp;orden=ASC">Fecha</a></th>
	<th><a href="index.php?filtro=modulo&amp;orden=ASC">Modulo</a></th>
	<th><a href="index.php?filtro=proceso&amp;orden=ASC">Proceso</a></th>
	<th></th>
	</tr>');
	echo $output;
		
	if ($row[tipo]==1) $sqlcursos=sprintf("SELECT * FROM `cursos` ORDER BY `cursos`.`%s` %s",$_GET[filtro],$_GET[orden]);	
	else if ($row[tipo]==1 || $row[tipo]==2) $sqlcursos=sprintf("SELECT * FROM `cursos` WHERE `proceso` != 'Soporte' ORDER BY `cursos`.`%s` %s",$_GET[filtro],$_GET[orden]);
	else if ($row[tipo]==3) $sqlcursos=sprintf("SELECT * FROM `cursos` WHERE `proceso` = 'Soporte' ORDER BY `cursos`.`%s` %s",$_GET[filtro],$_GET[orden]);
	$resultcursos=mysql_query($sqlcursos);
	$odd=0;
	while($cursos=mysql_fetch_array($resultcursos))
	{
		if ($odd==0) echo '<tr>';
		else echo '<tr class="odd">';
		if($cursos[modulo]=='e') $modulo='Especifico';
		else $modulo=$cursos[modulo]+1;
		echo '<td><img src="../themes/simplicio/document.png" width="32" height="32" /><a href="post.php?curso='.$cursos[ID].'&amp;action=editar" title="Editar">'.$cursos[nombre].'</a></td>
		<td>'.$cursos[clave].'</td>
		<td>'.strftime('%d de %b de %Y', $cursos[fecha]).'</td>
		<td>'.$modulo.'</td>
		<td>'.$cursos[proceso].'</td>
		<td><a onclick="return confirmSubmit()" href="post.php?curso='.$cursos[ID].'&amp;action=borrar" title="Borrar el curso"><img src="../themes/simplicio/document_delete.png" width="32" height="32" /></a></td>';
		echo '</tr>';
		if ($odd==0) $odd=1;
		else $odd=0;
	}
mysql_free_result($resultcursos);
echo '</table><br /></div>'; }

	if ($row[tipo]==1 || $row[tipo]==2)
	{
	$sqlcursos="SELECT * FROM `material` WHERE `curso`='2' ORDER BY `material`.`id` ASC";
	$resultcursos=mysql_query($sqlcursos);
	
	echo '<div id="platicas">
	<h2>Breviarios</h2>
	<a href="breviarios.php?action=upload" title="+ Subir un nuevo archivo"><img src="../themes/simplicio/folder_add.png" width="48" height="48" /></a><br />
	<table>
	<tr class="ui">
	<th>Presentación</th>
	<th>Descripción</th>
	<th>Fecha</th>
	<th></th>
	</tr>';
	$odd=0;
	while($cursos=mysql_fetch_array($resultcursos))
	{
		if ($odd==0) echo '<tr><td>';
		else echo '<tr class="odd"><td>';
		
		if (substr($cursos[nombre],-4) === ".jpg" || substr($cursos[nombre],-4) === ".JPG") $img=jpg;
		else if (substr($cursos[nombre],-4) === ".pdf" || substr($cursos[nombre],-4) === ".PDF") $img=pdf;
		else if (substr($cursos[nombre],-4) === ".avi" || substr($cursos[nombre],-4) === ".AVI") $img=mpg;
		else if (substr($cursos[nombre],-4) === ".mpg" || substr($cursos[nombre],-4) === ".MPG") $img=mpg;
		else if (substr($cursos[nombre],-4) === ".wmv" || substr($cursos[nombre],-4) === ".WMV") $img=mpg;
		else if (substr($cursos[nombre],-4) === ".ppt" || substr($cursos[nombre],-4) === ".PPT" || substr($cursos[nombre],-5) === ".pptx" || substr($cursos[nombre],-4) === ".PPTX" || substr($cursos[nombre],-5) === ".pps" || substr($cursos[nombre],-4) === ".PPS" || substr($cursos[nombre],-5) === ".ppsx" || substr($cursos[nombre],-5) === ".PPSX") $img=ppt;
		else if (substr($cursos[nombre],-4) === ".htm" || substr($cursos[nombre],-4) === ".HTM") $img=ppt;
		else if (substr($cursos[nombre],-4) === ".mp4" || substr($cursos[nombre],-4) === ".MP4" || substr($cursos[nombre],-4) === ".flv" || substr($cursos[nombre],-4) === ".FLV") $img=mpg;
	  
	  	else $img=doc;
	  
		echo '<img src="/make-training/themes/default/images/ext/'.$img.'.png" alt="'.$img.'" width="32" height="32" /> <a href="seguridad.php?id='.$cursos[ID].'&amp;action=editar" title="Editar">'.$cursos[nombre].'</a></td><td>'.$cursos[descripcion].'</td><td>'.strftime('%d de %b de %Y', $cursos[fecha]).'</td><td><a onclick="return confirmSubmit()" href="seguridad.php?id='.$cursos[ID].'&amp;action=borrar" title="Borrar el curso"><img src="../themes/simplicio/file_delete.png" width="32" height="32" /></a></td>';
		echo '</tr>';
		if ($odd==0) $odd=1;
		else $odd=0;
	}
	mysql_free_result($resultcursos);
	echo '</table><br /></div>'; 
	}

	if ($row[tipo]==1 || $row[tipo]==4)
	{
	$sqlcursos="SELECT * FROM `material` WHERE `curso`='1' ORDER BY `material`.`id` ASC";
	$resultcursos=mysql_query($sqlcursos);
	
	echo '<div id="platicas">
	<h2>Seguridad</h2>
	<a href="seguridad.php?action=upload" title="+ Subir un nuevo archivo"><img src="../themes/simplicio/folder_add.png" width="48" height="48" /></a><br />
	<table>
	<tr class="ui">
	<th>Presentación</th>
	<th>Descripción</th>
	<th>Fecha</th>
	<th></th>
	</tr>';
	$odd=0;
	while($cursos=mysql_fetch_array($resultcursos))
	{
		if ($odd==0) echo '<tr><td>';
		else echo '<tr class="odd"><td>';
		
		if (substr($cursos[nombre],-4) === ".jpg" || substr($cursos[nombre],-4) === ".JPG") $img=jpg;
		else if (substr($cursos[nombre],-4) === ".pdf" || substr($cursos[nombre],-4) === ".PDF") $img=pdf;
		else if (substr($cursos[nombre],-4) === ".avi" || substr($cursos[nombre],-4) === ".AVI") $img=mpg;
		else if (substr($cursos[nombre],-4) === ".mpg" || substr($cursos[nombre],-4) === ".MPG") $img=mpg;
		else if (substr($cursos[nombre],-4) === ".wmv" || substr($cursos[nombre],-4) === ".WMV") $img=mpg;
		else if (substr($cursos[nombre],-4) === ".ppt" || substr($cursos[nombre],-4) === ".PPT" || substr($cursos[nombre],-5) === ".pptx" || substr($cursos[nombre],-4) === ".PPTX" || substr($cursos[nombre],-5) === ".pps" || substr($cursos[nombre],-4) === ".PPS" || substr($cursos[nombre],-5) === ".ppsx" || substr($cursos[nombre],-5) === ".PPSX") $img=ppt;
		else if (substr($cursos[nombre],-4) === ".htm" || substr($cursos[nombre],-4) === ".HTM") $img=ppt;
		else if (substr($cursos[nombre],-4) === ".mp4" || substr($cursos[nombre],-4) === ".MP4" || substr($cursos[nombre],-4) === ".flv" || substr($cursos[nombre],-4) === ".FLV") $img=mpg;
	  
	  	else $img=doc;
	  
		echo '<img src="/make-training/themes/default/images/ext/'.$img.'.png" alt="'.$img.'" width="32" height="32" /> <a href="seguridad.php?id='.$cursos[ID].'&amp;action=editar" title="Editar">'.$cursos[nombre].'</a></td><td>'.$cursos[descripcion].'</td><td>'.strftime('%d de %b de %Y', $cursos[fecha]).'</td><td><a onclick="return confirmSubmit()" href="seguridad.php?id='.$cursos[ID].'&amp;action=borrar" title="Borrar el curso"><img src="../themes/simplicio/file_delete.png" width="32" height="32" /></a></td>';
		echo '</tr>';
		if ($odd==0) $odd=1;
		else $odd=0;
	}
	mysql_free_result($resultcursos);
	echo '</table><br /></div>'; 
	}
}

if ($row[tipo]==1 || $row[tipo]==2) {
echo '<div id="genesis">
<h2>Genesis</h2>
<p>Usuarios con acceso a diplomado de desarrollo ejecutivo de Avon</p>
<br clear="all" />
<table>
	<tr class="ui">
	<th></th>
	<th></th>
	<th>Usuario</th>
	<th>No. de Asociado</th>
	<th>Nombre</th>
	<th>e-mail</th>
	<th>Proceso</th>
	<th>Área</th>
	<th></th>
	</tr>';

$sqlgenesis="SELECT *
FROM `usuarios`
WHERE `genesis` = 1
ORDER BY `usuarios`.`usuario` ASC";
	$resultgenesis=mysql_query($sqlgenesis);
	$odd=0;
	$count=0;
	while($genesis=mysql_fetch_array($resultgenesis))
	{
		if ($genesis[id]!='1'){
		if ($odd==0) echo '<tr>
		';
		else echo '<tr class="odd">
		';
		$count++; 
		$avatar='../users/'.$genesis[usuario].'.jpg'; if (file_exists($avatar)) $avatar=$avatar; else $avatar='../themes/default/images/default.png';
		echo '<td>'.$count.'</td>
		<td><img src="'.$avatar.'" alt="'.$genesis[usuario].'" title="'.$genesis[usuario].'" width="48" height="48" class="thumbnail" /></td>
		<td>'.$genesis[usuario].'</td>
		<td>'.$genesis[asociado].'</td>
		<td><a href="user.php?action=editar&amp;id='.$genesis[id].'&amp;usuario='.$genesis[usuario].'">'.$genesis[nombre].' '.$genesis[apellido].'</a></td>
		<td>'.$genesis[email].'</td>
		<td>'.$genesis[proceso].'</td>
		<td>'.$genesis[area].'</td>
		<td><img src="../themes/simplicio/user_delete.png" width="32" height="32" /></td>
		</tr>
		';
		if ($odd==0) $odd=1;
		else $odd=0;}
	}
mysql_free_result($resultgenesis);
} ?>
</table><br />
</div><!--/#users-->
<!-- footer -->
        <div id="footer">
              <p class="aligncenter">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <?php echo $ver; ?> &copy; <?php echo date('o'); ?> Avon Planta Celaya Manufactura. Arquitectura del sistema por Ivan Barajas</p>
        </div><!-- /footer -->
</div><!--/#wrap-->

</body>
</html>
<?php endif; ?>
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

<a href="index.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a><a onclick="window.open('../register.php', 'mywindow','location=1,status=1,scrollbars=1,width=600,height=600')" title="Crear un nuevo usuario de make-Training"><img src="../themes/simplicio/user_add.png" width="48" height="48" /></a>

<div id="users">
<h2>Usuarios</h2>
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
	</tr>

<?php if ($row[tipo]!=0) {
	if ($row[tipo]==1) $sqlcursos="SELECT * FROM `usuarios` ORDER BY `usuarios`.`usuario` ASC";
	else if ($row[tipo]==2) $sqlcursos="SELECT *
FROM `usuarios`
WHERE `proceso` = 'Manufactura'
AND `area` = 'Envasado'
OR `area` = 'Mantenimiento'
OR `area` = 'Procesos'
OR `area` = 'Almacen'
OR `area` = 'Mejora Continua'
OR `area` = 'Administración Manufactura'
ORDER BY `usuarios`.`usuario` ASC";

else if ($row[tipo]==3) $sqlcursos="SELECT *
FROM `usuarios`
WHERE `area` != 'Envasado'
AND `area` != 'Mantenimiento'
AND `area` != 'Procesos'
AND `area` != 'Almacen'
AND `area` != 'Mejora Continua'
AND `area` != 'Administración Manufactura'
ORDER BY `usuarios`.`usuario` ASC";
	$resultcursos=mysql_query($sqlcursos);
	$odd=0;
	$count=0;
	while($cursos=mysql_fetch_array($resultcursos))
	{
		if ($cursos[id]!='1'){
		if ($odd==0) echo '<tr>
		';
		else echo '<tr class="odd">
		';
		$count++; 
		$avatar='../users/'.$cursos[usuario].'.jpg'; if (file_exists($avatar)) $avatar=$avatar; else $avatar='../themes/default/images/default.png';
		echo '<td>'.$count.'</td>
		<td><img src="'.$avatar.'" alt="'.$cursos[usuario].'" title="'.$cursos[usuario].'" width="48" height="48" class="thumbnail" /></td>
		<td>'.$cursos[usuario].'</td>
		<td>'.$cursos[asociado].'</td>
		<td><a href="user.php?action=editar&amp;id='.$cursos[id].'&amp;usuario='.$cursos[usuario].'">'.$cursos[nombre].' '.$cursos[apellido].'</a></td>
		<td>'.$cursos[email].'</td>
		<td>'.$cursos[proceso].'</td>
		<td>'.$cursos[area].'</td>
		<td><img src="../themes/simplicio/user_delete.png" width="32" height="32" /></td>
		</tr>
		';
		if ($odd==0) $odd=1;
		else $odd=0;}
	}
mysql_free_result($resultcursos);
}
?>
</table><br />
</div><!--/#users-->
<!-- footer -->
        <div id="footer">
              <p class="aligncenter">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <?php echo $ver; ?> &copy; <?php echo date('o'); ?> Avon Planta Celaya Manufactura. Arquitectura del sistema por Iván Barajas</p>
        </div><!-- /footer -->
</div><!--/#wrap-->

</body>
</html>
<?php endif; ?>
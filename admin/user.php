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

<a href="users.php" title="Regresar al panel de administración"><img src="../themes/simplicio/direction_left.png" width="48" height="48" /></a>

<div id="users">
<h2>Modificar Datos de Usuario</h2>

<img src="<?php $avatar="../users/".$_GET[usuario].".jpg";
if (file_exists($avatar)) echo $avatar;
else echo '../themes/default/images/default.png';
?>" alt="avatar" width="128" height="128" class="avatar alignleft" />

<?php $tipo=$row[tipo];
$sql="SELECT * FROM `usuarios` WHERE `id` = '$_GET[id]' LIMIT 1";
$result = mysql_query($sql);
$row=mysql_fetch_array($result);
mysql_free_result($result); ?>

<form action="post.php" method="post">
<table class="form-table">
	<tr>
		<th><label for="user_login">Nombre de usuario</label></th>
		<td><input type="text" name="user_login" id="user_login" value="<?php echo $_GET[usuario]; ?>" disabled="disabled" class="regular-text" /> <span class="description">El nombre de usuario no puede cambiarse.</span></td>
	</tr>

<tr>
	<th><label for="aosciado">No. de asociado</label></th>
	<td><input type="text" name="asociado" id="asociado" value="<?php echo $row[asociado]; ?>" class="regular-text" /></td>
</tr>
	
<tr>
	<th><label for="first_name">Nombre</label></th>
	<td><input type="text" name="nombre" id="first_name" value="<?php echo $row[nombre]; ?>" class="regular-text" /></td>
</tr>

<tr>
	<th><label for="last_name">Apellidos</label></th>

	<td><input type="text" name="apellido" id="last_name" value="<?php echo $row[apellido]; ?>" class="regular-text" /></td>
</tr>

<tr>
	<th><label for="email">Correo electrónico</label></th>
	<td><input type="text" name="email" id="email" value="<?php echo $row[email]; ?>" class="regular-text" /></td>
</tr>

</table>

<h3>Proceso/Área</h3>

<table class="form-table">
<tr>
<th><label for="proceso">Proceso</label></th>
<td><select id="proceso" name="proceso">
					<?php if($tipo==1) { $output=str_replace('value="'.$row[proceso].'"','selected value="'.$row[proceso].'"','<option value="Manufactura">Manufactura</option>
					<option value="Soporte">Soporte</option>
					<option value="Operaciones">Operaciones</option>
					<option value="Flujo de Ordenes">Flujo de Ordenes</option>');
					echo $output;}
					
					else if($tipo==2) echo '<option selected value="Manufactura">Manufactura</option>';
					else if($tipo==3) { $output=str_replace('value="'.$row[proceso].'"','selected value="'.$row[proceso].'"','<option value="Soporte">Soporte</option>
					<option value="Operaciones">Operaciones</option>
					<option value="Flujo de Ordenes">Flujo de Ordenes</option>');
					echo $output;} ?>
				</select></td>
</tr>
<tr><th><label for="area">Area</label></th>
<td><select id="area" name="area">
     <?php if($tipo==1) { $output=str_replace('value="'.$row[area].'"','selected value="'.$row[area].'"','<OPTGROUP label="Manufactura">
       <option value="Envasado">Envasado</option>
		<option value="Procesos">Procesos</option>
		<option value="Almacen">Almacén</option>
		<option value="Administración Manufactura">Administración</option>
		<option value="Mantenimiento">Mantenimiento</option>
		<option value="Mejora Continua">Mejora Continua</option>
     </OPTGROUP>
     <OPTGROUP label="Soporte">
	 <option value="Control Documental GMPs">Control Documental GMP\'s</option>
       <option value="Calidad Micro y Fisicoquimico">Calidad Micro y Fisicoquimico</option>
		<option value="Calidad Componentes">Calidad Componentes</option>
		<option value="Control de Documentos">Control de Documentos</option>
		<option value="Calidad Envasado">Calidad Envasado</option>
		<option value="Abasto y Programacion">Abasto y Programacion</option>
		<option value="Calidad Validacion">Calidad Validacion</option>
		<option value="Aseguramiento de Calidad">Aseguramiento de Calidad</option>
		<option value="Soporte Tecnico (cGMPs)">Soporte Tecnico (cGMPs)</option>
		<option value="Recursos Humanos">Recursos Humanos</option>
		<option value="Ingenieria Industrial">Ingenieria Industrial</option>
		<option value="Asuntos Regulatorios">Asuntos Regulatorios</option>
		<option value="ITS">ITS</option>
		<option value="IT Xola">IT Xola</option>
		<option value="Medio Ambiente">Medio Ambiente</option>
		<option value="Mantenimiento Order Fulfillment">Mantenimiento Order Fulfillment</option>
		<option value="Seguridad Industrial y Patrimonial">Seguridad Industrial y Patrimonial</option>
		<option value="Calidad Certificacion de Proveedores">Calidad Certificacion de Proveedores</option>
		<option value="Finanzas">Finanzas</option>
		<option value="Calidad BCFT">Calidad BCFT</option>
     </OPTGROUP>
     <OPTGROUP label="Operaciones">
       <option value="Calidad Shipping">Calidad Shipping</option>
		<option value="Calidad Exportaciones">Calidad Exportaciones</option>
		<option value="Recibo Producto Terminado">Recibo Producto Terminado</option>
		<option value="Almacen Producto Terminado">Almacen Producto Terminado</option>
		<option value="Muelle">Muelle</option>
		<option value="Expedicion">Expedicion</option>

		<option value="Flete">Flete</option>
		<option value="Porteo">Porteo</option>
		<option value="Sistemas Shipping">Sistemas Shipping</option>
		<option value="Line Balance">Line Balance</option>
		<option value="Shipping">Shipping</option>
		<option value="Recibo Miscelaneos">Recibo Miscelaneos</option>
     </OPTGROUP>
		<OPTGROUP label="Flujo de ordenes">		
		<option value="Control de Ordenes y Cajas">Control de Ordenes y Cajas</option>
		<option value="Compras">Compras</option>
		<option value="Ingenieria de Empaque">Ingenieria de Empaque</option>
		<option value="Flujo de Ordenes">Flujo de Ordenes</option>
		</OPTGROUP>'); echo $output; }
		else if ($tipo==2) { $output=str_replace('value="'.$row[area].'"','selected value="'.$row[area].'"','<OPTGROUP label="Manufactura">
       <option value="Envasado">Envasado</option>
		<option value="Procesos">Procesos</option>
		<option value="Almacen">Almacén</option>
		<option value="Administración Manufactura">Administración</option>
		<option value="Mantenimiento">Mantenimiento</option>
		<option value="Mejora Continua">Mejora Continua</option>
     </OPTGROUP>'); echo $output; }
	 else if ($tipo==3) { $output=str_replace('value="'.$row[area].'"','selected value="'.$row[area].'"','<OPTGROUP label="Soporte">
	 <option value="Control Documental GMPs">Control Documental GMP\'s</option>
       <option value="Calidad Micro y Fisicoquimico">Calidad Micro y Fisicoquimico</option>
		<option value="Calidad Componentes">Calidad Componentes</option>
		<option value="Control de Documentos">Control de Documentos</option>
		<option value="Calidad Envasado">Calidad Envasado</option>
		<option value="Abasto y Programacion">Abasto y Programacion</option>
		<option value="Calidad Validacion">Calidad Validacion</option>
		<option value="Aseguramiento de Calidad">Aseguramiento de Calidad</option>
		<option value="Soporte Tecnico (cGMPs)">Soporte Tecnico (cGMPs)</option>
		<option value="Recursos Humanos">Recursos Humanos</option>
		<option value="Ingenieria Industrial">Ingenieria Industrial</option>
		<option value="Asuntos Regulatorios">Asuntos Regulatorios</option>
		<option value="ITS">ITS</option>
		<option value="IT Xola">IT Xola</option>
		<option value="Medio Ambiente">Medio Ambiente</option>
		<option value="Mantenimiento Order Fulfillment">Mantenimiento Order Fulfillment</option>
		<option value="Seguridad Industrial y Patrimonial">Seguridad Industrial y Patrimonial</option>
		<option value="Calidad Certificacion de Proveedores">Calidad Certificacion de Proveedores</option>
		<option value="Finanzas">Finanzas</option>
		<option value="Calidad BCFT">Calidad BCFT</option>
     </OPTGROUP>
     <OPTGROUP label="Operaciones">
       <option value="Calidad Shipping">Calidad Shipping</option>
		<option value="Calidad Exportaciones">Calidad Exportaciones</option>
		<option value="Recibo Producto Terminado">Recibo Producto Terminado</option>
		<option value="Almacen Producto Terminado">Almacen Producto Terminado</option>
		<option value="Muelle">Muelle</option>
		<option value="Expedicion">Expedicion</option>

		<option value="Flete">Flete</option>
		<option value="Porteo">Porteo</option>
		<option value="Sistemas Shipping">Sistemas Shipping</option>
		<option value="Line Balance">Line Balance</option>
		<option value="Shipping">Shipping</option>
		<option value="Recibo Miscelaneos">Recibo Miscelaneos</option>
     </OPTGROUP>
		<OPTGROUP label="Flujo de ordenes">		
		<option value="Control de Ordenes y Cajas">Control de Ordenes y Cajas</option>
		<option value="Compras">Compras</option>
		<option value="Ingenieria de Empaque">Ingenieria de Empaque</option>
		<option value="Flujo de Ordenes">Flujo de Ordenes</option>
		</OPTGROUP>'); echo $output; } ?>
		</select>
			</td>
			</tr>
</table>

<h3>Perfil</h3>

<table class="form-table">

<tr id="perfil-row">
	<th><label for="perfil">Perfil</label></th>
	<td><select id="perfil" name="perfil">
		<option value="0">Usuario</option>
		<option value="2">Admon Make</option>
		<option value="3">Admon Calidad</option>
		<option value="4">Admon Seguridad</option>
		</select></td>
</tr>
</table>

<?php if($tipo==1 || $tipo==2) {if($row[genesis]==1) $output=str_replace('value="1"','selected value="1"', '<h3>Genesis</h3>

<table class="form-table">

<tr id="genesis-row">
	<th><label for="genesis">Genesis</label></th>
	<td><select id="genesis" name="genesis">
		<option value="0">Sin acceso</option>
		<option value="1">Abierto</option>
		</select></td>
</tr>
</table>');
else $output='<h3>Genesis</h3>

<table class="form-table">

<tr id="genesis-row">
	<th><label for="genesis">Genesis</label></th>
	<td><select id="genesis" name="genesis">
		<option selected value="0">Sin acceso</option>
		<option value="1">Abierto</option>
		</select></td>
</tr>
</table>';
echo $output;}
?>

<h3>Acerca del usuario</h3>

<table class="form-table">

<tr id="password">
	<th><label for="pass1">Nueva contraseña</label></th>
	<td><input type="password" name="pass1" id="pass1" value="" autocomplete="off" /> <span class="description">Si deseas cambiar la contraseña del usuario, escribe aquí dos veces la nueva. En caso contrario, deja las casillas en blanco.</span><br />
		<input type="password" name="pass2" id="pass2" value="" autocomplete="off" /> <span class="description">Teclea la nueva contraseña otra vez.</span><br /></td>
</tr>
</table>
<input type="hidden" id="action" name="action" value="user-do" />
<input type="hidden" id="id" name="id" value="<?php echo $_GET[id]; ?>" />
<input type="submit" name="submit" value="Guardar" />
</form>
</div><!--/#users-->

<br clear="all" />
<h3>Cursos Tomados</h3>
<div class="post-content clearfix">
		<table>
<tr>
<th>no.</th>
<th>Curso</th>
<th>Calificación</th>
<th>Examen</th>
<th>Fecha</th>
</tr>
<?php $sql="SELECT * FROM calif WHERE usuario = '$_GET[id]' ORDER BY `calif`.`id` ASC";
$result = mysql_query($sql);
$count=1;
while($row=mysql_fetch_array($result)) : ?><tr>
<td><?php echo $count; $count++; ?></td>
<td><?php $sqlc="SELECT * FROM cursos WHERE ID = '$row[curso]' LIMIT 1";
$resultc = mysql_query($sqlc);
$rowc=mysql_fetch_array($resultc); echo $rowc[clave].' '.$rowc[nombre]; mysql_free_result($resultc); ?></td>
<td><?php echo $row[calif]; ?></td>
<td><?php echo '<a href="../examenes/'.$rowc[clave].'-'.$_GET[id].'.pdf" target="_blank" title="Descargar examen"><img src="../themes/default/images/ext/pdf.png" alt="pdf" width="32" height="32" /></a>'; ?></td>
<td><?php echo strftime('%d/%b/%y %H:%M', $row[fecha]); ?></td>
</tr>
			<?php endwhile; mysql_free_result($result);?>
</table>
							
							</div>
<br clear="all" />
<!-- footer -->
        <div id="footer">
              <p class="aligncenter">mak<span style="color:#ec008c;"><strong><em>e</em></strong></span>-Training <?php echo $ver; ?> &copy; <?php echo date('o'); ?> Avon Planta Celaya Manufactura. Arquitectura del sistema por Ivan Barajas</p>
        </div><!-- /footer -->
</div><!--/#wrap-->

</body>
</html>
<?php endif; ?>
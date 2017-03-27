<?php
function conectar() {
	/*
	$server = 'app.citymes.com';
	$user = 'mycitymes';
	$pass = 'MD82452H';
	$db = 'citymesapp';
*/
	$server = 'app.citymes.com';
	$user = 'myapp3623';
	$pass = 'xbbP9GMw';
	$db = 'citymesapp';

	$conexion = mysqli_connect($server, $user, $pass, $db);
	return $conexion;
}

function reordenar_elemento($id, $orden, $table) {
	$conexion = conectar();

	$consulta = mysqli_query($conexion, "
        UPDATE $table
        SET sort_order = $orden
        WHERE id = $id");

	if ($consulta) return true;
	return false;
}
if (!empty($_POST['data'])) {
	$data = $_POST['data'];
	$table = $_GET['table'];
	$orden = 1;
	$array_elementos = explode(',', $data); // separamos por comas y guardamos en un array
	foreach ($array_elementos as $elemento) {
		// recordamos que los elementos se guardaban como "elemento-1", "elemento-2", etc
		$elemento_id = explode('-', $elemento); // en $elemento_id[1] tendríamos la id
		$id = $elemento_id[1];
		reordenar_elemento($id, $orden, $table); // reordenamos
		$orden++; // aumentamos 1 al orden
	}
}
?>
hola

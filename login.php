<?php session_start();

if (isset($_SESSION['usuario'])) {
	header('Location: index.php');
}

$errores = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$usuario = filter_var(strtolower($_POST['usuario']), FILTER_SANITIZE_STRING);
	$password = $_POST['password'];
	$password = hash('sha512', $password);

	try {
		$conection = new PDO('mysql:host=localhost;dbname=curso_login', 'root', '1234');
	} catch (PDOException $e) {
		echo "Error: " . $e->getMessage();
	}

	$statement = $conection->prepare('SELECT * FROM usuarios WHERE usuario = :usuario AND pass = :password');
	$statement->execute(array(
		':usuario' => $usuario,
		':password' => $password
	));

	$result = $statement->fetch();
	if ($result !== false) {
		$_SESSION['usuario'] = $usuario;
		header('Location: index.php');
	}else{
		$errores .= '<li>El usuario o la contraseña son incorrectos</li>';
	}
}


require 'views/login.view.php';

?>
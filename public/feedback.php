<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body class="container">


<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app = new Silex\Application();
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'host' => 'localhost',
        'dbname' => 'molinos',
        'user' => 'root',
        'password' => '',
        'driver' => 'pdo_mysql',
        'charset' => 'utf8mb4',
    ),
));

// Please set to false in a production environment
$app['debug'] = true;

$name = $_POST['txtFormName'];
$email = $_POST['txtFormEmail'];
$message = $_POST['txtFormMessage'];
$sql = "INSERT INTO feedback(name, email, message) VALUES ('$name','$email','$message')";
$app['db']->exec($sql);


?>

<div>Ваше сообщение отправлено. Спасибо!</div>

<?php foreach ($app['db']->fetchAll('SELECT * FROM feedback') as $row) : ?>
    <tr>
        <td><?= $row['name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['message'] ?></td>
    </tr>
<?php endforeach; ?>
</body>
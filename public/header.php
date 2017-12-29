<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body class="container">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
      integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<link rel="stylesheet" href="custom.css">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
        integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
        crossorigin="anonymous"></script>
<body class="container">
<header class="header">
    <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
        <a class="navbar-brand">Обратная связь</a>
        <ul class="navbar-nav">
            <li class="nav-item active"><a class="nav-link" href="/public/">Home</a></li>
            <li class="nav-item active"><a class="nav-link" href="/public/admin.php">Admin panel</a></li>

        </ul>
    </nav>
</header>

<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
$app = new Silex\Application();
Request::enableHttpMethodParameterOverride();

$app->register(new Silex\Provider\RoutingServiceProvider());
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


$app['debug'] = true;

$app->get('/', function () use ($app) {
    return '';
});

$app->get('/feedback/{id}', function ($id) use ($app) {
    $sql = "DELETE FROM feedback WHERE id = '$id'";
    $app['db']->exec($sql);
    return "<div class=\"alert alert-success\" id=\"flash_notice\">Запись удалена</div>";
})
    ->bind('delete_feedback');

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));


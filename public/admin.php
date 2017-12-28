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

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;


Request::enableHttpMethodParameterOverride();

$app = new Silex\Application();
$app->register(new Silex\Provider\RoutingServiceProvider());


$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
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

$app->get('/', function () use ($app) {
    return '';
});

$app->get('/feedback/{id}', function ($id) use ($app) {
    $sql = "DELETE FROM feedback WHERE id = '$id'";
    $app['db']->exec($sql);
    return "<div class=\"alert alert-success\" id=\"flash_notice\">Запись удалена</div>";
})
    ->bind('delete_feedback');




$app->run();

?>
<h1>Оставленные заявки</h1>
<div class="feedback_table">
    <table class="table">
        <tr>
            <th>Имя отправителя</th>
            <th>Email</th>
            <th>Сообщение</th>
            <th></th>
            <th></th>
        </tr>

        <?php foreach ($app['db']->fetchAll('SELECT * FROM feedback') as $row) : ?>
            <?php $request = Request::create($app['url_generator']->generate('delete_feedback',
                array('id' => $row['ID'])), 'DELETE'); ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['message'] ?></td>
                <td>
                    <?= '<a rel="nofollow" class="btn btn-link" data-method="delete"  href="' . $app['url_generator']->generate('delete_feedback', array('id' => $row['ID'])) . '">Удалить запись</a>'; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


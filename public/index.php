<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Пример веб-страницы</title>
</head>
<body class="container">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
      integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"
        integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"
        integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn"
        crossorigin="anonymous"></script>

<h1 class="row justify-content-md-center">Форма обратной связи</h1>
<div class="col-md-6 offset-md-3">
    <form action="send.php" method="post" name="form1" enctype="multipart/form-data">
        <div class="form-group">
            <label for="txtFormName">Введите ваше имя</label>
            <input name="txtFormName" type="text" class="form-control" required placeholder="Иван Приарит"
                   title='Иван Приарит'></div>
        <div class="form-group">
            <label for="txtFormEmail">Введите Ваш email</label>
            <input name="txtFormEmail" id="txtFormEmail" type="email" class="form-control" placeholder="example.email@gmail.com" required
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="example.email@gmail.com"/></div>
        <div class="form-group">
            <label for="txtFormMessage">Введите вашe сообщение</label>
            <input name="txtFormMessage"  class="form-control" title="Не может быть пустым"
                   placeholder="Ваше сообщение" required></div>
        <label for="fileAttach">Прикрепите файл с макетом</label>
        <input name="fileAttach" type="file" required>
        <br>
        <br>
        <input type="submit" name="Submit" value="ОТПРАВИТЬ" class="btn btn-danger btn-block">
    </form>
</div>
</body>
</html>

<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

// Please set to false in a production environment
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'      => 'localhost',
        //'path'     => __DIR__.'/app.db',
        'user'      => 'root',
        'password'  => '0'

    ),
));

$app->get('/', function () use ($toys) {
    return json_encode($toys);
});

$app->get('/{stockcode}', function (Silex\Application $app, $stockcode) use ($toys) {
    if (!isset($toys[$stockcode])) {
        $app->abort(404, "Stockcode {$stockcode} does not exist.");
    }
    return json_encode($toys[$stockcode]);
});

$app->run();
?>


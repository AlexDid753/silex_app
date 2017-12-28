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
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app['debug'] = true;

$app->get('/', function () use ($app) {
    return '';
});


$app->post('/feedback', function (Request $request) use ($app) {

    $message = $request->get('message');
    mail('alex.didenko753@gmail.com', '[YourSite] Feedback', $message);
    $name = $_POST['txtFormName'];
    $email = $_POST['txtFormEmail'];
    $message = $_POST['txtFormMessage'];
    $sql = "INSERT INTO feedback(name, email, message) VALUES ('$name','$email','$message')";
    $app['db']->exec($sql);

    /*mail("alex.didenko753@gmail.com", "the subject", $message,
        "From: webmaster@example.com \r\n");*/
    $app['swiftmailer.options'] = array(
        'host' => 'smtp.yandex.ru',
        'port' => '465',
        'username' => 't3sto90',
        'password' => '0177694asd',
        'encryption' => 'ssl',
        'auth_mode' => null
    );


    $message = (new \Swift_Message('Molinos Feedback'))
        ->setFrom('t3sto90@yandex.ru', 'Molinos')
        ->setTo('alex.didenko753@gmail.com')
        ->setBody('Есть новая заявка на обратную связь. Проверьте в админской панели.', 'text/html');

    /*
    $message = \Swift_Message::newInstance()
        ->setSubject('Molinos Feedback')
        ->setFrom(array('noreply@molinos.com'))
        ->setTo(array('alex.didenko753@gmail.com'))
        ->setBody($request->get('Есть новая заявка на обратную связь. Проверьте в админской панели.'));
*/
    $app['mailer']->send($message);

    return '<div>Ваше сообщение отправлено. Спасибо!</div>';
})
    ->bind('new_feedback');

$app->run();


?>
<h1 class="row justify-content-md-center">Форма обратной связи</h1>
<div class="col-md-6 offset-md-3">
    <form action="index.php/feedback" method="post" name="form1" enctype="multipart/form-data">
        <div class="form-group">
            <label for="txtFormName">Введите ваше имя</label>
            <input name="txtFormName" type="text" class="form-control" required placeholder="Иван Приарит"
                   title='Семен Петрович'></div>
        <div class="form-group">
            <label for="txtFormEmail">Введите Ваш email</label>
            <input name="txtFormEmail" id="txtFormEmail" type="email" class="form-control"
                   placeholder="example.email@gmail.com" required
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" title="example.email@gmail.com"/></div>
        <div class="form-group">
            <label for="txtFormMessage">Введите вашe сообщение</label>
            <input name="txtFormMessage" class="form-control" title="Не может быть пустым"
                   placeholder="Ваше сообщение" required></div>
        <label for="fileAttach">Прикрепите файл</label>
        <input name="fileAttach" type="file">
        <br>
        <br>
        <input type="submit" name="Submit" value="ОТПРАВИТЬ" class="btn btn-danger btn-block">
    </form>
</div>
</body>


<div class="feedback_table">
    <table class="table">
        <tr>
            <th>Имя отправителя</th>
            <th>Email</th>
            <th>Сообщение</th>
        </tr>

        <?php foreach ($app['db']->fetchAll('SELECT * FROM feedback') as $row) : ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['message'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


</html>
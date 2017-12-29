<?php
include_once 'header.php';
require_once __DIR__ . '/../vendor/autoload.php';

$app->register(new Silex\Provider\SwiftmailerServiceProvider());


$app->post('/feedback', function (Request $request) use ($app) {

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


    $app['mailer']->send($message);

    return '<div class="alert alert-success" id="flash_notice">Ваше сообщение отправлено. Спасибо!</div>';
})
    ->bind('new_feedback');

$app->run();


?>
<h1 class="row justify-content-md-center">Форма обратной связи</h1>
<div class="col-md-6 offset-md-3">
    <form action="index.php/feedback" method="post" name="form1" enctype="multipart/form-data">
        <div class="form-group">
            <label for="txtFormName">Введите ваше имя</label>
            <input name="txtFormName" type="text" class="form-control" required placeholder="Семен Петрович"
                   title='Семен Петрович'></div>
        <div class="form-group">
            <label for="txtFormEmail">Введите Ваш email</label>
            <input name="txtFormEmail" id="txtFormEmail" type="email" class="form-control"
                   placeholder="example@email.com" required
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
<?php
include_once 'header.php';
require_once __DIR__ . '/../vendor/autoload.php';

$app->register(new Silex\Provider\SwiftmailerServiceProvider());


$app->post('/feedback', function () use ($app) {

    if ($_POST['invisible'] != '') {
        die('Ботам - нет!');
    }
    $files_folder = "c:/OpenServer/domains/molinos/public/files/";

    if ($_FILES["userfile"]["size"] > 1024 * 3 * 1024) {
        echo("Размер файла превышает три мегабайта");
        exit;
    }
    if (is_uploaded_file($_FILES["userfile"]["tmp_name"])) {
        $file_path = $files_folder . $_FILES["userfile"]["name"];
        move_uploaded_file($_FILES["userfile"]["tmp_name"], $files_folder . $_FILES["userfile"]["name"]);
        echo 'Файл загружен!';
    } else {
        echo("Ошибка загрузки файла. Выберите размер поменьше");
        exit;
    }
    $name = $_POST['txtFormName'];
    $email = $_POST['txtFormEmail'];
    $message = $_POST['txtFormMessage'];
    $sql = "INSERT INTO feedback(name, email, message, file) VALUES ('$name','$email','$message','$file_path')";
    $app['db']->exec($sql);

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


    $uploaddir = 'c:/OpenServer/domains/molinos/public/files/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);


    return '<div class="alert alert-success" id="flash_notice">Ваше сообщение отправлено. Спасибо!</div>';
})
    ->bind('new_feedback');



$app->run();


?>
<h1 class="row justify-content-md-center">Форма обратной связи</h1>
<div class="col-md-6 offset-md-3">
    <form action="/public/index.php/feedback" method="post" name="form1" enctype="multipart/form-data">
        <input type="hidden" name="MAX_FILE_SIZE" value="30000"/>
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
        <label for="userfile">Прикрепите файл</label>
        <input id="userfile" name="userfile" type="file">
        <br>
        <br>
        <input type="text" name="invisible" id="invisible" value="">
        <input type="submit" name="Submit" value="ОТПРАВИТЬ" class="btn btn-danger btn-block">
    </form>
</div>
</body>


</html>
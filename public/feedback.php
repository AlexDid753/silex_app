<?php include_once 'header.php';
$app->run();
?>


<?php

$name = $_POST['txtFormName'];
$email = $_POST['txtFormEmail'];
$message = $_POST['txtFormMessage'];
$sql = "INSERT INTO feedback(name, email, message) VALUES ('$name','$email','$message')";
$app['db']->exec($sql);


?>

<div class="alert alert-success" id="flash_notice">Ваше сообщение отправлено. Спасибо!</div>


<?php include_once 'header.php';

use Symfony\Component\HttpFoundation\Request;



$app->post('/admin_mail', function () use ($app) {
    $email = $_POST['email'];
    $sql = "UPDATE admin set email = '$email' WHERE id = 1";
    $app['db']->exec($sql);

    return '<div class="alert alert-success" id="flash_notice">Почта администратора изменена!</div>';
})
    ->bind('admin_mail');

$sql = $app['db']->query('SELECT email FROM admin WHERE id = 1 LIMIT 1');
$admin_email = $sql->fetch()['email'];
$app->run();
?>

<div class="row">
    <h1 class="col-md-6">Оставленные заявки</h1>
    <form class="col-md-6" action="/public/admin.php/admin_mail" method="post" name="admin_mail">

        <div class="col-md-6 admin_mail">Админская почта:
            <input class="form-control" type="text" required class="admin_mail_value" name="email" value="<?= $admin_email ?>">
        </div>
        <div class="col-md-6 admin_mail">
            <input type="submit" name="Submit" value="Сохранить" class="btn btn-block">
        </div>
    </form>
</div>

<div class="feedback_table">
    <table class="table">
        <tr>
            <th>Имя отправителя</th>
            <th>Email</th>
            <th>Сообщение</th>
            <th>Файл</th>
            <th></th>
        </tr>

        <?php foreach ($app['db']->fetchAll('SELECT * FROM feedback') as $row) : ?>
            <?php $request = Request::create($app['url_generator']->generate('delete_feedback',
                array('id' => $row['ID'])), 'DELETE');
            $full_path = $row['file'];
            $file_name = end(explode("/", $full_path));
            ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['message'] ?></td>
                <td><?php if (!empty($file_name)) {
                        echo '<a href="/public/files/' . $file_name . '">' . $file_name . '</a>';
                    } else {
                        echo 'Не прикреплен';
                    }
                    ?></td>
                <td>
                    <?= '<a rel="nofollow" class="btn btn-link" data-method="delete"  href="' . $app['url_generator']->generate('delete_feedback', array('id' => $row['ID'])) . '">Удалить запись</a>'; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


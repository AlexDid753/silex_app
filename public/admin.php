<?php include_once 'header.php';
use Symfony\Component\HttpFoundation\Request;
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


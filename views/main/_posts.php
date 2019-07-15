<?php
/**
 * @var $this yii\web\View
 * @var $posts array
 * @var $post \app\lib\entity\PostEntity
 */

use yii\helpers\Url;

?>

<?php foreach ($posts as $post): ?>
    <div class="alert alert-secondary" role="alert">
        <?= $post->getMessage(); ?><br>
        <a href="<?= Url::to(['post/delete', 'id' => $post->getId()]) ?>">delete</a>
    </div>
<?php endforeach; ?>

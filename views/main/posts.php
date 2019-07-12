<?php
/**
 * @var $this yii\web\View
 * @var $posts array
 * @var $post \app\lib\entity\PostEntity
 */

use yii\helpers\Url;

$this->title = 'Posts';

?>
<div class="site-index">
    <ul class="nav nav-tabs nav-fill">
        <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['feed']) ?>">My feed</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?= Url::to(['posts']) ?>">My post</a>
        </li>
    </ul>
</div>

<?php foreach ($posts as $post): ?>
    <div class="alert alert-secondary" role="alert">
        <?= $post->getMessage(); ?>
        <a href="<?= Url::to(['post/delete', 'id' => $post->getId()]) ?>">delete</a>
    </div>
<?php endforeach; ?>

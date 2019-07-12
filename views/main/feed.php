<?php
/**
 * @var $this yii\web\View
 * @var $feed array
 */

$this->title = 'Feed';

use yii\helpers\Url;

?>
<div class="site-index">
    <ul class="nav nav-tabs nav-fill">
        <li class="nav-item">
            <a class="nav-link active" href="<?= Url::to(['feed']) ?>">My feed</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= Url::to(['posts']) ?>">My post</a>
        </li>
    </ul>
</div>
<?php foreach ($feed as $post): ?>
    <div class="alert alert-secondary">
        <a href="<?= Url::to(['main/user-profile', 'username' => $post->getUsername()]) ?>"><?= $post->getUsername(); ?></a>
        <?= $post->getMessage(); ?>
    </div>
<?php endforeach; ?>

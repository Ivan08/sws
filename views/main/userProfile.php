<?php
/**
 * @var $this yii\web\View
 * @var $posts array
 * @var $post \app\lib\entity\PostEntity
 * @var $isSubscribe bool
 * @var $user \app\lib\entity\PostEntity
 */

use yii\helpers\Url;

$this->title = 'Posts';

?>
<?php if($isSubscribe): ?>
    <a href="<?= Url::to(['follow/unsubscribe', 'username' => $user->getUsername()]) ?>" class="btn btn-outline-secondary btn-lg btn-block" role="button" aria-pressed="true">unsubscribe</a>
<?php else: ?>
    <a href="<?= Url::to(['follow/subscribe', 'username' => $user->getUsername()]) ?>" class="btn btn-outline-success btn-lg btn-block" role="button" aria-pressed="true">subscribe</a>
<?php endif; ?>
<br>

<?php foreach ($posts as $post): ?>
    <div class="alert alert-secondary" role="alert">
        <?= $post->getMessage(); ?>
    </div>
<?php endforeach; ?>

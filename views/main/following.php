<?php
/**
 * @var $following \app\lib\entity\UserEntity[]
 */

use yii\helpers\Url;

$this->title = 'following';
?>
<div class="site-index">
    <?php foreach ($following as $user): ?>
        <div class="alert alert-secondary">
            <a href="<?= Url::to(['main/user-profile', 'username' => $user->getUsername()]) ?>"><?= $user->getUsername(); ?></a>
            <a href="<?= Url::to(['follow/unsubscribe', 'username' => $user->getUsername()]) ?>">unsubscribe</a>
        </div>
    <?php endforeach; ?>
</div>

<?php
/**
 * @var $this yii\web\View
 * @var $followers \app\lib\entity\UserEntity[]
 */

use yii\helpers\Url;

$this->title = 'followers';
?>
<div class="site-index">
    <?php foreach ($followers as $user): ?>
        <div class="alert alert-secondary">
            <a href="<?= Url::to(['main/user-profile', 'username' => $user->getUsername()]) ?>"><?= $user->getUsername(); ?></a>
            <a href="<?= Url::to(['follow/delete', 'username' => $user->getUsername()]) ?>">delete</a>
        </div>
    <?php endforeach; ?>
</div>

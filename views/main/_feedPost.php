<?php
/**
 * @var $this yii\web\View
 * @var $feed array
 */

use yii\helpers\Url;

?>

<?php foreach ($feed as $post): ?>
    <div class="alert alert-secondary">
        <a href="<?= Url::to(['main/user-profile', 'username' => $post->getUsername()]) ?>"><?= $post->getUsername(); ?></a>
        <br>
        <?= $post->getMessage(); ?>
    </div>
<?php endforeach; ?>

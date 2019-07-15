<?php
/**
 * @var $this yii\web\View
 * @var $posts array
 * @var $post \app\lib\entity\PostEntity
 */

use yii\helpers\Url;

$this->title = 'Posts';
$this->registerJsFile(Yii::getAlias('/js/infBlock.js'), ['depends' => \yii\web\JqueryAsset::class]);

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
<div class="infBlock" data-url="<?= Url::to(['posts']) ?>">
    <?= $this->render('_posts', ['posts' => $posts]); ?>
</div>

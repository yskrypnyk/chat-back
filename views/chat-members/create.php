<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChatMembers */

$this->title = 'Create Chat Members';
$this->params['breadcrumbs'][] = ['label' => 'Chat Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chat-members-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

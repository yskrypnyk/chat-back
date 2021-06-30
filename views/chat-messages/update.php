<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChatMessages */

$this->title = 'Update Chat Messages: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Chat Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chat-messages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

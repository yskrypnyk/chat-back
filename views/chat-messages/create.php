<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChatMessages */

$this->title = 'Create Chat Messages';
$this->params['breadcrumbs'][] = ['label' => 'Chat Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chat-messages-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

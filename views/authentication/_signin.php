<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class="signin">
    <h2><?= Html::encode(Yii::t('app', 'Sign In')) ?></h2>
    <!--
<p><?= Yii::t("app", "Please fill out the following fields to continue") ?></p>
    !-->

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'rememberMe')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
        <?= Html::a('Forgot Password', ['/account/recovery/request'], ['class' => 'btn btn-warning', 'name' => 'recovery-request-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

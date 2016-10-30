<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div class=signup">
    <h2><?= Html::encode(Yii::t('app', 'Sign Up')) ?></h2>
    <!--
    <p><?= Yii::t("app", "Please fill out the following fields to continue") ?></p>
!-->
    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>



    <div class="form-group">
        <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

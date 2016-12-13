<?php

namespace humanized\account\controllers;

use Yii;
use yii\web\Controller;
use humanized\account\models\base\RecoveryRequest;
use humanized\account\models\base\PasswordSetup;

/**
 * RecoveryController implements the actions for account password recovery.
 */
class RecoveryController extends Controller
{

    public function actionRequest()
    {
        $model = new RecoveryRequest();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'account-reset-sent'));
            return $this->redirect('index');
        }

        return $this->render('@vendor/humanized/yii2-account/views/recovery/request', ['model' => $model]);
    }

    public function actionResetPassword($token)
    {
        $userClass = Yii::$app->user->identityClass;
        if (!$userClass::isPasswordResetTokenValid($token)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'link.error{type}{status}', ['type' => 'Reset', 'status' => 'expired']));
            return $this->redirect(['request-account-reset']);
        }

        if (NULL === $userClass::findByPasswordResetToken($token)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'link.error{type}{status}', ['type' => 'Reset', 'status' => 'invalid']));
            return $this->redirect(['request-account-reset']);
        }
        $model = new PasswordSetup(['token' => $token]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('info', Yii::t('app', 'account-reset-success'));
            return $this->redirect('index');
        }
        return $this->render('@vendor/humanized/yii2-account/views/reset', ['model' => $model]);
    }

}

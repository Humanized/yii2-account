<?php

namespace humanized\account\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use humanized\account\models\base\Signin;
use humanized\account\models\base\Signup;

/**
 * SupplyController implements the CRUD actions for Supply model.
 */
class AuthenticationController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    private $allowSignup = true;

    public function behaviors()
    {
        return [

            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'signout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 
     * @return type
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goBack();
        }

        $signin = new Signin();
        $signup = new Signup();
        if ($signin->load(Yii::$app->request->post()) && $signin->login()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'signin-success'));

            return $this->goBack();
        }
        if ($signup->load(Yii::$app->request->post())) {
            if (NULL !== $signup->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'signup-success'));
            }
        }
        return $this->render('@vendor/humanized/yii2-account/views/authentication/index', [
                    'login' => $signin,
                    'signup' => $signup,
        ]);
    }

    public function actionConfirm($token)
    {
        $userClass = Yii::$app->user->identityClass;
        if (!$userClass::isPasswordResetTokenValid($token)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'link.error{type}{status}', ['type' => 'Confirmation', 'status' => 'expired']));
            return $this->redirect(['request-account-reset']);
        }

        if (NULL === $userClass::findByPasswordResetToken($token)) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'link.error{type}{status}', ['type' => 'Confirmation', 'status' => 'invalid']));
            return $this->redirect(['request-account-reset']);
        }

        $model = new AccountSettings(['token' => $token]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'account-confirm-success'));
            $this->redirect('index');
            return;
        }
        return $this->render('settings', ['model' => $model]);
    }

    /**
     * Signs the current user out.
     *
     * @return mixed
     */
    public function actionSignout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

}

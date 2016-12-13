<?php

namespace humanized\account\models\base;

use Yii;
use yii\base\Model;
use humanized\account\models\User;

/**
 * Password reset request form
 */
class RecoveryRequest extends Model
{

    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $identityClass = Yii::$app->user->identityClass;
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => $identityClass,
                'filter' => ['IN', 'status', [$identityClass::STATUS_ACTIVE, $identityClass::STATUS_PENDING]],
                'message' => 'There is no user with such email.'
            ],
        ];
    }

    public function save()
    {
        $identityClass = Yii::$app->user->identityClass;
        $user = $identityClass::findOne(['email' => $this->email]);
        if (isset($user)) {
            $class = '\\humanized\\account\\models\\mail\\' . ($user->status == User::STATUS_PENDING ? 'UserConfirmationMail' : 'AccountRecoveryMail');
            $email = new $class(['destination' => $user->email]);
            return $email->send();
        }
        return false;
    }

}

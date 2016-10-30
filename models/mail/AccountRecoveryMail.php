<?php

namespace humanized\account\models;

use Yii;
use humanized\mail\components\Model;

/**
 * Password reset request form
 */
class AccountRecoveryMail extends Model
{

    public $path = '@vendor/humanized/yii2-accounts/mails/account-recovery';

    public function beforeSend()
    {
        $identityClass = \Yii::$app->user->identityClass;
        /* @var $user User */
        $user = $identityClass::findOne([
                    'status' => User::STATUS_ACTIVE,
                    'email' => $this->destination,
        ]);

        if (!$user) {
            return false;
        }

        if (!$identityClass::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $this->extraParams['user'] = $user;
    }

}

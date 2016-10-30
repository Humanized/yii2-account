<?php

namespace humanized\account\models\mail;

use Yii;
use humanized\mail\components\Model;

/**
 * Password reset request form
 */
class UserConfirmationMail extends Model
{

    public $path = '@vendor/humanized/yii2-accounts/mails/user-confirmation';

    public function beforeSend()
    {
        $identityClass = \Yii::$app->user->identityClass;
        /* @var $user User */
        $user = $identityClass::findOne([
                    'status' => $identityClass::STATUS_PENDING,
                    'email' => $this->to,
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

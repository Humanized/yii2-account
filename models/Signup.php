<?php

namespace humanized\account\models;

use Yii;
use yii\base\Model;
use humanized\usermanagement\common\models\UserCrud;
use humanized\account\models\mail\MemberConfirmationMail;

/**
 * Signup form
 */
class Signup extends Model
{

    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => Yii::$app->user->identityClass, 'message' => 'This email address has already been taken.'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = UserCrud::create(['email' => $this->email]);
        if (isset($user)) {
            $confirmation = new MemberConfirmationMail(['email' => $user->email]);
            $confirmation->send();
        }
        return $user;
    }

}

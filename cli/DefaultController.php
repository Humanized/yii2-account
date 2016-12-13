<?php

/**
 * @link https://github.com/humanized/yii2-user-management
 * @copyright Copyright (c) 2016 Humanized BV Comm V
 * @license https://github.com/humanized/yii2-user-management/LICENSE.md
 */

namespace humanized\account\cli;

use humanized\account\models\base\UserCrud;

/**
 * 
 * @name Yii2 User Managment Module CLI
 * @version 1.0
 * @author Jeffrey Geyssens <jeffrey@humanized.be>
 * @package yii2-user-management
 * 
 */
class DefaultController extends \yii\console\Controller
{

    public $setPassword = false;

    public function options()
    {
        return ['setPassword'];
    }

    public function optionAliases()
    {
        return ['pswd' => 'setPassword'];
    }

    public function actionCreate($email, $username = null)
    {
        $attributes = ['email' => $email];
        if (isset($username)) {
            $attributes['username'] = $username;
        }

        if ($this->setPassword) {

            $attributes['password'] = 'debug123';
        }


        $success = UserCrud::create($attributes);
        return 0;
    }

}

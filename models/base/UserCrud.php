<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace humanized\account\models\base;

use Yii;
use yii\base\Model;

/**
 * Generic CRUD Operations for IdentityClass
 */
class UserCrud extends Model
{

    public static function create($attributes)
    {
        //E-mail is required
        if (!isset($attributes['email'])) {
            return null;
        }
        
        $userClass = Yii::$app->user->identityClass;
        $user = new $userClass();

        //Check if e-mail already exists
        $model = $userClass::find()->where(['email' => $attributes['email']])->one();
        if (!isset($model)) {
            if (!isset($attributes['username'])) {
                $attributes['username'] = uniqid('user_');
            }
            if (!isset($attributes['password'])) {
                $attributes['password'] = Yii::$app->security->generateRandomString();
                $attributes['status'] = 0;
            }
            self::setup($user, $attributes);
            return $user->save() ? $user : null;
        }
        return null;
    }

    public static function setup($model, &$attributes)
    {
        if (isset($attributes['status'])) {
            $model->status = $attributes['status'];
        }
        $model->username = $attributes['username'];
        $model->email = $attributes['email'];
        $model->setPassword($attributes['password']);
        $model->generateAuthKey();
        if ($model->status == 0) {
            $model->generatePasswordResetToken();
        }
    }

    public static function readOne($id)
    {
        return self::read(['id' => $id]);
    }

    public static function readAll($condition)
    {
        return self::read($condition, true);
    }

    public static function read($condition, $all = false)
    {
        $userClass = \Yii::$app->user->identityClass;
        $query = $userClass::find()->where($condition);
        return $all ? $query->all() : $query->one();
    }

    public static function update($id, $attributes)
    {
        $userClass = \Yii::$app->user->identityClass;
        $model = $userClass::findOne($id);
        if (isset($model)) {
            $model->setAttributes($attributes);
            return $model->save();
        }
        return false;
    }

    public static function delete($attributes)
    {
        $userClass = \Yii::$app->user->identityClass;
        $model = $userClass::findOne($attributes['id']);
        if (isset($model)) {
            $model->setAttributes($attributes);
            return $model->delete();
        }
        return false;
    }

}

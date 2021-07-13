<?php

namespace app\controllers\api;

use app\models\Users;
use Yii;

class UsersController extends SecurityController
{
    //TODO - test

    /**
     * This method registers user if he didnt exist yet
     * @param username - uniqe username
     * @param name - name
     * @param password - password
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     *          data - array with user data [token,id,authType,name]
     */
    public function actionRegister()
    {
        $username = Yii::$app->request->post('username');
        $name = Yii::$app->request->post('name');
        $password = Yii::$app->request->post('password');
        if ($username && $password && $name) {
            $checker = Users::findOne(['username' => $username]);
            if (!$checker) {
                $user = new Users();
                $user->password = Yii::$app->security->generatePasswordHash($password);
                $user->name = $name;
                $user->username = $username;
                $user->auth_key = Yii::$app->security->generateRandomString();

                if ($user->save()) {
                    return json_encode(['status' => true, 'data'=>[
                        'token' => $user->auth_key,
                        'id' => $user->id,
                        'authType'=>$user->auth_type,
                        'name'=>$user->name
                    ]]);
                } else {
                    return json_encode(['status' => false, 'message' => "Something went wrong"]);
                }
            } else {
                return json_encode(['status' => false, 'message' => "User already exists"]);
            }
        } else {
            return json_encode(['status' => false, 'message' => 'Missing parameters']);
        }
    }

    /**
     * This method logins user
     * @param username - existing username
     * @param password - current password
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     *          data - array with user data [token,id,authType,name]
     */
    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        if ($username && $password) {
            $user = Users::find()
                ->where(['username' => $username])
                ->one();
            if ($user) {
                if (Yii::$app->getSecurity()->validatePassword($password, $user->password)) {
                    $user->auth_key = Yii::$app->security->generateRandomString();
                    if ($user->save()) {
                        return json_encode(['status' => true, 'data'=>[
                            'token' => $user->auth_key,
                            'id' => $user->id,
                            'authType'=>$user->auth_type,
                            'name'=>$user->name
                        ]]);
                    } else {
                        return json_encode(['status' => false, 'message' => 'Saving token failed']);
                    }
                } else {
                    return json_encode(['status' => false, 'message' => 'Wrong password']);
                }
            } else {
                return json_encode(['status' => false, 'message' => 'User not found']);
            }
        } else {
            return json_encode(['status' => false, 'message' => 'Missing parameters']);
        }
    }

    /**
     * This method updates user info
     * @param user_id - requested user id
     * @param username - new username
     * @param password - array with current and new password ["currentPass":<password>, "newPass":<newPassword>]
     * @param name - new name
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     */
    public function actionUpdate()
    {
        $userId = Yii::$app->request->post('user_id');
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $name = Yii::$app->request->post('name');

        if ($userId && $username && $password && $name) {
            $user = Users::findOne($userId);
            $checker = Users::find()
                ->where(['username' => $username])
                ->andWhere(['!=','id',$userId])
                ->one();
            if (!$checker) {
                if (Yii::$app->security->validatePassword($password["currentPass"], $user->password)) {
                    if ($password["newPass"] !== '') {
                        $user->password = Yii::$app->security->generatePasswordHash($password["newPass"]);
                    }
                    $user->username = $username;
                    $user->name = $name;

                    if ($user->save()) {
                        return json_encode(['status' => true]);
                    } else {
                        return json_encode(['status' => false, 'warning' => $user->errors]);
                    }
                } else {
                    return json_encode(['status' => false, 'warning' => "Wrong password"]);
                }
            } else {
                return json_encode(['status' => false, 'warning' => "This username is already in use"]);
            }
        } else {
            return json_encode(['status' => false, 'warning' => 'Missing query parameters']);
        }
    }
    /**
     * This method gets user info
     * @param user_id - requested user id
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     *          data - user data [id, name, username, password, auth_key, auth_type]
     */
    public function actionGetInfo()
    {
        $userId = Yii::$app->request->post('user_id');
        if ($userId){
            $user = Users::find()->where(['id' => $userId])->asArray()->one();
            if ($user) {
                return json_encode(['status' => true, 'data' => $user]);
            } else {
                return json_encode(['status' => false, 'warning' => 'Client not found']);
            }
        } else {
            return json_encode(['status' => false, 'warning' => 'Missing query parameters']);
        }
    }
    /**
     * This method gets all users ids
     * @return false|string - json with status and data||warning.
     *          status - boolean to check whether operation was a success
     *          data - array of user data [id, name]
     */
    public function actionGetAllUsers(){
        $data = Users::find()
            ->select('id, name')
            ->asArray()
            ->all();

        if ($data){
            return json_encode(['status' => true, 'data' => $data]);
        } else {
            return json_encode(['status' => false, 'warning' => 'Users not found']);
        }
    }
}
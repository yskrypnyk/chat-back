<?php


namespace app\controllers\api;

use app\models\Users;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

class SecurityController extends BaseController
{
    public static function allowedDomains()
    {
        return [
            '*'
        ];
    }

    public function behaviors()
    {
        unset(parent::behaviors()['authenticator']);

        return array_merge(parent::behaviors(), [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => static::allowedDomains(),
                    'Access-Control-Request-Method' => ['POST'],
                    'Access-Control-Allow-Credentials' => false,
                    'Access-Control-Allow-Headers' => ['*'],
                    'Access-Control-Max-Age' => 3600,
                ],
            ],
            'authenticator' => [
                'class' => CompositeAuth::className(),
                'except' => [
                    'login',
                    'register',
                ],
            ]
        ]);
    }

    public static function allowedActions()
    {
        return [
            'api/users/login',
            'api/users/register',
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        $url = $action->controller->id . '/' . $action->id;

        if (Yii::$app->request->isOptions) {
            return parent::beforeAction($action);
        }

        if (in_array($url, self::allowedActions()))
        {
            return parent::beforeAction($action);
        }

        $token = Yii::$app->request->headers->get('Authorization');
        if ($token) {
            $isValid = Users::validateToken(base64_decode($token));
            if ($isValid) {
                return parent::beforeAction($action);
            } else {
                throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
            }
        } else {
            throw new UnauthorizedHttpException('Missing token');
        }
    }

}
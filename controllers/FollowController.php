<?php

namespace app\controllers;

use app\lib\repository\UserRepository;
use app\lib\service\Follow\FollowService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class FollowController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => null,
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionSubscribe()
    {
        $username = Yii::$app->request->get('username');
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $userTo = $userRepository->getByUsername($username);
        if (!$userTo) {
            //return exception
            exit;
        }
        $userFrom = $userRepository->getLoggedUser();
        $followService = \Yii::$container->get(FollowService::class);
        $result = $followService->subscribe($userFrom, $userTo);
        if (!$result) {
            //echo bad
        }
        sleep(1);
        return $this->redirect('/');
        //echo good
    }

    /**
     * @return \yii\web\Response
     */
    public function actionUnsubscribe()
    {
        $username = Yii::$app->request->get('username');
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $userTo = $userRepository->getByUsername($username);
        if (!$userTo) {
            //return exception
            exit;
        }
        $userFrom = $userRepository->getLoggedUser();
        $followService = \Yii::$container->get(FollowService::class);
        $result = $followService->unsubscribe($userFrom, $userTo);
        if (!$result) {
            //echo bad
        }
        sleep(1);
        return $this->redirect('/');
        //echo good
    }

    /**
     * @return \yii\web\Response
     */
    public function actionDelete()
    {
        $username = Yii::$app->request->get('username');
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $userFrom = $userRepository->getByUsername($username);
        if (!$userFrom) {
            //return exception
            exit;
        }
        $loggedUser = $userRepository->getLoggedUser();
        /** @var FollowService $followService */
        $followService = \Yii::$container->get(FollowService::class);
        $result = $followService->delete($userFrom, $loggedUser);
        if (!$result) {
            //echo bad
        }
        sleep(1);
        return $this->redirect('/');
        //echo good
    }
}

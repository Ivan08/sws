<?php

namespace app\controllers;

use app\lib\repository\FollowRepository;
use app\lib\repository\UserRepository;
use app\lib\service\Feed\FeedService;
use app\lib\service\Follow\FollowService;
use app\lib\service\Post\PostService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class MainController
 * @package app\controllers
 */
class MainController extends Controller
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
     * @return string
     */
    public function actionFeed()
    {
        $page = (int)Yii::$app->request->get('page', 1);
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getLoggedUser();
        /** @var FeedService $feedService */
        $feedService = Yii::$container->get(FeedService::class);
        $feed = $feedService->getFeed($user, $page);
        $data = [
            'feed' => $feed
        ];
        if (Yii::$app->request->getIsAjax()) {
            $this->layout = false;
            return $this->render('_feedPost', $data);
        }

        return $this->render('feed', $data);
    }

    /**
     * @return string
     */
    public function actionPosts()
    {
        $page = (int)Yii::$app->request->get('page', 1);
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getLoggedUser();
        if (!$user) {
            //return exception
            exit;
        }
        /** @var PostService $postService */
        $postService = \Yii::$container->get(PostService::class);
        $posts = $postService->getPosts($user, $page);
        $data = [
            'posts' => $posts
        ];
        if (Yii::$app->request->getIsAjax()) {
            $this->layout = false;
            return $this->render('_posts', $data);
        }
        return $this->render('posts', $data);
    }

    /**
     * @return string
     */
    public function actionFollowing()
    {
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getLoggedUser();
        /** @var FollowRepository $followRepository */
        $followRepository = \Yii::$container->get(FollowRepository::class);
        $following = $followRepository->getFollowing($user->getId());

        return $this->render('following', [
            'following' => $following
        ]);
    }

    /**
     * @return string
     */
    public function actionFollowers() {
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getLoggedUser();
        /** @var FollowRepository $followRepository */
        $followRepository = \Yii::$container->get(FollowRepository::class);
        $followers = $followRepository->getFollowers($user->getId());

        return $this->render('followers', [
            'followers' => $followers
        ]);
    }

    /**
     * @return string
     */
    public function actionUserProfile()
    {
        $username = Yii::$app->request->get('username');
        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getByUsername($username);
        $loggedUser = $userRepository->getLoggedUser();
        $page = (int)Yii::$app->request->get('page', 1);
        if (!$user) {
            //return exception
            exit;
        }
        $postService = \Yii::$container->get(PostService::class);
        $followService = \Yii::$container->get(FollowService::class);
        $posts = $postService->getPosts($user, $page);
        return $this->render('userProfile', [
            'user' => $user,
            'posts' => $posts,
            'isSubscribe' => $followService->isSubscribe($loggedUser, $user)
        ]);
    }
}

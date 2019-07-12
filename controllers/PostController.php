<?php

namespace app\controllers;

use app\lib\form\PostForm;
use app\lib\repository\PostRepository;
use app\lib\repository\UserRepository;
use app\lib\service\Post\PostService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class PostController extends Controller
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
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PostForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            /** @var UserRepository $userRepository */
            $userRepository = \Yii::$container->get(UserRepository::class);
            $user = $userRepository->getLoggedUser();

            $postService = \Yii::$container->get(PostService::class);
            $post = $postService->add($user, $model->toArray());
            return $this->goBack();
        }
        return $this->render('create', [
            'model' => $model,
        ]);

    }

    /**
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionDelete()
    {
        $postId = \Yii::$app->request->get('id');
        /** @var PostRepository $postRepository */
        $postRepository = \Yii::$container->get(PostRepository::class);
        $post =  $postRepository->getById($postId);

        /** @var UserRepository $userRepository */
        $userRepository = \Yii::$container->get(UserRepository::class);
        $user = $userRepository->getLoggedUser();

        //Check IfUserAccessPost
        if ($user->getId() != $post->getUserId()) {
            throw new \yii\web\NotFoundHttpException('Post not found');
        }
        $postService = \Yii::$container->get(PostService::class);
        $postService->delete($post);

        return $this->redirect('/posts');
    }
}

<?php

namespace app\controllers;

use app\lib\repository\UserRepository;
use app\lib\service\Auth\AuthService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            /** @var UserRepository $userRepository */
            $userRepository = \Yii::$container->get(UserRepository::class);
            $user = $userRepository->getByUsername($form->username);
            if (!$user) {
                $form->addError('username', 'Incorrect username or password.');
            }
            /** @var AuthService $authService */
            $authService = \Yii::$container->get(AuthService::class);
            if (!$form->hasErrors() && !$authService->validatePassword($user, $form->password)) {
                $form->addError('password', 'Incorrect username or password.');
            }
            if (!$form->hasErrors()) {
                $authService->login($user, $form->rememberMe);
                return $this->goHome();
            }
        }

        $form->password = '';
        return $this->render('login', [
            'model' => $form,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\lib\service\Follow\FollowService;
use app\lib\service\Post\PostService;
use app\lib\service\User\UserService;
use app\models\Follow;
use app\models\Post;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class InitController extends Controller
{
    /**
     * Create User[0001-9999]\pass
     * @return int Exit code
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     * @throws \Throwable
     */
    public function actionCreateUser()
    {
        User::deleteAll();
        Post::deleteAll();
        Follow::deleteAll();
        $users = [];
        $userService = \Yii::$container->get(UserService::class);
        $postService = \Yii::$container->get(PostService::class);
        $followService = \Yii::$container->get(FollowService::class);
        for ($i = 1; $i < 100; $i++) {
            $userData = [
                'username' => sprintf('User%04d', $i),
                'password' => 'pass',
                'authKey' => md5($i . microtime()),
                'accessToken' => md5($i . microtime()),
            ];
            $user = $userService->add($userData);
            $users[] = $user;
            echo sprintf("Create %s:%s\n", $user->getUsername(), $user->getId());
        }

        echo sprintf("Create follow\n");
        foreach ($users as $user) {
            for ($i = 1; $i < 5; $i++) {
                do {
                    $userTo = $users[random_int(0, count($users) - 1)];
                } while ($user->getId() == $userTo->getId());

                $followService->subscribe($user, $userTo);
            }
        }

        echo sprintf("Create posts\n");
        for ($j = 0; $j < 2000; $j++) {
            $postData = [
                'message' => 'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века.'
            ];
            $postService->add($users[random_int(0, count($users) - 1)], $postData);
        }

        return ExitCode::OK;
    }
}

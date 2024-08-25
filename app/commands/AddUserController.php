<?php

declare(strict_types=1);

namespace app\commands;

use app\models\User\User;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Exception;


class AddUserController extends Controller
{

    /**
     * @throws Exception
     */
    public function actionIndex(string $username, string $password): int
    {
        $user = new User();
        $user->username = $username;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->generatePasswordResetToken();
        $user->email = $username . '@' . $username . '.com';
        $user->status = User::STATUS_ACTIVE;
        try {
            if ($user->save()) {
                $this->stdout("Пользователь {$user->username} успешно добавлен\n");
                return ExitCode::OK;
            }
        } catch (Exception $e) {
            $this->stderr("Пользователь {$user->username} не добавлен. Ошибка: {$e->getMessage()}\n");
        }

        return ExitCode::UNSPECIFIED_ERROR;
    }
}

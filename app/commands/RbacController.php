<?php

namespace app\commands;

use app\models\User\User;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $adminId = User::findByUsername('admin')?->getId();
        $userId = User::findByUsername('user')?->getId();

        $auth = Yii::$app->authManager;

        // добавляем разрешение "createBook"
        $createBook = $auth->createPermission('createBook');
        $createBook->description = 'Добавление книги';
        $auth->add($createBook);

        // добавляем разрешение "updateBook"
        $updateBook = $auth->createPermission('updateBook');
        $updateBook->description = 'Изменение книги';
        $auth->add($updateBook);

        // добавляем разрешение "deleteBook"
        $deleteBook = $auth->createPermission('deleteBook');
        $deleteBook->description = 'Удаление книги';
        $auth->add($deleteBook);

        // добавляем разрешение "createAuthor"
        $createAuthor = $auth->createPermission('createAuthor');
        $createAuthor->description = 'Добавление автора';
        $auth->add($createAuthor);

        // добавляем разрешение "updateAuthor"
        $updateAuthor = $auth->createPermission('updateAuthor');
        $updateAuthor->description = 'Изменение автора';
        $auth->add($updateAuthor);

        // добавляем разрешение "deleteAuthor"
        $deleteAuthor = $auth->createPermission('deleteAuthor');
        $deleteAuthor->description = 'Удаление автора';
        $auth->add($deleteAuthor);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $createBook);
        $auth->addChild($user, $updateBook);
        $auth->addChild($user, $createAuthor);
        $auth->addChild($user, $updateAuthor);
        $auth->addChild($user, $deleteAuthor);
        $auth->addChild($user, $deleteBook);


        // добавляем роль "admin"
        // а также все разрешения роли "author"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $user);

        /// Назначение ролей пользователям.
        if ($userId) {
            $auth->assign($user, $userId);
        }
        if ($adminId) {
            $auth->assign($admin, $adminId);
        }
    }
}
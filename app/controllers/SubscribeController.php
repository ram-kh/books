<?php

namespace app\controllers;

use app\models\Author\Author;
use app\models\Book\Book;
use app\models\Subscribe\Subscribe;
use app\models\Subscribe\SubscribeForm;
use Carbon\Carbon;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SubscribeController implements the CRUD actions for Subscribe model.
 */
class SubscribeController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ],
        );
    }

    /**
     * Redirects to the home page.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('subscribe');
    }

    /**
     * Displays a single Subscribe model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subscribe model.
     * @return bool
     */
    public function create(array $modelArray = []): bool
    {
        $now = Carbon::now()->toDateTimeString();
        $model = new Subscribe();
        $model->load($modelArray, '');
        $model->created_at = $now;
        $model->updated_at = $now;

        return $model->save();
    }

    /**
     * Updates an existing Subscribe model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id): Response|string
    {
        if (!Yii::$app->user->can('updateSubscribe')) {
            Yii::$app->session->setFlash('warning', 'У вас нет прав для редактирования автора.');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Subscribe model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id): Response
    {
        if (!Yii::$app->user->can('deleteSubscribe')) {
            Yii::$app->session->setFlash('warning', 'У вас нет прав для удаления автора.');
            return $this->redirect(['index']);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public static function checkSubscribe(Book $book): void
    {
        if (!$authors = $book->authors) {
            return;
        }

        $authorIds = ArrayHelper::getColumn($authors, 'id');

        $subscribers = Subscribe::find()
            ->where(['author_id' => $authorIds])
            ->all();

        if (empty($subscribers)) {
            return;
        }

        static::sendSms($subscribers, $book);
        static::sendEmail($subscribers, $book);
    }

    /**
     * Displays subscribe page.
     *
     * @return Response|string
     */
    public function actionSubscribe(): Response|string
    {
        $modelForm = new SubscribeForm();
        if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate()) {
            if (!$this->create((array)$modelForm)) {
                Yii::$app->session->setFlash(
                    'error',
                    'Произошла ошибка при подписке на рассылку. Попробуйте еще раз.',
                );
            } else {
                Yii::$app->session->setFlash('subscribeFormSubmitted');
            }

            return $this->refresh();
        }

        return $this->render('subscribe', [
            'model' => $modelForm,
            'authors' => ArrayHelper::map(
                Author::findAllAsArray(),
                'id',
                'fio',
            ),
        ]);
    }

    public static function sendEmail(array $receivers, Book $book): void
    {
        if (empty($receivers)) {
            return;
        }

        $authors = $book->getAuthorsAsString();

        foreach ($receivers as $receiver) {

            Yii::$app->mailer->compose()
                ->setTo($receiver->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$receiver->email => $receiver->name])
                ->setSubject('Подписка на рассылку')
                ->setTextBody(
                    'Вы подписаны на рассылку уведомлений о поступлении новых книг автора: ' . $authors . ' на адрес: ' . $receiver->email . ' и телефон: ' . $receiver->phone . ' и комментариями: ' . $receiver->comment . '\n'
                    . 'Поступила новая книга: ' . $book->title . ' на сайте ' . Yii::$app->name . '.\n'
                    . 'Для ознакомления с книгой, перейдите по ссылке: ' . Yii::$app->urlManager->createAbsoluteUrl(['book/view', 'id' => $book->id]),
                )
                ->send();
        }
    }

    public static function sendSms(array $receivers, Book $book): void
    {
        $apikey = Yii::$app->params['apiSmsPilotKey'];
        $sender = Yii::$app->params['sender'];


        foreach ($receivers as $receiver) {
            $message = "Поступила новая книга: {$book->title} автора: {$book->authorsAsString}. Подробнее по ссылке: " . Yii::$app->urlManager->createAbsoluteUrl(['book/view', 'id' => $book->id]);
            $phone = str_replace(['+', '(', ')', ' ', '-'], '', $receiver['phone']) ?: $receiver['phone'];
            $url = 'https://smspilot.ru/api.php'
                . '?send=' . urlencode($message)
                . '&to=' . urlencode($phone)
                . '&from=' . $sender
                . '&apikey=' . $apikey
                . '&format=json';

            $json = file_get_contents($url);

            $response = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            if (isset($response->error)) {
                Yii::error($response->error->description_ru, __METHOD__);
            }
        }
    }


    /**
     * Finds the Subscribe model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Subscribe the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Subscribe::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }
}

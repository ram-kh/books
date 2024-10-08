<?php

namespace app\controllers;

use app\models\Book\Book;
use app\models\Book\BookSearch;
use app\models\File\File;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
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
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Book model.
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
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('createBook')) {
            Yii::$app->session->setFlash('warning', 'У вас нет прав для добавления книг.');
            return $this->redirect(['index']);
        }

        $model = new Book();

        if ($this->request->isPost) {
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if (!empty($_FILES['Book']['name']['picture'])) {
                $file = new File();
                $file->file = UploadedFile::getInstance($model, 'picture');
                $file->upload();
                $model->picture_id = $file->id;
            }

            if ($model->load($this->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Книга добавлена.');
                SubscribeController::checkSubscribe($model);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,

        ]);
    }

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('updateBook')) {
            Yii::$app->session->setFlash('warning', 'У вас нет прав для редактирования книг.');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        if ($this->request->isPost) {
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if (!empty($_FILES['Book']['name']['picture'])) {
                $file = new File();
                $file->file = UploadedFile::getInstance($model, 'picture');
                $file->upload();
                $model->picture_id = $file->id;
            }

            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('deleteBook')) {
            Yii::$app->session->setFlash('warning', 'У вас нет прав для удаления книг.');
            return $this->redirect(['index']);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }
}

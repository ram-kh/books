<?php

namespace app\controllers;

use app\models\Author\Author;
use app\models\Author\AuthorSearch;
use app\models\Book\Book;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AuthorController implements the CRUD actions for Author model.
 */
class AuthorController extends Controller
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
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ],
        );
    }

    /**
     * Lists all Author models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AuthorSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Author model.
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
     * Creates a new Author model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('createAuthor')) {
            Yii::$app->session->setFlash('warning', 'У вас нет прав для добавления автора.');
            return $this->redirect(['index']);
        }

        $model = new Author();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
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
     * Updates an existing Author model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('updateAuthor')) {
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
     * Deletes an existing Author model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('deleteAuthor')) {
            Yii::$app->session->setFlash('warning', 'У вас нет прав для удаления автора.');
            return $this->redirect(['index']);
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionTop10 (?int $year = null)
    {
        if ($year === null) {
            $year = (int)date('Y');
        }

        $bookIds = (new Query())
            ->select('book_id')
            ->from('book_2_author')
            ->where(['author_id' => 'author.id']);

        $query = (new Query())
            ->select('id')
            ->from('book')
            ->where(['id' => $bookIds])
            ->andWhere(['year' => $year]);

        $data = Author::find()
            ->select([
                'rating' => $query->count(),
            ])
            ->from('author')
            ->limit(10)
            ->orderBy('rating DESC')
            ->asArray()
            ->all();

        return $this->render('top10', [
            'authors' => Author::find()
                ->where(['year' => $year])
                ->orderBy('rating DESC')
                ->limit(10)
                ->all(),
            'year' => $year
        ]);
    }

    /**
     * Finds the Author model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Author the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Author::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрошенная страница не существует.');
    }
}

<?php

namespace app\controllers;

use Yii;
use app\models\MArticleMult;
use app\models\MArticleMultArticle;
use app\models\search\MArticleMultArticleSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticlemultarticleController implements the CRUD actions for MArticleMultArticle model.
 */
class ArticlemultarticleController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all MArticleMultArticle models.
     * @return mixed
     */
    public function actionIndex($article_mult_id)
    {
        $articleMult = MArticleMult::findOne($article_mult_id);
        $searchModel = new MArticleMultArticleSearch();
        $searchModel->article_mult_id = $article_mult_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'articleMult' => $articleMult,
        ]);
    }

    /**
     * Displays a single MArticleMultArticle model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MArticleMultArticle model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($article_mult_id)
    {
        $articleMult = MArticleMult::findOne($article_mult_id);
        $model = new MArticleMultArticle();
        $model->article_mult_id = $article_mult_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->article_mult_article_id]);
            return $this->redirect(['index', 'article_mult_id'=>$model->article_mult_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'articleMult' => $articleMult,
            ]);
        }
    }

    /**
     * Updates an existing MArticleMultArticle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'article_mult_id'=>$model->article_mult_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MArticleMultArticle model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index', 'article_mult_id'=>$model->article_mult_id]);
    }

    /**
     * Finds the MArticleMultArticle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MArticleMultArticle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MArticleMultArticle::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

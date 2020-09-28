<?php
namespace frontend\controllers;

use common\models\Book;
use frontend\models\BookSearch;
use yii\helpers\Html;
use yii\web\Controller;

class SiteController extends Controller
{
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

    public function actionIndex()
    {
        $searchModel = new BookSearch;
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index.twig', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'photoCallback' => function ($book) {
                /** @var Book $book */
                return Html::img("https://" . $book->photo_url, ['class' => 'img-thumbnail', 'style' => 'width: 80px;']);
            },
        ]);
    }
}

<?php

namespace backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Module;
use yii\web\Request;
use backend\models\Apple;
use backend\models\forms\EatPercentForm;
use backend\services\AppleService;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    const GENERATE_MIN = 5;
    const GENERATE_MAX = 15;

    private AppleService $appleService;

    public function __construct(
        string $id, 
        Module $module, 
        AppleService $appleService, 
        $config = []
    ) {
        parent::__construct($id, $module, $config);    
        $this->appleService = $appleService;
    }

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
            ]
        );
    }

    /**
     * Lists all Apple models.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'dataProvider' => $this->getDataProvider(),
        ]);
    }

    public function actionGenerate(Request $request)
    {
        $this->appleService
             ->clean()
             ->generate(self::GENERATE_MIN, self::GENERATE_MAX);

        if ($request->isPjax) {
            return $this->renderPartial('index', [
                'dataProvider' => $this->getDataProvider(),
            ]);
        }

        return $this->redirect(['index']);
    }

    public function actionFall(Request $request, int $id)
    {
        $model = $this->findModel($id);
        $model->fallToGround();

        if ($request->isPjax) {
            return $this->renderPartial('index', [
                'dataProvider' => $this->getDataProvider(),
            ]);
        }

        return $this->redirect(['index']);
    }

    public function actionEat(Request $request, int $id)
    {
        $model = $this->findModel($id);
        $form = new EatPercentForm($model);

        if ($request->isPost && $form->load($request->post()) && $form->validate()) {
            $model->eat($form->percent);
        } 

        if ($request->isPjax) {
            return $this->renderPartial('index', [
                'dataProvider' => $this->getDataProvider(),
            ]);
        }

        return $this->redirect(['index']);
    }


    private function getDataProvider(): ActiveDataProvider
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $dataProvider;
    }

    /**
     * Deletes an existing Apple model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if ($request->isPjax) {
            return $this->renderIndex();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

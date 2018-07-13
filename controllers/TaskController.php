<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;


/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
{
    public $modelClass = 'app\models\Task';

    public static function allowedDomains() {
        return [
             '*',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $this->enableCsrfValidation = false;
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['DELETE'],
                ],
            ],
            'corsFilter'  => [
                'class' => \yii\filters\Cors::className(),
                'cors'  => [
                    // restrict access to domains:
                    'Origin'                           => static::allowedDomains(),
                    'Access-Control-Request-Method'    => ['POST'],
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Max-Age'           => 3600,                 // Cache (seconds)
                ],
            ],
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = Task::find()->all();
        $response->statusCode = 200;
        return $response;
    }

    /**
     * Displays a single Task model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $taskId = Task::findOne($id);
        $response->data = $taskId;
        $response->statusCode = 200;
        return $response;
    }

    /**
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Task();

        if (Yii::$app->request->post() != null) {
            $move = (UploadedFile::getInstanceByName('image'));
            $file_name = null;
            if ($move) {
                $file_name = time() . '_' . rand(100000, 100000000) . '.' . $move->extension;
                $move->saveAs('uploads/' . $file_name);
            }
            $data = Yii::$app->request->post();

            $task = ['Task' => [
                'title' => isset($data['title']) ? $data['title'] : null,
                'image' => $file_name,
                'description' => isset($data['description']) ? $data['description'] : null,
                'date_start' => isset($data['date_start']) ? $data['date_start'] : null,
            ]];
            $response = Yii::$app->response;
            $response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->load($task) && $model->validate()) {
                $model->save();
                $response->statusCode = 201;
                return $response;
            } else {
                $response = $model->errors;
                return $response;
            }
        }
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $data = Yii::$app->request->post();
        $move = (UploadedFile::getInstanceByName('image'));

        if ($move) {
            if ($model->image && file_exists('./uploads/' . $model->image)) {
                unlink('./uploads/' . $model->image);
            }
            $file_name = time() . '_' . rand(100000, 100000000) . '.' . $move->extension;
            $move->saveAs('uploads/' . $file_name);
        }

        $task = ['Task' => [
            'title' => isset($data['title']) ? $data['title'] : null,
            'image' => isset($file_name) ? $file_name : $model->image,
            'description' => isset($data['description']) ? $data['description'] : null,
            'date_start' => isset($data['date_start']) ? $data['date_start'] : null,
            'status' => isset($data['status']) ? $data['status'] : $model->status,
        ]];
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        if ($model->load($task) && $model->validate()) {
            $model->save();
            $response->statusCode = 201;
            return $response;
        } else {
            $response = $model->errors;
            return $response;
        }
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->image && file_exists('./uploads/' . $model->image))
        {
            unlink('./uploads/' . $model->image);
        }
        $response = Yii::$app->response;
        $this->findModel($id)->delete();
        $response->statusCode = 204;
        return $response;
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

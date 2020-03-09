<?php
namespace backend\controllers;

use backend\src\Saver;
use Yii;
use backend\src\Apple;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'fall', 'eat', 'remove'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays apples.
     *
     * @return string
     */
    public function actionIndex()
    {
        $count = Yii::$app->request->post('count');
        $apples = [];
        if($count)
        {
            $saver = new Saver();
            for ($i = 1; $i <= $count; $i++)
            {
                $apple = new Apple();
//                $apples[] = $apple;
                $saver->insertToDb($apple);
            }
            return $this->refresh();
        }

        $db_apples = Yii::$app->db->createCommand("SELECT * FROM apples")->queryAll();
        if($db_apples)
        {
            foreach($db_apples as $db_apple) {
                $apple = new Apple($db_apple);
                $apples[] = $apple;
            }

        }

        return $this->render('index', ['count' => $count, 'apples' => $apples]);
    }

    /**
     * Уронить яблоко.
     *
     * @return redirect
     */
    public function actionFall($id)
    {
        $saver = new Saver();
        $apple_db = $saver->findById($id);
        $apple = new Apple($apple_db);
        $apple->fall();
        $saver->updateToDb($apple);

        return $this->redirect(['index']);
    }

    /**
     * Съесть часть яблока.
     *
     * @return redirect
     */
    public function actionEat($id, $percent)
    {
        $saver = new Saver();
        $apple_db = $saver->findById($id);
        $apple = new Apple($apple_db);
        $apple->eat($percent);
        $saver->updateToDb($apple);

        return $this->redirect(['index']);
    }

    /**
     * Удалить яблоко.
     *
     * @return redirect
     */
    public function actionRemove($id)
    {
        $saver = new Saver();
        $apple_db = $saver->findById($id);
        $apple = new Apple($apple_db);
        $apple->remove();
        $saver->removeFromDb($apple);

        return $this->redirect(['index']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}

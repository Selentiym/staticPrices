<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.07.2016
 * Time: 16:25
 */
class LoginController extends Controller
{
    public $loginFormClass = 'ULoginForm';
    public $defaultAction = 'login';
    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        if (Yii::app() -> user -> getState('logged')) {
            $this -> redirect(Yii::app() -> baseUrl.'/');
        }
        $modelClass = $this -> loginFormClass;
        $model=new $modelClass();

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='loginForm')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        if (!Yii::app() -> user -> isGuest) {
            $this -> redirect(Yii::app()->baseUrl);
        }
        // collect user input data
        if(isset($_POST[get_class($model)]))
        {
            $model->attributes=$_POST[get_class($model)];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login()) {
                Yii::app() -> user -> setState('logged', 1);
                $this->redirect(Yii::app()->baseUrl . '/');
            }
        }
        // display the login form
        $this->renderPartial('login',array('model'=>$model));
    }
    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app() -> user -> setState('logged', 0);
        Yii::app()->user->logout();
        Yii::app() -> user -> raiseEvent('onLogout',new CEvent($this));
        $this->redirect(Yii::app()->homeUrl);
    }
    public function actionpss(){
        echo CPasswordHelper::hashPassword('kicker');
    }
    public function actionAddRules() {
        //print_r(Yii::app() -> getAuthManager());
        $auth = Yii::app()->authManager;
        $auth->clearAll();

        //$isParent = 'return Yii::app() -> user -> getId()==$params["user"] -> id_parent';
        //$auth->createOperation('viewChildUserCabinet', 'view your child user\'s cabinet.', $isParent);

        //$auth -> createOperation('administrateTask', 'Accept or decline texts corresponding to the task.', 'return ((User::logged() -> id == $params["task"] -> id_editor)||(Yii::app() -> user -> checkAccess("admin")));');

        $admin = $auth->createRole('admin');
        /*$editor = $auth->createRole('editor');
        $author = $auth->createRole('author');

        $editor->addChild('administrateTask');
        $editor->addChild('author');
        $admin->addChild('editor');
*/
        $this->AddAdminUser();
        //$auth -> createOperation('viewOwnUserCabinet', 'view your own cabinet.', $bizRule);
    }

    public function AddAdminUser() {
        $auth = Yii::app()->authManager;
        $admin = User::model()->findByAttributes(array('username' => 'shubinsa'));
        /*$editor = User::model()->findByAttributes(array('username' => 'nikita'));
        $author1 = User::model()->findByAttributes(array('username' => 'anna'));
        $author2 = User::model()->findByAttributes(array('username' => 'nastya'));*/

        $auth->assign('admin', $admin->id);
        /*$auth->assign('editor', $editor->id);
        $auth->assign('author', $author1->id);
        $auth->assign('author', $author2->id);*/
    }//*/
}
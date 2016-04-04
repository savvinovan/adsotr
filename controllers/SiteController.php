<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Corp;
use yii\helpers\Url;
use app\components\Helper;

class SiteController extends Controller
{

    function init() {
        session_start();
    }
    
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionStep1() {
        $request = Yii::$app->request;
        $corp = new Corp();
        $data = [];
        if($request->isPost) {
          $modData = $corp->extractPersonalData($request->bodyParams);
          $checkResult = $corp->checkStep1($modData);
          if($checkResult['error'] == '0') {
            $corp->saveStep1($modData);
            $_SESSION['isAllowedStep2'] = '1';
            Controller::redirect(Url::toRoute('/site/step2'));
          } else {
            $data['result'] = $checkResult;
          }
          $data['preResponse'] = $_REQUEST;
        }
        return $this->render('step1', $data);
    }

    public function actionStep2() {
      $corp = new Corp();
      if($_SESSION['isAllowedStep2'] == '1') {
        $request = Yii::$app->request;
        $reqData = $request->bodyParams;
        $data['logins'] = $corp->getLogins($_SESSION['personalData']['fio']);
        if($request->isPost) {
          $data['result'] = $corp->checkStep2($reqData);
          if($data['result']['error'] == '0') {
            $corp->saveStep2($reqData);
            $_SESSION['isAllowedStep3'] = '1';
            Controller::redirect(Url::toRoute('/site/step3'));
          } 
        }
        return $this->render('step2', $data);
      } else {
        Controller::redirect(Url::toRoute('/site/step1'));
      }
    }
    
    public function actionStep3() {
        $corp = new Corp();
        if($_SESSION['isAllowedStep3'] == '1') {
          $data['result'] = $corp->registerAdSotr($_SESSION['personalData']);
          return $this->render('step3', $data);
        } else {
          Controller::redirect(Url::toRoute('/site/step1'));
        }
    }
    
    public function actionRestorepassword() {
        $corp = new Corp();
        $corp->sendMailRestorationLink();
    }
    
    public function actionShowrestorepassword() {
        return $this->render('restore_password');
    }
    
    public function actionActivateuser() {
      $corp = new Corp();
      $corp->activateUser($_REQUEST);
    }
    
}

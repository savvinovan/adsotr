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
      if(!empty($_SESSION['personalData'])) {
        $data['data'] = $_SESSION['personalData'];
      }
      if($request->isPost) {
        unset($_SESSION['personalData']);
        unset($_SESSION['isAllowedStep2']);
        unset($_SESSION['isAllowedStep3']);
        $modData = $corp->extractPersonalData($request->bodyParams);
        $data['result'] = $corp->checkStep1($modData);
        if($data['result']['error'] == '0') {
          $corp->saveStep1($modData);
          $_SESSION['isAllowedStep2'] = '1';
          Controller::redirect(Url::toRoute('/site/step2'));
        } else {
          $data['data'] = $modData;
        }
      }
      return $this->render('step1', $data);
    }

    public function actionStep2() {
      $request = Yii::$app->request;
      $corp = new Corp();
      $data = [];
      if($_SESSION['isAllowedStep2'] == '1') {
        $reqData = $request->bodyParams;
        $data['logins'] = $corp->getLogins($_SESSION['personalData']['fio']);
        if(!empty($_SESSION['personalData'])) {
          $data['data'] = $_SESSION['personalData'];
        }
        if($request->isPost) {
          unset($_SESSION['personalData']['password']);
          unset($_SESSION['personalData']['passwordrepeat']);
          unset($_SESSION['personalData']['email']);
          unset($_SESSION['isAllowedStep3']);
          $data['result'] = $corp->checkStep2($reqData);
          if($data['result']['error'] == '0') {
            $corp->saveStep2($reqData);
            $_SESSION['isAllowedStep3'] = '1';
            Controller::redirect(Url::toRoute('/site/step3'));
          } else {
            $data['data'] = $reqData;
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
      $corp->activateAdSotr($_REQUEST['link']);
    }
    
    public function actionCreateadunits() {
      $corp = new Corp();
      $corp->createAdUnits();
    }
    
}

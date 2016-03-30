<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Corp;
use yii\helpers\Url;

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
        $data = [];
        if($request->isPost) {
          $corp = new Corp();
          $modData = $corp->extractPersonalData($request->bodyParams);
          $checkResult = $corp->checkStep1($modData);
          if($checkResult['error'] == '0') {
            $_SESSION['step1_fio'] = $request->getBodyParam('fio');
            Controller::redirect(Url::toRoute('/site/step2'));
          } else {
            $data['result'] = $checkResult;
          }
          $data['preResponse'] = $_REQUEST;
        }
        return $this->render('step1', $data);
    }

    public function actionStep2() {
      $request = Yii::$app->request;
      $corp = new Corp();
      $data['logins'] = $corp->getLogins($_SESSION['step1_fio']);
      if($request->isPost) {
        $checkResult = $corp->checkStep1($request);
        if($checkResult['error'] == '0') {
            Controller::redirect(Url::toRoute('/site/step3'));
          } else {
            $data['result'] = $checkResult;
          }
      }
      return $this->render('step2', $data);
    }
    
    public function actionStep3() {
        $corp = new Corp();
        if($_SESSION['isPassedSteps'] == '1') {
          $corp->createAdSotrPeopleID();
          $corp->sendMailActivationLink();
          return $this->render('step3');
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
}

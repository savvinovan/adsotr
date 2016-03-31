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
            $_SESSION['personalData']['Surname'] = $modData['Surname'];
            $_SESSION['personalData']['Name'] = $modData['Name'];
            $_SESSION['personalData']['Patronymic'] = $modData['Patronymic'];
            $_SESSION['personalData']['DocSerial'] = $modData['DocSerial'];
            $_SESSION['personalData']['BornDate'] = $modData['BornDate'];
            $_SESSION['personalData']['DocNo'] = $modData['DocNo'];
            $_SESSION['personalData']['fio'] = $request->getBodyParam('fio');
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
      if($_SESSION['isAllowedStep2'] == '1') {
        $request = Yii::$app->request;
        $reqData = $request->bodyParams;
        $corp = new Corp();
        $data['logins'] = $corp->getLogins($_SESSION['personalData']['fio']);
        if($request->isPost) {
          $checkResult = $corp->checkStep2($reqData);
          if($checkResult['error'] == '0') {
            $_SESSION['personalData']['password'] = $reqData['password'];
            $_SESSION['personalData']['email'] = $reqData['email'];
            $_SESSION['personalData']['logonname'] = $reqData['logonname'];
            $_SESSION['isAllowedStep3'] = '1';
            Controller::redirect(Url::toRoute('/site/step3'));
          } else {
            $data['result'] = $checkResult;
          }
        }
        return $this->render('step2', $data);
      } else {
        Controller::redirect(Url::toRoute('/site/step1'));
      }
    }
    
    public function actionStep3() {
        if($_SESSION['isAllowedStep3'] == '1') {
          $corp = new Corp();
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

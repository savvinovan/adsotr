<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Регистрация сотрудников в WiFi';
?>
<div class="step1-bg__div">
  <div class="register-form__div">
    <div class="center-text__div">
      <h3 class="registration-title__h3">ПРОВЕРКА ДАННЫХ</h3>
    </div>
    <form class="form-horizontal" method="POST" action="<?=Url::toRoute('/site/step1')?>">
      <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
      <div class="form-group">
        <label for="fio" class="col-sm-4 control-label">Фамилия, имя, отчество</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="fio" placeholder="Введите ваше ФИО" name="fio" value='<?=$_SESSION['personalData']['fio']?>'/>
        </div>
      </div>
      <div class="form-group">
        <label for="borndate" class="col-sm-4 control-label">Дата рождения</label>
        <div class="col-sm-8">
          <input type="text" class="form-control date" id="borndate" placeholder="Введите дату рождения" name="BornDate" value='<?=$_SESSION['personalData']['BornDate']?>' />
        </div>
      </div>
      <div class="form-group">
        <label for="docserial" class="col-sm-4 control-label">Паспорт гражданина РФ</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="docserialno" placeholder="Введите серию и номер паспорта" name="docserialno" value='<?=$_SESSION['personalData']['DocSerial']?> <?=$_SESSION['personalData']['DocNo']?>' />
        </div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-8">
          <button type="submit" class="btn btn-default step1-submit__button">Проверить данные</button>
        </div>
      </div>
    </form>
    <?php if(isset($result['message'])) { ?>
      <div class="error"><?=$result['message']?></div>
    <?php } ?>
  </div>
</div>

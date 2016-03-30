<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Регистрация сотрудников в WiFi';
?>
<div class="login-choose-form">
  <h3>Восстановление пароля</h3>
  <p>Введите вашу почту, которую вы ввели при регистрации</p>
  <form class="form-horizontal" method="POST" action="<?=Url::toRoute('/site/restorepassword')?>">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <div class="form-group">
      <label for="fio" class="col-sm-4 control-label">Личная почта</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="пример@mail.ru" name="PrivateEmail" />
      </div>
    </div>
  </form>
</div>
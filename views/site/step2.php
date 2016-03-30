<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Регистрация сотрудников в WiFi';
?>
<div class="login-choose-form">
  <form class="form-horizontal" method="POST" action="<?=Url::toRoute('/site/step2')?>">
  <h3>Шаг 2. Регистрация.</h3>
   <div class="form-group">
      <label for="password" class="col-sm-4 control-label">Логин</label>
      <div class="col-sm-8">
          <?php foreach($logins as $row) { ?>
            <label>
                <?php if($row['isAvailable']) { ?>
                    <input type="radio" name="logonname" id="logonname-<?=$row['Name']?>" class='logonname' value="<?=$row['Name']?>"> 
                    <?php echo Html::img('@web/img/knob/Knob Valid Green.png') ?> 
                <?php } else { ?>
                <?php echo Html::img('@web/img/knob/Knob Cancel.png') ?> 
                <?php } ?> <span><?=$row['Name']?></span>
            </label>
          <?php } ?>
      </div>
   </div>
   <div class="form-group">
      <label for="password" class="col-sm-4 control-label">Пароль</label>
      <div class="col-sm-8">
        <input type="password" class="form-control" id="password" placeholder="Придумайте пароль" name="password" />
      </div>
    </div>
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <div class="form-group">
      <label for="passwordrepeat" class="col-sm-4 control-label">Повтор пароля</label>
      <div class="col-sm-8">
        <input type="password" class="form-control" id="passwordrepeat" placeholder="Повторите пароль" name="passwordrepeat" />
      </div>
    </div>
    <div class="form-group">
      <label for="email" class="col-sm-4 control-label">Адрес почты</label>
      <div class="col-sm-8">
        <input type="text" class="form-control" id="email" placeholder="Введите адрес вашей личной существующей почты" name="email" />
      </div>
    </div>
  </form>
  <a href='<?=Url::toRoute('/site/step3')?>' class='cbutton'>Далее</a>
</div>

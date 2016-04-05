<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Регистрация сотрудников в WiFi';
?>
<div class="step2-popup-static__div" id="step2-popup" onclick="HidePopup()">
  <div class="login-choose-popup__div" onclick="HidePopup()">
    <div class="login-choose-popup-center__div">
      <h2 class="login-choose-popup-title__h2">
        ВЫБЕРИТЕ ЛОГИН
      </h2>
    </div>
  </div>
</div>
<div class="login-choose-form">
  <form class="form-horizontal" method="POST" action="<?=Url::toRoute('/site/step2')?>">
    <div class="center-text__div">
      <h3 class="registration-title__h3">Шаг 2. Регистрация.</h3>
    </div>
    <div class="form-group">
      <label for="password" class="col-sm-4 control-label" onclick="ShowPopup();">Логин</label>
      <div class="col-sm-8">
          <?php foreach($logins as $row) { ?>
            <label class="login-select__label">
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
        <input type="text" class="form-control" id="email" placeholder="Введите адрес вашей корпоративной почты" name="email" value="<?=$_SESSION['personalData']['email']?>"/>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-4 col-sm-8">
        <button type="submit" class="btn btn-default">Далее</button>
      </div>
    </div>
  </form>
  <?php if(isset($result['message'])) { ?>
    <div class="error"><?=$result['message']?></div>
  <?php } ?>
</div>
<script type="text/javascript">
  function HidePopup(){
    $( "#step2-popup" ).hide("slow");
  };
  function ShowPopup(){
    $( "#step2-popup" ).show("slow");
  };

</script>

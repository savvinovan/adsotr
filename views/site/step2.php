<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Регистрация сотрудников в WiFi';
?>
<div class="login-choose-popup-hide__div" id="step2-popup" onclick="HidePopup()">
  <div class="login-choose-popup__div" onclick="HidePopup()">
    <div class="login-choose-popup-center__div">
      <h2 class="login-choose-popup-title__h2">
        ВЫБЕРИТЕ ЛОГИН
      </h2>
      <div class="login-choose-popup-list__div">
        <div class="col1-popup-list__div">
          <?php foreach($logins as $row) { ?>
            <?php $i++ ?>
              <?php if ($i<=7) { ?>
                <div class="login-choose-list-element__div" onclick="SelectThis('<?php echo $row["Name"] ?>');">
                <?php if($row['isAvailable']) { ?>
                  <?php echo Html::img('@web/img/available-icon.png') ?>
                <?php } else { ?>
                  <?php echo Html::img('@web/img/not-available-icon.png') ?>
                <?php } ?>
                <span><?=$row['Name']?>@empl.s-vfu.ru</span>
                </div>
              <?php } ?>
            <?php } ?>
        </div>
        <div class="col2-popup-list__div">
          <?php $i = 0; ?>
          <?php foreach($logins as $row) { ?>
            <?php $i++ ?>
              <?php if(($i > 7)AND($i<=14)) { ?>
                <div class="login-choose-list-element__div" onclick="SelectThis('<?php echo $row["Name"] ?>');">
                <?php if($row['isAvailable']) { ?>
                  <?php echo Html::img('@web/img/available-icon.png') ?>
                <?php } else { ?>
                  <?php echo Html::img('@web/img/not-available-icon.png') ?>
                <?php } ?>
                <span><?=$row['Name']?>@empl.s-vfu.ru</span>
                </div>
              <?php } ?>
            <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="login-choose-form">
  <form class="form-horizontal" method="POST" action="<?=Url::toRoute('/site/step2')?>">
    <div class="center-text__div">
      <h3 class="registration-title__h3">Шаг 2. Регистрация.</h3>
    </div>
    <div class="form-group">
      <label for="" class="col-sm-4 control-label">Логин</label>
      <div class="col-sm-8">
          <div class="login-select-button__div"  onclick="ShowPopup();">
            <span>Выберите логин</span> <?php echo Html::img('@web/img/navigation-icon.png', ["class"=>"login-select-icon__img"]) ?>
            <input type="hidden" name="logonname" id="hidden_login_input" value="">
          </div>
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
    $( "#step2-popup" ).attr("class", "login-choose-popup-hide__div");
  };
  function ShowPopup(){
    $( "#step2-popup" ).attr("class", "login-choose-popup__div");
  };
  function SelectThis(name) {
    $(".login-select-button__div span").text(name + "@empl.s-vfu.ru");
    $("#hidden_login_input").val(name);
  }
</script>

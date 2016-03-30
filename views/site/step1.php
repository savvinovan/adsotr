<?php

/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Регистрация сотрудников в WiFi';
?>
          <div class="register-form">
            <h3>Проверка данных</h3>
            <form class="form-horizontal" method="POST" action="<?=Url::toRoute('/site/step1')?>">
              <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
              <div class="form-group">
                <label for="fio" class="col-sm-4 control-label">Фамилия, имя, отчество</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="fio" placeholder="Введите ваше ФИО" name="fio" value='<?=$preResponse['fio']?>'/>
                </div>
              </div>
              <div class="form-group">
                <label for="borndate" class="col-sm-4 control-label">Дата рождения</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control date" id="borndate" placeholder="Введите дату рождения" name="borndate" value='<?=$preResponse['borndate']?>' />
                </div>
              </div>
              <div class="form-group">
                <label for="docserial" class="col-sm-4 control-label">Паспорт гражданина РФ</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="docserialno" placeholder="Введите серию и номер паспорта" name="docserialno" value='<?=$preResponse['docserialno']?>' />
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                  <button type="submit" class="btn btn-default">Проверить данные</button>
                </div>
              </div>
            </form>
            <?php if(isset($result['message'])) { ?>
              <div class="error"><?=$result['message']?></div>
            <?php } ?>
          </div>

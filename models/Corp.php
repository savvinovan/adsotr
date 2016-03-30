<?php

namespace app\models;

use Yii;

use app\components\LDAP;

class Corp {

    private $connection;
    private $ldap;

    function __construct () {
        $this->connection = Yii::$app->db;
        $this->ldap = new LDAP();
    }

    public function getLogins($fio) {
        $fio = mb_strtolower($fio);
        $fio = $this->rus2translit($fio);
        $arr = explode(' ',trim($fio));
        $lastName = $arr[0];
        $firstName = $arr[1];
        $middleName = $arr[2];
        
        $i = 0;
        $logins[$i]['Name'] = mb_substr($firstName, 0, 1).mb_substr($middleName, 0, 1).'.'.$lastName;
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
                
        
        $i = 1;
        $logins[$i]['Name'] = mb_substr($firstName, 0, 2).mb_substr($middleName, 0, 1).'.'.$lastName;
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 2;
        $logins[$i]['Name'] = mb_substr($firstName, 0, 2).'.'.mb_substr($middleName, 0, 2).'.'.$lastName;
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 3;
        $logins[$i]['Name'] = mb_substr($firstName, 0, 2).mb_substr($middleName, 0, 2).'.'.$lastName;
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 4;
        $logins[$i]['Name'] = mb_substr($firstName, 0, 3).mb_substr($middleName, 0, 2).'.'.$lastName;
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 5;
        $logins[$i]['Name'] = mb_substr($firstName, 0, 3).mb_substr($middleName, 0, 3).'.'.$lastName;
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);    
        
        $i = 6;
        $logins[$i]['Name'] = $lastName.'.'.mb_substr($firstName, 0, 1).mb_substr($middleName, 0, 1);
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 7;
        $logins[$i]['Name'] = $lastName.'.'.mb_substr($firstName, 0, 1).'.'.mb_substr($middleName, 0, 1);
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 8;
        $logins[$i]['Name'] = $lastName.mb_substr($firstName, 0, 1).mb_substr($middleName, 0, 1);
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 9;
        $logins[$i]['Name'] = mb_substr($lastName, 0, 3).mb_substr($firstName, 0, 1).mb_substr($middleName, 0, 1);
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 10;
        $logins[$i]['Name'] = mb_substr($lastName, 0, 5).mb_substr($firstName, 0, 1).mb_substr($middleName, 0, 1);
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 11;
        $logins[$i]['Name'] = mb_substr($lastName, 0, 3).'.'.mb_substr($firstName, 0, 1).mb_substr($middleName, 0, 1);
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        $i = 12;
        $logins[$i]['Name'] = mb_substr($firstName, 0, 1).'.'.mb_substr($middleName, 0, 1).'.'.$lastName;
        $logins[$i]['isAvailable'] = $this->ldap->getLoginAvailability($logins[$i]['Name']);
        
        return $logins;
    }
    
    private function rus2translit($string) {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }
    
    public function extractPersonalData($data) {
      
      // Разбираем строку с ФИО
      $arrFio = explode(' ',trim($data['fio']));
      $data['Surname'] = $arrFio[0];
      $data['Name'] = $arrFio[1];
      $data['Patronymic'] = $arrFio[2];
      
      // Разбираем строку с паспортом
      $arrDocidentity = explode(' ',trim($data['docserialno']));
      $data['DocSerial'] = $arrDocidentity[0];
      $data['DocNo'] = $arrDocidentity[1];
      return $data;
    }
    
    public function checkStep1($data) {
      $fio = $data['fio'];
      $arr = explode(' ',trim($fio));
      $result['error'] = '0';
      $docserial =  mb_substr($data['docserialno'], 0, 4);
      $docno =  mb_substr($data['docserialno'], 5, 10);
      if(empty($fio)) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели ФИО';
      } else if (sizeof($arr) < 3) {
        $result['error'] = '1';
        $result['message'] = 'Введите фио полностью';
      } else if(sizeof($arr) > 3) {
        $result['error'] = '1';
        $result['message'] = 'Вы ввели лишние символы в ФИО';
      } else if(empty($data['borndate'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели дату рождения';
      } else if(empty($data['docserialno'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели серию и номер паспорта';
      } else if(strlen($docserial) != 4) {
        $result['error'] = '1';
        $result['message'] = 'Вы ввели некорректную серию паспорта';
      } else if(strlen($docno) != 6) {
        $result['error'] = '1';
        $result['message'] = 'Вы ввели некорректный номер паспорта';
      } else if(!$this->checkPersonalDataValidity($data)) {
        $result['error'] = '1';
        $result['message'] = 'Вы ввели неправильные личные данные';
      } 
      return $result;
    }
    
    
    public function checkPersonalDataValidity($data) {
      $sql = 'SELECT COUNT(*) as cnt FROM StaffPeople WHERE DocSerial = :DocSerial AND DocNo = :DocNo AND Surname = :Surname AND Name = :Name AND Patronymic = :Patronymic';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":DocSerial", $data['DocSerial']);
      $command->bindParam(":DocNo", $data['DocNo']);
      $command->bindParam(":Surname", $data['Surname']);
      $command->bindParam(":Name", $data['Name']);
      $command->bindParam(":Patronymic", $data['Patronymic']);
      $row = $command->queryOne();
      return ($row['cnt'] == '1') ? true : false;
    }
    
    
    /* Почта */
    public function sendMailActivationLink() {
      Yii::$app->mailer->compose('aduser/activation')
      ->setFrom('aduser@s-vfu.ru')
      ->setTo('ivanmat@mail.ru')
      ->setSubject('Активация вашего аккаунта')
      ->send();
    }
    
    public function sendMailRestorationLink() {
      Yii::$app->mailer->compose('aduser/restoration')
      ->setFrom('aduser@s-vfu.ru')
      ->setTo('ivanmat@mail.ru')
      ->setSubject('Активация вашего аккаунта')
      ->send();
    }
    
    /* ///////////////////////////////////// */
    
    public function addAdSotrPeople($data) {
      $activationString = hash('sha256', time());
      $sql = 'INSERT INTO AdSotrPeople (StaffID, PrivateEmail, CorpEmail, isActivated, ActivationString) VALUES (StaffID, PrivateEmail, CorpEmail, 0, ActivationString)';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":StaffID", $data['StaffID']);
      $command->bindParam(":PrivateEmail", $data['PrivateEmail']);
      $command->bindParam(":CorpEmail", $data['CorpEmail']);
      $command->bindParam(":ActivationString", $activationString);
      $command->execute();
    }
    
    public function checkStep2($data) {
      if(empty($data['password'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели пароль';
      } else if(empty($data['password_retype'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели ФИО';
      }
      return $result;
    }
}

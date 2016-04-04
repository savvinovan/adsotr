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
      
      // BornDate
      
      $data['BornDate'] = $data['BornDate'];
      
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
      } else if(empty($data['BornDate'])) {
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
    public function sendMailActivationLink($data) {
      $activationLink = $this->getActivationLink($this->getStaffID($data));
      Yii::$app->mailer->compose('aduser/activation', ['activationLink' => $activationLink])
      ->setFrom('aduser@s-vfu.ru')
      ->setTo($data['email'])
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
    
    public function createAdSotrPeopleID($data) {
      $staffID = $this->getStaffID($data);
      $salt = 'ezbaitbydoro';
      $activationString = hash('sha256', time());
      $passwordHash = hash('sha256', $salt.$data['password']);
      $sql = 'INSERT INTO AdSotrPeople (StaffID, CorpEmail, isActivated, ActivationString, Password, Logonname) VALUES (:StaffID, :CorpEmail, 0, :ActivationString, :Password, :Logonname)';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":StaffID", $staffID);
      $command->bindParam(":CorpEmail", $data['email']);
      $command->bindParam(":Logonname", $data['logonname']);
      
      $command->bindParam(":Password", $passwordHash);
      $command->bindParam(":ActivationString", $activationString);
      return $command->execute();
    }
    
    public function registerAdSotr($data) {
      if($this->createAdSotrPeopleID($data)) {
        $this->ldap->createDisabledUser($data);
        $this->sendMailActivationLink($data);
      }
      
      
    }
    
    public function checkStep2($data) {
      $result['error'] = '0';
      if (empty($data['logonname'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не выбрали логин';
      } else if(empty($data['password'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели пароль';
      } else if(empty($data['passwordrepeat'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели повтор пароля';
      } else if(empty($data['email'])) {
        $result['error'] = '1';
        $result['message'] = 'Вы не ввели адрес вашей корпоративной почты (@s-vfu.ru)';
      } else if($data['passwordrepeat'] != $data['password']) {
        $result['error'] = '1';
        $result['message'] = 'Введенные пароли не совпадают';
      } else if(stristr($data['email'], '@s-vfu.ru') === FALSE) {
        $result['error'] = '1';
        $result['message'] = 'Вы ввели некорректный адрес корпоративной почты @s-vfu.ru';
      }
      return $result;
    }
    
    private function getStaffID($data) {
      $sql = 'SELECT StaffID FROM StaffPeople WHERE DocSerial = :DocSerial AND DocNo = :DocNo AND Surname = :Surname AND Name = :Name AND Patronymic = :Patronymic';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":DocSerial", $data['DocSerial']);
      $command->bindParam(":DocNo", $data['DocNo']);
      $command->bindParam(":Surname", $data['Surname']);
      $command->bindParam(":Name", $data['Name']);
      $command->bindParam(":Patronymic", $data['Patronymic']);
      $row = $command->queryOne();
      return $row['StaffID'];
    }
    
    public function test($name, $password) {
      $this->ldap->changeAduserPassword($name, $password);
    }
    
    public function saveStep1($data) {
      $_SESSION['personalData']['Surname'] = $data['Surname'];
      $_SESSION['personalData']['Name'] = $data['Name'];
      $_SESSION['personalData']['Patronymic'] = $data['Patronymic'];
      $_SESSION['personalData']['DocSerial'] = $data['DocSerial'];
      $_SESSION['personalData']['BornDate'] = $data['BornDate'];
      $_SESSION['personalData']['DocNo'] = $data['DocNo'];
      $_SESSION['personalData']['fio'] = $data['fio'];
      $_SESSION['personalData']['Initials'] = mb_strtoupper(mb_substr($data['Surname'],0,1).mb_substr($data['Name'],0,1).mb_substr($data['Patronymic'],0,1));
    }
    
    public function saveStep2($data) {
      $_SESSION['personalData']['password'] = $data['password'];
      $_SESSION['personalData']['email'] = $data['email'];
      $_SESSION['personalData']['logonname'] = $data['logonname'];
    }
    
    private function getActivationLink($staffid) {
      $sql = 'SELECT ActivationString FROM AdSotrPeople WHERE StaffID = :StaffID';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":StaffID", $staffid);
      $row = $command->queryOne();
      $activationLink = 'http://corp.s-vfu.ru/site/activateuser?link='.$row['ActivationString'];
      return $activationLink;
    }
    
    public function activateUser($data) {
      $sql = 'UPDATE AdSotrPeople SET isActivated = 1 WHERE ActivationString = :ActivationString';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":ActivationString", $data['link']);
      $command->execute();
    }
    
}

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
      $bornDate = date('Y-m-d',strtotime($data['BornDate']));
      $sql = 'SELECT COUNT(*) AS cnt FROM StaffPeople WHERE BornDate = :BornDate AND DocSerial = :DocSerial AND DocNo = :DocNo AND Surname = :Surname AND Name = :Name AND Patronymic = :Patronymic';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":BornDate", $bornDate);
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
    
    public function sendMailAccountActivated($login, $email) {
      Yii::$app->mailer->compose('aduser/activated', ['login' => $login])
      ->setFrom('aduser@s-vfu.ru')
      ->setTo($email)
      ->setSubject('Ваша учетная запись активирована')
      ->send();
    }
    
    /* ///////////////////////////////////// */
    
    public function createAdSotrPeopleID($data) {
      $staffID = $this->getStaffID($data);
      $salt = 'ezbaitbydoro';
      $activationString = hash('sha256', time());
      $passwordHash = hash('sha256', $salt.$data['password']);
      if(!$this->isAdSotrAlreadyCreated($staffID)) {
        $sql = 'INSERT INTO AdSotrPeople (StaffID, CorpEmail, isActivated, ActivationString, Password, Logonname) VALUES (:StaffID, :CorpEmail, 0, :ActivationString, :Password, :Logonname)';
        $command = $this->connection->createCommand($sql);
        $command->bindParam(":StaffID", $staffID);
        $command->bindParam(":CorpEmail", $data['email']);
        $command->bindParam(":Logonname", $data['logonname']);

        $command->bindParam(":Password", $passwordHash);
        $command->bindParam(":ActivationString", $activationString);
        $result = $command->execute();
      } else {
        $result = true;
      }
      return $result;
    }
    
    private function isAdSotrAlreadyCreated($staffID) {
      $sql = 'SELECT COUNT(*) AS cnt FROM AdSotrPeople WHERE StaffID = :StaffID';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":StaffID", $staffID);
      $row = $command->queryOne();
      return ($row['cnt'] == '1') ? true : false;
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
      } else if(mb_strlen($data['password']) < 8) {
        $result['error'] = '1';
        $result['message'] = 'Пароль должен быть от 8 символов';
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
      $_SESSION['personalData']['Surname'] = $this->mb_ucfirst($data['Surname']);
      $_SESSION['personalData']['Name'] = $this->mb_ucfirst($data['Name']);
      $_SESSION['personalData']['Patronymic'] = $this->mb_ucfirst($data['Patronymic']);
      $_SESSION['personalData']['DocSerial'] = $data['DocSerial'];
      $_SESSION['personalData']['BornDate'] = $data['BornDate'];
      $_SESSION['personalData']['DocNo'] = $data['DocNo'];
      $_SESSION['personalData']['fio'] = $this->mb_ucfirst($data['Surname']).' '.$this->mb_ucfirst($data['Name']).' '.$this->mb_ucfirst($data['Patronymic']);
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
    
    /* Активация пользователя */
    
    public function activateAdSotr($link) {
      $login = $this->getLoginByLink($link);
      $email = $this->getEmailByLink($link);
      if($this->activateUser($link)) {
        $this->activateLdapUser($login);
        $this->sendMailAccountActivated($login, $email);
      }
    }
    
    private function activateUser($link) {
      $sql = 'UPDATE AdSotrPeople SET isActivated = 1 WHERE ActivationString = :ActivationString';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":ActivationString", $link);
      return $command->execute();
    }
    
    private function activateLdapUser($name) {
      $uri = 'http://10.2.8.130:3001/add-activate';
      $curl = curl_init($uri);      
      $fields = array(
        'name' => $name,
      );
      
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

      curl_exec($curl);
      $curlInfo = curl_getinfo($curl);
      curl_close($curl);
      if($curlInfo['http_code'] == '200') {
        $result['error'] = '0';
        $result['message'] = 'Создан неактивный пользователь';
      } else {
        $result['error'] = '1';
        $result['message'] = 'Произошла ошибка';
      }      
    }
    
    private function getLoginByLink($link) {
      $sql = 'SELECT Logonname FROM AdSotrPeople WHERE ActivationString = :ActivationString';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":ActivationString", $link);
      $row = $command->queryOne();
      return $row['Logonname'];
    }
    
    private function getEmailByLink($link) {
      $sql = 'SELECT CorpEmail FROM AdSotrPeople WHERE ActivationString = :ActivationString';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":ActivationString", $link);
      $row = $command->queryOne();
      return $row['CorpEmail'];
    }
    
    /* END Активация пользователя */
    
    function mb_ucfirst($string) {
      $strlen = mb_strlen($string);
      $firstChar = mb_substr($string, 0, 1);
      $then = mb_substr($string, 1, $strlen - 1);
      return mb_strtoupper($firstChar) . $then;
    }
    
    /* Создание структуры каталогов */
    
    public function createAdUnits() {
      $departments = $this->getDepartments();
      foreach($departments as $row) {
        echo $this->getDepartmentADLink($row).'<br />';
      }
    }
    
    private function getDepartments() {
      $sql = "SELECT * FROM Departments";
      $command = $this->connection->createCommand($sql);
      $rows = $command->queryAll();
      return $rows;
    }
    
    private function getDepartmentADLink($department) {
      $link = '';
      $structure[] = 'OU='.$department['Name'];
      $isFound = true;
      $parentDeptID = $department['ParentDeptID'];
      while($isFound) {
        $departmentParent = $this->getDepartmentParent($parentDeptID);
        if($departmentParent['cnt'] != '1') {
          $isFound = false;
        } else {
          $structure[] = 'OU='.$departmentParent['Name'];
          $parentDeptID = $departmentParent['ParentDeptID'];
        }
      }
      $structure[] = 'OU=Сотрудники';
      $structure[] = 'OU=СВФУ';
      $structure[] = 'DC=svfu';
      $structure[] = 'DC=s-vfu';
      $structure[] = 'DC=ru';
      foreach($structure as $row) {
        $link .= $row.',';
      }
      $link = mb_substr($link, 0, -1);
      return $link;
    }
    
    private function getDepartmentParent($parentDeptID) {
      $sql = 'SELECT COUNT(*) AS cnt, DeptID, ParentDeptID, Name FROM Departments WHERE DeptID = :DepartmentParent GROUP BY DeptID, Name, ParentDeptID';
      $command = $this->connection->createCommand($sql);
      $command->bindParam(":DepartmentParent", $parentDeptID);
      $row = $command->queryOne();
      return $row;
    }
    
}

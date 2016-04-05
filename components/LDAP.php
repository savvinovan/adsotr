<?php
namespace app\components;

use Yii;

class LDAP {
    
    private $adServer = "ldap://svfu.s-vfu.ru";
    private $username = 'ia.matveev';
    private $password = 'Djljktq142442';
    private $ldaprdn;
    private $ldap;
    
    function __construct () {
      $this->ldap = ldap_connect($this->adServer);
      $this->ldaprdn = 'S-VFU' . "\\" . $this->username;
      ldap_set_option ($this->ldap, LDAP_OPT_REFERRALS, 0) or die('Unable to set LDAP opt referrals');
      ldap_set_option($this->ldap, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
    }
    
    public function getLoginAvailability($name) {
      $uri = 'http://10.2.8.130:3000/get-aduser?name='.$name;
      $curl = curl_init($uri);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_exec($curl);
      $curlInfo = curl_getinfo($curl);
      if($curlInfo['http_code'] == '200') {
        return false;
      } else {
        return true;
      }
    }
    
    public function createDisabledUser($data) {
      $uri = 'http://10.2.8.130:3001/add-aduser';
      $curl = curl_init($uri);      
      $fields = array(
        'name' => $data['fio'],
        'password' => $data['password'],
        'login' => $data['logonname'],
        'surname' => $data['Surname'],
        'givenname' => $data['Name'],
        'initials' => $data['Initials'],
        'group' => 'ФЭИ-М-СИМ-15',
        'institute' => 'Финансово-экономический институт',
        'upnlogin' => $data['logonname'].'@empl.s-vfu.ru',
      );
      
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($fields));

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
    
    public function changeAduserPassword($name, $password) {
      $uri = 'http://10.2.8.130:3001/add-password';
      $fields = array(
        'name' => $name,
        'password' => $password
      );
      $curl = curl_init($uri);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
      curl_exec($curl);
      $curlInfo = curl_getinfo($curl);
      if($curlInfo['http_code'] == '200') {
        return false;
      } else {
        return true;
      }
    }
    
    public function activateUser($name) {
      $uri = 'http://10.2.8.130:3000/add-activate?name='.$name;
      $curl = curl_init($uri);
      curl_exec($curl);
      $curlInfo = curl_getinfo($curl);
      if($curlInfo['http_code'] == '200') {
        return false;
      } else {
        return true;
      }
    }
    
    public function checkAdGroup($name) {
      
    }
    
    public function createAdGroup($name, $parent = null) {
      
    }
    
    public function addToGroup($member, $group) {
      
    }
    
    public function checkOu($name, $parent = null) {
      
    }
    
    public function deleteUser($name) {
      $uri = 'http://10.2.8.130:3000/delete-aduser?name='.$name;
      $curl = curl_init($uri);
      curl_exec($curl);
      return true;
    }
}
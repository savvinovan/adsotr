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
    
    public function createDisabledUser($name, $password, $login, $surname, $givenname, $initials, $group, $institute, $upnlogin) {
      $uri = 'http://10.2.8.130:3000/add-aduser?name='.$name.'&password='.$password.'&login='.$login.'&surname='.$surname.'&givenname='.$givenname.'&initials='.$initials.'&group='.$group.'&institute='.$institute.'&upnlogin='.$upnlogin;
      $curl = curl_init($uri);
      curl_exec($curl);
      $curlInfo = curl_getinfo($curl);
      if($curlInfo['http_code'] == '200') {
        return false;
      } else {
        return true;
      }
    }
    
    public function changeAduserPassword($name, $password) {
      $uri = 'http://10.2.8.130:3000/add-password?name='.$name.'&password='.$password;
      $curl = curl_init($uri);
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
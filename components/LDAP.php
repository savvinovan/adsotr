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
      $uri = 'http://10.2.8.130:3000/add-aduser';
      $curl = curl_init($uri);
      $fields = array(
        'name' => urlencode($data['name']),
        'password' => urlencode($data['password']),
        'login' => urlencode($data['login']),
        'surname' => urlencode($data['surname']),
        'givenname' => urlencode($data['givenname']),
        'initials' => urlencode($data['initials']),
        'group' => urlencode($data['group']),
        'institute' => urlencode($data['institute']),
        'upnlogin' => urlencode($data['upnlogin']),
      );
      //url-ify the data for the POST
      foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
      rtrim($fields_string, '&');
      
      curl_setopt($curl,CURLOPT_POST, count($fields));
      curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);

      curl_exec($curl);
      $curlInfo = curl_getinfo($curl);
      curl_close($curl);
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
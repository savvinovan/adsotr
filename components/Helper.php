<?php
namespace app\components;

class Helper {
    
    public static function GetDate($date) {
        if($date != '') {
          return date('d.m.Y', strtotime($date));
        }
    }
    
    public static function GetDictValue($array, $searched, $idColumn, $nameColumn) {
      foreach($array as $row) {
        if($row[$idColumn] === $searched) {
          echo $row[$nameColumn];
        }
      }
    }
}

?>
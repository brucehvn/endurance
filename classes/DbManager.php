<?php

/*
  Assumes a Products table with the following schema
  CREATE TABLE products {
    cust_id  AUTOINCREMENT int,  // should be an id reference into a customer table
    product_type int, // Should be an id reference into a products table
    domain VARCHAR,
    start_date DATETIME,
    duration int
  }

*/

class DbManager {
  private static $cust_ids = array("Cust123", "Cust123", "Cust123", "Cust123", "Cust234", "Cust234", "Cust345", "Cust345", "Cust456");
  private static $products = array("domain", "hosting", "domain", "email", "domain", "hosting", "pdomain", "hosting", "edomain");
  private static $domains = array("xyzzy.com", "xyzzy.com", "mydomain.com", "mydomain.com", "plugh.org", "plugh.org", "protected.org", "protected.org", "school.edu");
  private static $dates = array("2020-01-01", "2020-01-01", "2020-03-01", "2020-03-01", "2020-02-01", "2020-11-17", "2020-03-01", "2020-04-01", "2020-04-01");
  private static $durations = array(12, 6, 12, 12, 24, 6, 36, 11, 12);

  private static $instance = null;

  private $record_set = array();

  private function __constructor() {}

  private function addInitialRecord($rec) {
    $this->record_set[] = $rec;
  }

  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new DbManager();
      for ($xctr = 0; $xctr < 9; $xctr++) {
        $start_date = strtotime(self::$dates[$xctr]);
        if (preg_match('/^.*domain/', self::$products[$xctr]) == 1) {
          $end_date = $start_date + (self::$durations[$xctr] * (60 * 60 * 24 * 365));
        }
        else if (self::$products[$xctr] == "hosting" || self::$products[$xctr] == "email") {
          $end_date = $start_date + (self::$durations[$xctr] * (60 * 60 * 24 * 30));
        }
        $rec = array("cust_id" => self::$cust_ids[$xctr], "product" => self::$products[$xctr],
         "domain" => self::$domains[$xctr], "start_date" => strtotime(self::$dates[$xctr]), "duration" => self::$durations[$xctr],
         "end_date" => $end_date);
        self::$instance->addInitialRecord($rec);
      }
    }
    return(self::$instance);
  }

  public function getRecCount() {
    return(count($this->record_set));
  }

  public function getRecords() {
    return($this->record_set);
  }

  public function getActiveRecords() {
    $ret_set = array();
    foreach($this->record_set as $rec) {
      if ($rec['end_date'] > time()) {
        $ret_set[] = $rec;
      }
    }
    return($ret_set);
  }

  public function getRecord($params) {
    $record = array();
    $bfound = FALSE;
    foreach($this->record_set as $res) {
      $keys = array_keys($params);
      if (count($keys) > 0) {
        $bfound = TRUE;
      }
      foreach($keys as $key) {
        $cmp_val = $res[$key];
        if ($key == 'start_date') {
          if ($params[$key] != $cmp_val) {
            $bfound = FALSE;
            break;
          }
        }
        else if (strcasecmp($params[$key], $cmp_val) != 0) {
          $bfound = FALSE;
          break;
        }
      }
      if ($bfound) {
        $record = $res;
        break;
      }
    }
    return($record);
  }

  public function testInit() {
    print_r($this->record_set);
  }

}

?>
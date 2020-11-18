<?php
class ActiveList {

  private static $instance = NULL;
  private $active_list;
  private $dbm;

  private function __construct() {
    $active_list = array();
  }

  public static function getInstance() {
    if (self::$instance == NULL) {
      self::$instance = new ActiveList();
    }
    return(self::$instance);
  }

  public function addRecord($record) {
    $this->active_list[] = $record;
  }

  public function getSortedRecordList() {
    uasort($this->active_list, function($a, $b) {
      $retval = strcasecmp($a['cust_id'], $b['cust_id']);

      if ($retval == 0) {
        $$retval = strcasecmp($a['product'], $b['product']);
      }
      return($retval);
    });
    return($this->active_list);
  }

  public function getEmailList($product_obj) {
    $output_array = array();

    foreach($this->active_list as $rec) {
      $class_name = ProductTypes::getClassName();
      $prod_obj = new $class_name;
      $email_arr = $prod_obj->getEmailList($rec);
      foreach($email_arr as $email_rec) {
        $output_array[] = $email_rec;
      }
    }
    uasort($output_array, function($a, $b) {
      return(strcasecmp($a['email_date'], $b['email_date']));
    });
    return($output_array());
  }

  public function findRegisteredDomain($domain) {
    $retval = FALSE;
    foreach($this->active_list as $rec) {
      if (strcasecmp($rec['domain'], $domain) == 0 &&
          strcasecmp($rec['product'], "domain") == 0) {
        $retval = $rec;
      }
    }
    return($retval);
  }

  public function resetList() {
    $this->active_list = array();
  }

}


?>
<?php
class ActiveList {

  private $active_list;
  private $dbm;

  public function __construct() {
    $dbm = DbManager::getInstance();
    $active_list = array();
  }

  public function initialize() {

  }
}


?>
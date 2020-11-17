<?php
abstract class aProduct {
  protected $type;

  protected $cust_id;
  protected $product;
  protected $domain;
  protected $duration;
  protected $start_date;

  public function __construct() {}

  protected function setType($type) {
    $this->type = $type;
  }

  protected function getType() {
    return($this->type);
  }

  abstract public function addProduct($cust_id, $product, $domain, $duration);

  abstract public function loadProduct($cust_id, $product, $domain, $start_date, $duration);

  abstract public function getNextEmailDate();

  abstract protected function validateInputData($record, $check_start_date);

}
?>
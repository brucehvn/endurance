<?php

class PDomainProduct extends DomainProduct {

  public function __construct() {
    parent::__construct();
    $this->setType(ProductTypes::PT_PDOMAIN);
  }

  public function addProduct($cust_id, $product, $domain, $duration) {
    $retval = parent::addProduct($cust_id, $product, $domain, $duration);
    $record = array('cust_id' => $cust_id, 'product' => $product,
      'domain' => $domain, 'duration' => $duration);

    if ($retval) {
      $dm = new DomainManager();
      $retval = $dm->secureDomain($record);
    }

    return($retval);
  }
}

?>
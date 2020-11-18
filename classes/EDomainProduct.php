<?php

class EDomainProduct extends DomainProduct {

  public function __construct() {
    $this->setType(ProductTypes::PT_EDOMAIN);
    $this->addValidDomain("edu");
  }
}

?>
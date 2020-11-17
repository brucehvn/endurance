<?php

class ProductTypes {
  const __default = self::PT_NONE;

  const PT_NONE = 0;
  const PT_DOMAIN = 1;
  const PT_HOSTING = 2;
  const PT_PDOMAIN = 3;
  const PT_EDOMAIN = 4;
  const PT_EMAIL = 5;

  protected $typeHash = array(PT_NONE => "none", PT_DOMAIN => "domain",
      PT_HOSTING => "hosting", PT_PDOMAIN => "pdomain", PT_EDOMAIN => "edomain",
      PT_EMAIL => "email");

  private function __construct() {}

  static public function getName($pt) {
    return($typehash[$pt]);
  }
}

?>
<?php

class ProductTypes {
  const __default = self::PT_NONE;

  const PT_NONE = 0;
  const PT_DOMAIN = 1;
  const PT_HOSTING = 2;
  const PT_PDOMAIN = 3;
  const PT_EDOMAIN = 4;
  const PT_EMAIL = 5;

  public static $typeHash = array(self::PT_NONE => "none", self::PT_DOMAIN => "domain",
      self::PT_HOSTING => "hosting", self::PT_PDOMAIN => "pdomain", self::PT_EDOMAIN => "edomain",
      self::PT_EMAIL => "email");

  public static $classNames = array("none" => "none", 'domain' => "DomainProduct",
      'hosting' => "HostingProduct", 'pdomain' => "PDomainProduct", 'edomain' => "EDomainProdcut",
      'email' => "EmailProduct");

  private function __construct() {}

  static public function getName($pt) {
    return(self::$typehash[$pt]);
  }

  static public function getClassName($ptname) {
    return(self::$classNames[$ptname]);
  }
}

?>
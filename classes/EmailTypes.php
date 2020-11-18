<?php

class EmailTypes {
  const __default = self::ET_NONE;

  const ET_NONE = 0;
  const ET_WELCOME = 1;
  const ET_EXPDOMAIN = 2;
  const ET_EXPHOSTING = 3;
  const ET_EXPEMAIL = 4;
  const ET_HOSTSTART = 5;

  protected static $typeHash = array(self::ET_NONE => "none", self::ET_WELCOME => "welcome",
      self::ET_EXPDOMAIN => "domainexpire", self::ET_EXPHOSTING => "hostingexpire",
      self::ET_EXPEMAIL => "emailexpire", self::ET_HOSTSTART => "hostingactive");

  private function __construct() {}

  static public function getName($et) {
    return(self::$typeHash[$et]);
  }
}

?>
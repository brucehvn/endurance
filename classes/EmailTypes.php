<?php

class EmailTypes {
  const __default = self::ET_NONE;

  const ET_NONE = 0;
  const ET_WELCOME = 1;
  const ET_EXPDOMAIN = 2;
  const ET_EXPHOSTING = 3;
  const ET_EXPEMAIL = 4;
  const ET_HOSTSTART = 5;

  protected $typeHash = array(ET_NONE => "none", ET_WELCOME => "welcome",
      ET_EXPDOMAIN => "domainexpire", ET_EXPHOSTING => "hostingexpire", ET_EXPEMAIL => "emailexpire",
      ET_HOSTSTART => "hostingactive");

  private function __construct() {}

  static public function getName($et) {
    return($typehash[$et]);
  }
}

?>
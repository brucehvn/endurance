<?php

class EmailManager {

  public function configureEmailRouting($record) {
    return(TRUE);
  }

  public function sendEmail($cust_id, $email_type, $email_subject, $email_body) {

  }
}

?>
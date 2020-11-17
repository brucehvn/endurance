<?php

class DomainProduct extends aProduct {

  protected $domain_regex_base = '/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)';

  protected $valid_domans = array();

  public function __construct() {
    $this->setType(ProductTypes::PT_DOMAIN);
    $this->addValidDomain("com");
    $this->addValidDomain("org");
  }

  public function addProduct($cust_id, $product, $domain, $duration) {
    $retval = FALSE;
    $record = array('cust_id' => $cust_id, 'product' => $product,
      'domain' => $domain, 'duration' => $duration);

    if (validateInputData($record, false)) {
      $record['start_date'] == time();
      $record['end_date'] = $record['start_date'] + ($record['duration'] * (60 * 60 * 24 * 365));

      $bm = new BillingManager();
      if ($bm->BillCustomerForDomain($record)) {
        $dm = new DomainManager();

        if ($dm->registerDomain($record)) {
          return($record);
        }
      }
    }
  }

  public function loadProduct($cust_id, $product, $domain, $start_date, $duration) {
    $record = NULL;
    $params = array('cust_id' => $cust_id, 'product' => $product,
      'domain' => $domain, 'duration' => $duration, 'start_date' => $start_date);

    if (validateInputData($params, true)) {
      $dbm = DbManager::getInstance();
      $record = $dbm->getRecord($params);
    }
    return($record);
  }

  public function getNextEmailDate() {

  }

  protected function addValidDomain($tld) {
    $this->valid_domains[] = $tld;
  }

  protected function checkDomainAlreadyRegistered($domain) {
    $dbm = DbManager::getInstance();
    return($dbm->findRegisteredDomain($domain));
  }

  protected function validateInputData($rec, $check_start_date) {
    $retval = false;

    if (strlen($rec['cust_id']) > 0) {
      if (preg_match('/(domain|hosting|email)/', $rec['product']) == 1) {
        if (preg_match('/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)(org|com)$/', $rec['domain'])) {
          if (intval($rec['duration']) > 0) {
            if ($check_start_date) {
              if ($rec['start_date'] != NULL && intval($rec['start_date']) > 0) {
                $retval = true;
              }
            }
            else {
              $retval = true;
            }
          }
        }
      }
    }
    return($retval);
  }

  protected function getDomainRegex() {
    $retstr = $this->domain_regex_base;
    $tldstr = '(';
    foreach($this->valid_domains as $tld) {
      if (strlen($tldstr) > 1) {
        $tldstr .= '|';
      }
      $tldstr .= $tld;
    }

    if (strlen($tldstr) > 1) {
      $retstr .= $tldstr . ')';
    }
    $retstr .= '/';
    return($retstr);
  }
}

?>
<?php

class DomainProduct extends aProduct {

  protected $domain_regex_base = '/^(?!\-)(?:(?:[a-zA-Z\d][a-zA-Z\d\-]{0,61})?[a-zA-Z\d]\.){1,126}(?!\d+)';

  protected $valid_domains = array();

  public function __construct() {
    $this->setType(ProductTypes::PT_DOMAIN);
    $this->addValidDomain("com");
    $this->addValidDomain("org");
  }

  public function addProduct($cust_id, $product, $domain, $duration) {
    $retval = FALSE;
    $record = array('cust_id' => $cust_id, 'product' => $product,
      'domain' => $domain, 'duration' => $duration);

    if ($this->validateInputData($record, false)) {
      $record['start_date'] = time();
      $record['end_date'] = $record['start_date'] + ($record['duration'] * (60 * 60 * 24 * 365));

      $chk_domain = $this->checkDomainAlreadyRegistered($domain);

      if ($chk_domain === FALSE) {
        $bm = new BillingManager();

        if ($bm->BillCustomerForDomain($record)) {
          $dm = new DomainManager();

          if ($dm->registerDomain($record)) {
            $al = ActiveList::getInstance();
            $al->addRecord($record);
            $retval = TRUE;
          }
        }
      }
    }
    return($retval);
  }

  public function loadProduct($cust_id, $product, $domain, $start_date, $duration) {
    $retval = FALSE;
    $record = NULL;
    $params = array('cust_id' => $cust_id, 'product' => $product,
      'domain' => $domain, 'duration' => $duration, 'start_date' => $start_date);

    if ($this->validateInputData($params, true)) {
      $dbm = DbManager::getInstance();
      $params['start_date'] = strtotime($params['start_date']);
      $record = $dbm->getRecord($params);
      $al = ActiveList::getInstance();
      $al->addRecord($record);
      $retval = TRUE;
    }
    return($retval);
  }

  public function getEmailDates($record) {
    $ret_arr = array();
    $end_date = $record['end_date'];
    $curtime = time();

    $exp_email_date = $end_date - (60 * 60 * 24 * 2);
    if ($curtime < $exp_email_date) {
      $ret_arr[] = array('cust_id' => $record['cust_id'], 'product' => $record['product'],
          'domain' => $record['domain'], 'email_date' => date('Y-m-d', $exp_email_date),
          'email_type' => EmailTypes::getName(EmailTypes::ET_EXPDOMAIN));
    }
    return($ret_arr);
  }

  protected function validateInputData($rec, $check_start_date) {
    $retval = false;

    if (strlen($rec['cust_id']) > 0) {
      if (preg_match($this->getDomainRegex(), $rec['domain'])) {
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
    return($retval);
  }

    protected function addValidDomain($tld) {
    $this->valid_domains[] = $tld;
  }

  protected function checkDomainAlreadyRegistered($domain) {
    $al = ActiveList::getInstance();
    return($al->findRegisteredDomain($domain));
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
    $retstr .= '$/';
    return($retstr);
  }
}

?>
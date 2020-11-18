<?php

class TestDomainProduct {
  private $dbm;
  private $dp;
  private $al;

  public function __construct() {}

  public function setup() {
    $this->dbm = DbManager::getInstance();
    $this->dp = new DomainProduct();
    $this->al = ActiveList::getInstance();
    $this->al->resetList();
  }

  public function runTests() {
    $this->setup();
    $this->testAddProduct();
    $this->setup();
    $this->testLoadProduct();
    $this->setup();
    $this->testGetEmailDates();
  }

  public function testAddProduct() {
    $cust_id = "Test123";
    $product = "domain";
    $duration = 2;
    $domain = 'test.com';

    $addret = $this->dp->addProduct($cust_id, $product, $domain, $duration);

    if ($addret) {
      $active_list = $this->al->getSortedRecordList();
      if (count($active_list) != 1 ||
        strcasecmp($cust_id, $active_list[0]['cust_id']) != 0 ||
        strcasecmp($product, $active_list[0]['product']) != 0 ||
        strcasecmp($domain, $active_list[0]['domain']) != 0 ||
        $duration != $active_list[0]['duration']) {
        throw new Exception("TestDomainProduct::testAddProduct: Invalid Record in ActiveList");
      }
    }
    else {
      throw new Exception("TestDomainProduct::testAddProduct: DomainProduct::addProduct failed");
    }
    echo "TestDomainProduct::testAddProduct() succeeded\n";
  }

  public function testLoadProduct() {
    $cust_id = "Test123";
    $product = "domain";
    $duration = 2;
    $domain = 'test.com';

    $cust_id2 = "Cust234";
    $domain2 = "plugh.org";
    $duration2 = 24;
    $start_date2 = "2020-02-01";

    $addret = $this->dp->addProduct($cust_id, $product, $domain, $duration);

    if ($addret) {
      $loadret = $this->dp->loadProduct($cust_id2, $product, $domain2, $start_date2, $duration2);

      if ($loadret) {
        $active_list = $this->al->getSortedRecordList();
        if (count($active_list) != 2 ||
            strcasecmp($cust_id2, $active_list[1]['cust_id']) != 0 ||
            strcasecmp($product, $active_list[1]['product']) != 0 ||
            strcasecmp($domain2, $active_list[1]['domain']) != 0 ||
            $duration2 != $active_list[1]['duration']) {
          var_dump($active_list[1]);
          throw new Exception("TestDomainProduct::testLoadProduct: Invalid Record in ActiveList");
        }
      }
      else {
        throw new Exception("TestDomainProduct::testLoadProject: DomainProduct::loadProduct failed");
      }
    }
    else {
      throw new Exception("TestDomainProduct::testLoadProduct: DomainProduct::addProduct failed");
    }
    echo "TestDomainProduct::testLoadProduct() succeeded\n";
  }

  public function testGetEmailDates() {
    $cust_id = "Test123";
    $product = "domain";
    $duration = 2;
    $domain = 'test.com';
    $start_date = (time() - (60 * 60 * 24));
    $end_date = $start_date + ($duration * (60 * 60 * 24 * 365));
    $end_email_date = $end_date - (60 * 60 * 24 * 2);

    $record = array('cust_id' => $cust_id, 'product' => $product, 'domain' => $domain,
        'duration' => 3, 'start_date' => $start_date, 'end_date' => $end_date);

    $email_list = $this->dp->getEmailDates($record);
    if (count($email_list) != 1) {
      throw new Exception("TestDomainProduct::testGetEmailDates: Invalid return from DomainProduct::getEmailDates");
    }
    else {
      $email_str_date = $email_list[0]['email_date'];
      if (strcasecmp(date('Y-m-d', $end_email_date), $email_list[0]['email_date']) != 0) {
        var_dump($email_list);
        throw new Exception("TestDomainProduct::testGetEmailDates: Invalid email date " . $email_list[0]['email_date']);
      }
      if (strcasecmp('domainexpire', $email_list[0]["email_type"]) != 0) {
        var_dump($email_list);
        throw new Exception("TestDomainProduct::testGetEmailDates: Invalid email type " . $email_list[0]['email_type']);
      }
    }
    echo "TestDomainProduct::testGetEmailDates() succeeded\n";
  }
}
?>

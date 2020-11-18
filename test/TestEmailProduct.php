<?php

class TestEmailProduct {
  private $dbm;
  private $dp;
  private $al;

  public function __construct() {}

  public function setup() {
    $this->dbm = DbManager::getInstance();
    $this->dp = new EmailProduct();
    $this->al = ActiveList::getInstance();
    $this->al->resetList();
  }

  public function runTests() {
    $this->setup();
    $this->testAddProductFailsWithoutDomainReg();
    $this->setup();
    $this->testAddProduct();
    $this->setup();
    $this->testLoadProduct();
    $this->setup();
    $this->testGetEmailDates();
  }

  public function testAddProductFailsWithoutDomainReg() {
    $cust_id = "Test123";
    $product = "email";
    $duration = 24;
    $domain = 'test.com';

    $addret = $this->dp->addProduct($cust_id, $product, $domain, $duration);

    if ($addret) {
      throw new Exception("TestEmailProduct::testAddProductFailsWithoutDomainReg: addRecord succeeded with no prior domain reg");
    }
    echo "TestHostingProduct::testAddProductFailsWithoutDomainReg() succeeded\n";
  }

  public function testAddProduct() {
    $cust_id = "Test123";
    $product = "email";
    $duration = 24;
    $domain = 'test.com';

    $dp2 = new DomainProduct();

    $addret = $dp2->addProduct($cust_id, "domain", $domain, 3);
    if (!$addret) {
      throw new Exception("TestHostingProduct::testAddProduct: Sample Domain Adding Failed");
    }

    $addret = $this->dp->addProduct($cust_id, $product, $domain, $duration);

    if ($addret) {
      $active_list = $this->al->getSortedRecordList();
      if (count($active_list) != 2 ||
        strcasecmp($cust_id, $active_list[1]['cust_id']) != 0 ||
        strcasecmp($product, $active_list[1]['product']) != 0 ||
        strcasecmp($domain, $active_list[1]['domain']) != 0 ||
        $duration != $active_list[1]['duration']) {
        throw new Exception("TestEmailProduct::testAddProduct: Invalid Record in ActiveList");
      }
    }
    else {
      throw new Exception("TestEmailProduct::testAddProduct: HostingProduct::addProduct failed");
    }
    echo "TestEmailProduct::testAddProduct() succeeded\n";
  }

  public function testLoadProduct() {
    $cust_id = "Test123";
    $product = "email";
    $duration = 24;
    $domain = 'test.com';

    $cust_id2 = "Cust123";
    $domain2 = "mydomain.com";
    $duration2 = 12;
    $start_date2 = "2020-03-01";

    $dp2 = new DomainProduct();
    $addret = $dp2->addProduct($cust_id, "domain", $domain, 3);
    if (!$addret) {
      throw new Exception("TestEmailProduct::testLoadProduct: Sample Domain Adding Failed");
    }
    $addret = $this->dp->addProduct($cust_id, $product, $domain, $duration);

    if ($addret) {
      $loadret = $this->dp->loadProduct($cust_id2, $product, $domain2, $start_date2, $duration2);

      if ($loadret) {
        $active_list = $this->al->getSortedRecordList();
        if (count($active_list) != 3 ||
            strcasecmp($cust_id2, $active_list[2]['cust_id']) != 0 ||
            strcasecmp($product, $active_list[2]['product']) != 0 ||
            strcasecmp($domain2, $active_list[2]['domain']) != 0 ||
            $duration2 != $active_list[2]['duration']) {
          throw new Exception("TestEmailProduct::testLoadProduct: Invalid Record in ActiveList");
        }
      }
      else {
        throw new Exception("TestEmailProduct::testLoadProject: HostingProduct::loadProduct failed");
      }
    }
    else {
      throw new Exception("TestEmailProduct::testLoadProduct: HostingProduct::addProduct failed");
    }
    echo "TestEmailProduct::testLoadProduct() succeeded\n";
  }

  public function testGetEmailDates() {
    $cust_id = "Test123";
    $product = "domain";
    $duration = 2;
    $domain = 'test.com';
    $start_date = (time() - (60 * 60));
    $end_date = $start_date + ($duration * (60 * 60 * 24 * 365));
    $end_email_date = $end_date - (60 * 60 * 24);

    $record = array('cust_id' => $cust_id, 'product' => $product, 'domain' => $domain,
        'duration' => 3, 'start_date' => $start_date, 'end_date' => $end_date);

    $email_list = $this->dp->getEmailDates($record);
    if (count($email_list) != 1) {
      throw new Exception("TestEmailProduct::testGetEmailDates: Invalid return from EmailProduct::getEmailDates");
    }
    else {
      if (strcasecmp(date('Y-m-d', $end_email_date), $email_list[0]['email_date']) != 0) {
        var_dump($email_list);
        throw new Exception("TestEmailProduct::testGetEmailDates: Invalid email date " . $email_list[0]['email_date']);
      }
      if (strcasecmp('emailexpire', $email_list[0]["email_type"]) != 0) {
        var_dump($email_list);
        throw new Exception("TestEmailProduct::testGetEmailDates: Invalid email type " . $email_list[0]['email_type']);
      }
    }
    echo "TestEmailProduct::testGetEmailDates() succeeded\n";
  }
}
?>

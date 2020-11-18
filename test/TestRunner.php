<?php
include __DIR__ . '/../classes/aProduct.php';
include __DIR__ . '/../classes/ProductTypes.php';
include __DIR__ . '/../classes/DomainProduct.php';
include __DIR__ . '/../classes/PDomainProduct.php';
include __DIR__ . '/../classes/EDomainProduct.php';
include __DIR__ . '/../classes/HostingProduct.php';
include __DIR__ . '/../classes/EmailProduct.php';
include __DIR__ . '/../classes/DbManager.php';
include __DIR__ . '/../classes/ActiveList.php';
include __DIR__ . '/../classes/EmailManager.php';
include __DIR__ . '/../classes/BillingManager.php';
include __DIR__ . '/../classes/EmailTypes.php';
include __DIR__ . '/../classes/DomainManager.php';
include __DIR__ . '/TestDomainProduct.php';
include __DIR__ . '/TestPDomainProduct.php';
include __DIR__ . '/TestEDomainProduct.php';
include __DIR__ . '/TestHostingProduct.php';
include __DIR__ . '/TestEmailProduct.php';

$test_obj = new TestDomainProduct();
$test_obj->runTests();
$test_obj = new TestPDomainProduct();
$test_obj->runTests();
$test_obj = new TestEDomainProduct();
$test_obj->runTests();
$test_obj = new TestHostingProduct();
$test_obj->runTests();
$test_obj = new TestEmailProduct();
$test_obj->runTests();


 ?>
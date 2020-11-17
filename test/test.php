<?php

include __DIR__ . '/../classes/aProduct.php';
include __DIR__ . '/../classes/ProductTypes.php';
include __DIR__ . '/../classes/DomainProduct.php';
include __DIR__ . '/../classes/DbManager.php';
include __DIR__ . '/../classes/ActiveList.php';
include __DIR__ . '/../classes/EmailManager.php';
include __DIR__ . '/../classes/BillingManager.php';
include __DIR__ . '/../classes/EmailTypes.php';
include __DIR__ . '/../classes/DomainManager.php';


$dbm = DbManager::getInstance();
$dp = new DomainProduct();
$al = new ActiveList();
$dm = new DomainManager();
$bm = new BillingManager();
$em = new EmailManager();

$records = $dbm->getActiveRecords();
var_dump($records);
print "Time: " . time() . "\n";


 ?>
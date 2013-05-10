<?php

require_once("./sitemanager.php");

$sitemanager = new SiteManager();

$sitemanager->SetDatabaseServer("localhost");
$sitemanager->SetDatabaseName("php_journal");
$sitemanager->SetDatabaseUsername("root");
$sitemanager->SetDatabasePassword("braindrain");

//purposfully ommitting the closing php tag
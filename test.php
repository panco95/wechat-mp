<?php

require_once "src/MP.php";
require_once "src/MPTools.php";
require_once "src/MPUrls.php";

try {
    $temp = new \Panco\MP\MP("15779410677@163.com", "panco0825", "D://");
    $temp->login();
} catch (\Exception $e) {
    var_dump($e->getMessage());
}

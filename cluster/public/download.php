<?php
require_once("config.php");

$referer = isset($_SERVER["HTTP_REFERER"]) ? trim($_SERVER["HTTP_REFERER"]) : null;

if (strpos($referer, $_config_access_download_referer) === 0){
    //Ok, download
}
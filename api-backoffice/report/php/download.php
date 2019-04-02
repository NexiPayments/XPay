<?php

// Report - Download

$requestURL = "https://int-ecommerce.nexi.it/"; // URL
$requestURI = "ecomm/api/bo/downloadReport"; // URI

// Parametri per calcolo MAC
$apiKey = "<ALIAS>"; // Alias fornito da Nexi
$chiaveSegreta = "<CHIAVE SEGRETA PER CALCOLO MAC>"; // Chiave segreta fornita da Nexi
$idReport = "1"; // Id del report recuperato con l'api ecomm/api/bo/elencoReport
$timeStamp = (time()) * 1000;

// Calcolo MAC
$mac = sha1('apiKey=' . $apiKey . "timeStamp=" . $timeStamp . "idReport=" . $idReport . $chiaveSegreta);

// Parametri
$parametri = array(
    'apiKey' => $apiKey,
    'idReport' => $idReport,
    'timeStamp' => $timeStamp,
    'mac' => $mac
);

// Url da utilizzare per fare il download del report
$urlDownload = $requestURL . $requestURI . '?' . http_build_query($parametri);

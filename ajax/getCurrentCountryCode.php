<?php
/*
// GET COUNTRY CODE (fr, en, ...) :
$dataArray = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$_SERVER['REMOTE_ADDR']));
if (isset($dataArray->geoplugin_countryCode) && !empty($dataArray->geoplugin_countryCode)) {
    $countryCode = $dataArray->geoplugin_countryCode;
} else {
    $countryCode = 'en';
}

// RETURN
echo json_encode(array(
    "countryCode" => $countryCode
));
*/
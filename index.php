<?php
header('Content-Type: text/html;charset=utf-8');
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');
function posturl($url,$data){
        $headerArray = [];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "glt=3" );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output, true);
}

$result = posturl("https://a01ts.sovparents.com/api/getKj.php", [
	"glt" => 3
]);

echo json_encode($result, 256);
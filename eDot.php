<?php

//error_reporting(0);
require 'function.php';
system('clear');
echo $orange.$banner.$cln;
echo "\n\n";
echo $bold.$fgreen."[-] Bot eDot v1 by CatzBurry\n\n".$cln;
    echo $bold . $lblue . "Commands\n";
    echo "========\n\n";
    echo $bold . $fgreen . "[1]$cln Auto Register With AdaOTP$cln\n";
    echo $bold . $fgreen . "[2]$cln Manual Register With OTP$cln\n";
web:
$pilihweb = "[-] Pilih Web ";
$webOTP = input("$bold$orange$pilihweb$cln");
echo "\n";
if($webOTP == 1) {
echo "------------ \e[1;36mAuto Regist With AdaOTP\e[0m -----------".PHP_EOL;
echo "\n";
if(!file_exists("alerts.txt")) {
    inputApikeyOTP:
    $updated = input("[-] Apakah Sudah edit config.json? (y/N)");
    if(strtolower($updated) == "y") {
        file_put_contents("alerts.txt", "off");
    } else if(strtolower($updated) == "n") {
        inputLicense:
        $key = input("[-] Apikey Kamu");
        file_put_contents("config.json", json_encode(['apikey' => $key]));
    } else {
        echo "[!] Pilihan Tidak Tersedia".PHP_EOL;
        goto inputApikeyOTP;
    }
}

$readConfig  = json_decode(file_get_contents("config.json"), true);
$apikey = trim($readConfig['apikey']);

if ($apikey) {
    echo "[-] Apikey Ditemukan: ".$apikey.PHP_EOL;
} else {
    echo  "[!] Apikey Tidak Ditemukan ".PHP_EOL;
    echo  "[!] Silakan Input Data Apikey ".PHP_EOL;
    goto inputLicense;
}

/** GET BALANCE **/
$getBalance = curl("https://adaotp.com/api/get-profile/".$apikey, 0, 0);
$balance = get_between($getBalance[1], '"saldo":"', '",');
$usernameOtp = get_between($getBalance[1], '"username":"', '",');
$messageOTP = get_between($getBalance[1], '{"messages":"', '"');

if(strpos($getBalance[1], '"success":true,"')) {
    if ($saldo == 0) {
        echo "[!] Isi Saldo Dulu Pantek!!".PHP_EOL;
    } else {
        echo "[-] Username: ".$usernameOtp;
        echo " -> Saldo Rp " . number_format($balance, 0, ",", ".").PHP_EOL.PHP_EOL;
    }
} else {
    echo "[!] Message: ".$messageOTP.PHP_EOL.PHP_EOL.PHP_EOL;
}

if ($balance < 1000) {
    die("[!] Saldo Tidak Mencukupi, Silahkan Top Up!!");
}
/** END GET BALANCE **/

$loopp = "[-] Jumlah Reff ";
$kodereff = "[-] Kode Refferal ";
$refferal = input("$bold$orange$kodereff$cln");
$loop = input("$bold$orange$loopp$cln");

echo PHP_EOL;
for($ia=1; $ia <= $loop; $ia++){
    echo " --------------- [ $ia/$loop ] --------------- ".PHP_EOL;
    ulang:
    $deviceId = generateRandomString(36);
    $data = '{"name":"web-sso","secret_key":"3e53440178c568c4f32c170f","device_type":"web","device_id":"'.$deviceId.'"}';
    $lenght = strlen($data);
    $headers = [
        "Host: api-accounts.edot.id",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: gzip, deflate, br"
    ];
    
    $getToken = curl("https://api-accounts.edot.id/api/token/get", $data, $headers);
    $code = get_between($getToken[1], '"code":', ',"');
    $token_code = get_between($getToken[1], '"token_code":"', '",');
    if ($code == 200) {
        echo "[-] Status Code 200";
        echo " -> token_code: ".$token_code.PHP_EOL;
        $fullName = getName();
        $data = '{"fullname":"'.$fullName.'"}';
        $lenght = strlen($data);
        $headers = [
            "Host: api-accounts.edot.id",
            "Content-Type: application/json",
            "Origin: https://accounts.edot.id",
            "Accept-Encoding: gzip, deflate, br",
            "Connection: keep-alive",
            "Accept: */*",
            "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
            "Content-Length: ".$lenght,
            "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
            "sso-token: ".$token_code,
        ];
        $getUsername = curl("https://api-accounts.edot.id/api/user/get_suggestion_username", $data, $headers);
        $codeUsername = get_between($getUsername[1], '"code":', ',"');
        $username = get_between($getUsername[1], '"data":["', '",');
        if ($codeUsername == 200) {
            echo "[-] Status Code 200";
            echo " -> Username: ".$username.PHP_EOL;
            getnomor:
            $createOrder = curl("https://adaotp.com/api/set-orders/".$apikey."/440");
            $orderID = get_between($createOrder[1], '"order_id":"', '",');
            $messageOrder = get_between($createOrder[1], '"messages":"', '"');
            $nomor = get_between($createOrder[1], '"number":"', '","');
            if(strpos($createOrder[1], '"success":true,"')) {
                echo "[-] Message: ".$messageOrder." - Nomor: ".$nomor.PHP_EOL;
            } else {
                echo "[-] Message: ".$messageOrder.PHP_EOL;
                goto getnomor;
            }
            $data = '{"phone_number":"'.$nomor.'","type":"verify_phone","send_type":"sms"}';
            $lenght = strlen($data);
            $headers = [
                "Host: api-accounts.edot.id",
                "Content-Type: application/json",
                "Origin: https://accounts.edot.id",
                "Accept-Encoding: gzip, deflate, br",
                "Connection: keep-alive",
                "Accept: */*",
                "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                "Content-Length: ".$lenght,
                "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                "sso-token: ".$token_code,
            ];
            $sendOTP = curl("https://api-accounts.edot.id/api/user/send_otp_phone", $data, $headers);
            $codesendOTP = get_between($sendOTP[1], '"code":', ',"');
            $msgotp = get_between($sendOTP[1], '"data":"', '"}');
            if ($codesendOTP == 200) {
                echo "[-] Status Code 200";
                echo " -> ".$msgotp.PHP_EOL;
                $time = time();
                CheckUlangOTP:
                $getOTP = curl("https://adaotp.com/api/get-orders/".$apikey."/".$orderID, 0, 0);
                $orderID = get_between($getOTP[1], '"order_id":"', '",');
                $messageOTP = get_between($getOTP[1], '"messages":"', '"');
                $successdataGet = get_between($getOTP[1], '"success":', ',"');
                $otp = get_between($getOTP[1], '"<#> ', 'adalah');
                $otpbos = trim($otp);
                if($otp) {
                    echo "[-] Message: ".$messageOTP." - OTP: ".$otpbos.PHP_EOL;
                } else {
                    if (time()-$time > 30) {
                        echo "[!] Gagal Mendapatkan OTP.".PHP_EOL;
                        $cancle = curl("https://adaotp.com/api/cancle-orders/".$apikey."/".$orderID, 0, 0);
                        goto getnomor;
                    } else {
                        goto CheckUlangOTP;
                    }
                }
                $data = '{"phone_number":"'.$nomor.'","otp":"'.$otpbos.'","description":"register"}';
                $lenght = strlen($data);
                $headers = [
                    "Host: api-accounts.edot.id",
                    "Content-Type: application/json",
                    "Origin: https://accounts.edot.id",
                    "Accept-Encoding: gzip, deflate, br",
                    "Connection: keep-alive",
                    "Accept: */*",
                    "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                    "Content-Length: ".$lenght,
                    "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                    "sso-token: ".$token_code,
                ];
                $verifotp = curl("https://api-accounts.edot.id/api/user/verify_otp_phone", $data, $headers);
                $codeverifOTP = get_between($verifotp[1], '"code":', ',"');
                $msgverif = get_between($verifotp[1], '"data":"', '"}');
                if ($codeverifOTP == 200) {
                    echo "[-] Status Code 200";
                    echo " -> ".$msgverif.PHP_EOL;
                    $data = '{"fullname":"'.$fullName.'","email":"","username":"'.$username.'","recovery_email":"","phone_number":"'.$nomor.'","password":"Jumadygantengnihbos11#$","date_of_birth":"2000-01-05","gender":"pria","security_question_id":"1","security_question_answer":"Sukinah","response_type":"code","client_id":"0c21679b392bc480c87c150303ab255d","referral_code":"'.strtoupper($refferal).'"}';
                    $lenght = strlen($data);
                    $headers = [
                        "Host: api-accounts.edot.id",
                        "Content-Type: application/json",
                        "Origin: https://accounts.edot.id",
                        "Accept-Encoding: gzip, deflate, br",
                        "Connection: keep-alive",
                        "Accept: */*",
                        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                        "Content-Length: ".$lenght,
                        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                        "sso-token: ".$token_code,
                    ];
                    $register = curl("https://api-accounts.edot.id/api/user/register", $data, $headers);
                    $registerfinal = get_between($register[1], '{"redirect_url":"', '"}');
                    if ($registerfinal) {
                        echo "[-] Sukses Mendaftarkan $username Kode Reff: ".strtoupper($refferal).PHP_EOL;
                        echo "[-] Redirect Url ".$registerfinal.PHP_EOL;
                        $finish = curl("https://adaotp.com/api/finish-orders/".$apikey."/".$orderID, 0, 0);
                        $getToken_login = curl($registerfinal, 0, 0);
                        sleep(10);
                        $accessToken = get_between($getToken_login[2], '[access-token] => ', ')');
                        if ($accessToken) {
                            echo "[-] Access Token ".trim($accessToken).PHP_EOL;
                        }
                    } else if (strpos($register[1], '"code":400,"')) {
                        $msg_registerfinal = get_between($register[1], '{"message":"', '","');
                        echo "[!] Gagal Mendaftarkan User - Reason: ".$msg_registerfinal.PHP_EOL;
                        $finish = curl("https://adaotp.com/api/finish-orders/".$apikey."/".$orderID, 0, 0);
                        goto ulang;
                    } else {
                        echo "[!] Gagal Mendaftarkan User".PHP_EOL;
                        $finish = curl("https://adaotp.com/api/finish-orders/".$apikey."/".$orderID, 0, 0);
                        goto ulang;
                    }
                } else {
                    echo "[!] Gagal Verifikasi OTP".PHP_EOL;
                    $finish = curl("https://adaotp.com/api/finish-orders/".$apikey."/".$orderID, 0, 0);
                    goto ulang;
                }
            } else {
                echo "[!] Gagal Mengirimkan OTP".PHP_EOL;
                $cancle = curl("https://adaotp.com/api/cancle-orders/".$apikey."/".$orderID, 0, 0);
                goto ulang;
            }
        } else {
            echo "[!] Gagal Mendaptkan Username".PHP_EOL;
            goto ulang;
        }
    } else {
        echo "[!] Gagal Mendaftarkan Token".PHP_EOL;
        goto ulang;
    }
}
} elseif($webOTP == 2) {
echo "------------ \e[1;36mManual Regist With OTP\e[0m -----------".PHP_EOL;
echo PHP_EOL;
$loopp = "[-] Jumlah Reff ";
$kodereff = "[-] Kode Refferal ( CATZBURR1 ) ";
$refferal = input("$bold$orange$kodereff$cln");
$loop = input("$bold$orange$loopp$cln");

echo PHP_EOL;
for($ia=1; $ia <= $loop; $ia++){
    echo " --------------- \e[1;36m[ $ia/$loop ]\e[0m --------------- ".PHP_EOL;
echo PHP_EOL;
    ulang1:
    $deviceId = generateRandomString(36);
    $data = '{"name":"web-sso","secret_key":"3e53440178c568c4f32c170f","device_type":"web","device_id":"'.$deviceId.'"}';
    $lenght = strlen($data);
    $headers = [
        "Host: api-accounts.edot.id",
        "Content-Type: application/json",
        "Origin: https://accounts.edot.id",
        "Connection: keep-alive",
        "Accept: */*",
        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
        "Content-Length: ".$lenght,
        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
        "Accept-Encoding: gzip, deflate, br"
    ];
    $getToken = curl("https://api-accounts.edot.id/api/token/get", $data, $headers);
    $code = get_between($getToken[1], '"code":', ',"');
    $token_code = get_between($getToken[1], '"token_code":"', '",');
    if ($code == 200) {
        echo "[-] Status Code 200\n";
        echo "[-] Token_code: ".$token_code.PHP_EOL;
        $fullName = getName();
        $data = '{"fullname":"'.$fullName.'"}';
        $lenght = strlen($data);
        $headers = [
            "Host: api-accounts.edot.id",
            "Content-Type: application/json",
            "Origin: https://accounts.edot.id",
            "Accept-Encoding: gzip, deflate, br",
            "Connection: keep-alive",
            "Accept: */*",
            "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
            "Content-Length: ".$lenght,
            "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
            "sso-token: ".$token_code,
        ];
        $getUsername = curl("https://api-accounts.edot.id/api/user/get_suggestion_username", $data, $headers);
        $codeUsername = get_between($getUsername[1], '"code":', ',"');
        $username = get_between($getUsername[1], '"data":["', '",');
        if ($codeUsername == 200) {
            echo "[-] Username: ".$username.PHP_EOL;
            getnomor2:
            $nomor = input("[-] Nomor");
            $data = '{"phone_number":"'.$nomor.'","type":"verify_phone","send_type":"sms"}';
            $lenght = strlen($data);
            $headers = [
                "Host: api-accounts.edot.id",
                "Content-Type: application/json",
                "Origin: https://accounts.edot.id",
                "Accept-Encoding: gzip, deflate, br",
                "Connection: keep-alive",
                "Accept: */*",
                "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                "Content-Length: ".$lenght,
                "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                "sso-token: ".$token_code,
            ];
            $sendOTP = curl("https://api-accounts.edot.id/api/user/send_otp_wa", $data, $headers);
            $codesendOTP = get_between($sendOTP[1], '"code":', ',"');
            $msgotp = get_between($sendOTP[1], '"data":"', '"}');
            if ($codesendOTP == 200) {
                echo " -> Otp Sent".PHP_EOL;
                $time = time();
                CheckUlangOTP1:
                $otpbos = input("[-] OTP");
                $data = '{"phone_number":"'.$nomor.'","otp":"'.$otpbos.'","description":"register"}';
                $lenght = strlen($data);
                $headers = [
                    "Host: api-accounts.edot.id",
                    "Content-Type: application/json",
                    "Origin: https://accounts.edot.id",
                    "Accept-Encoding: gzip, deflate, br",
                    "Connection: keep-alive",
                    "Accept: */*",
                    "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                    "Content-Length: ".$lenght,
                    "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                    "sso-token: ".$token_code,
                ];
                $verifotp = curl("https://api-accounts.edot.id/api/user/verify_otp_phone", $data, $headers);
                $codeverifOTP = get_between($verifotp[1], '"code":', ',"');
                $msgverif = get_between($verifotp[1], '"data":"', '"}');
                if ($codeverifOTP == 200) {
                    echo " -> ".$msgverif.PHP_EOL;
                    $data = '{"fullname":"'.$fullName.'","email":"","username":"'.$username.'","recovery_email":"","phone_number":"'.$nomor.'","password":"Jumadygantengnihbos11#$","date_of_birth":"2000-01-05","gender":"pria","security_question_id":"1","security_question_answer":"Sukinah","response_type":"code","client_id":"0c21679b392bc480c87c150303ab255d","referral_code":"'.strtoupper($refferal).'"}';
                    $lenght = strlen($data);
                    $headers = [
                        "Host: api-accounts.edot.id",
                        "Content-Type: application/json",
                        "Origin: https://accounts.edot.id",
                        "Accept-Encoding: gzip, deflate, br",
                        "Connection: keep-alive",
                        "Accept: */*",
                        "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/109.0.5414.83 Mobile/15E148 Safari/604.1",
                        "Content-Length: ".$lenght,
                        "Accept-Language: en-GB,en-US;q=0.9,en;q=0.8",
                        "sso-token: ".$token_code,
                    ];
                    $register = curl("https://api-accounts.edot.id/api/user/register", $data, $headers);
                    $registerfinal = get_between($register[1], '{"redirect_url":"', '"}');
                    if ($registerfinal) {
                        echo "[-] Sukses Mendaftarkan $username Kode Reff: ".strtoupper($refferal).PHP_EOL;
                        echo "[-] Redirect Url ".$registerfinal.PHP_EOL;
                        $getToken_login = curl($registerfinal, 0, 0);
                    } else {
                        echo "[!] Gagal Mendaftarkan User".PHP_EOL;
                        goto ulang1;
                    }
                } else {
                    echo "[!] Gagal Verifikasi OTP".PHP_EOL;
                    goto ulang1;
                }
            } else {
                echo "[!] Gagal Mengirimkan OTP".PHP_EOL;
                sleep(3);
                goto ulang1;
            }
        } else {
            echo "[!] Gagal Mendaptkan Username".PHP_EOL;
            goto ulang1;
        }
    } else {
        echo "[!] Gagal Mendaftarkan Token".PHP_EOL;
        goto ulang1;
    }
}
}
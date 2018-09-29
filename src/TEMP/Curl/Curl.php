<?php

namespace TEMP\Curl;

class Curl
{

    public function get($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ?? '');

        if(substr($url,0,8)=='https://'){
            curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        }

        $sendCH = curl_exec($ch);

        curl_close($ch);

        return $sendCH;
    }

}

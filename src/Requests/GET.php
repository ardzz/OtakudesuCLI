<?php

/**
 * OtakudesuCLI
 * 
 * @author Ardan <ardzz@indoxploit.or.id>
 * @package Library
 * @copyright Otakudesu
 */

namespace Otakudesu\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ConnectException;
use Otakudesu\Config\Config;

class GET { 
    function Run($path){
        $request = new Client([
            "base_uri" => Config::Otakudesu_URL,
        ]);
        try {
            $lookup = $request->request("GET", $path, [
                //"proxy" => Config::Proxy,
                "verify" => false,
                "allow_redirects" => false
            ]);
            return $lookup->getBody()->getContents();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $response = $e->getResponse();
            return $response->getBody()->getContents();
        }
    }
}

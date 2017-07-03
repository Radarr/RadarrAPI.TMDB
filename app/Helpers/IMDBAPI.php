<?php
/**
 * Created by PhpStorm.
 * User: leonardogalli
 * Date: 21.06.17
 * Time: 10:54
 */

namespace App\Helpers;
use Illuminate\Foundation\Testing\HttpException;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;


final class IMDBAPI
{
    private $API_KEY = "";
    private $API_SECRET = "";

    private $client;

    /**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function shared()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new IMDBAPI();
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct()
    {
        $this->API_KEY = Config::get("app.imdb_key");
        $this->API_SECRET = Config::get("app.imdb_secret");
        $this->client = new Client([]);
    }

    public function getJSON($path = "", $queryItems = array(), $host = "api.imdbws.com")
    {
        try
        {
            $response = $this->request($path, $queryItems, "GET", $host, null);
            return json_decode($response->getBody(), true);
        }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            try
            {
                abort($response->getStatusCode(), json_decode($response->getBody(), true)["message"]);
            }
            catch (Exception $e)
            {
                abort($response->getStatusCode(), $response->getReasonPhrase());
            }

        }

        return $response;
    }

    public function request($path = "", $queryItems = array(), $method = "GET", $host = "api.imdbws.com", $postBody = null)
    {
        $headers = ["user-agent" => "IMDb/7.2 (iPhone; iOS 10.2.1)"];
        $signedHeaders = [];

        if ($path == "" || $path == null)
        {
            $path = "/";
        }

        if (!(stripos($path, "/") === 0))
        {
            $path = "/" . $path;
        }

        $query = $this->getCanocialQueryStr($queryItems);

        $url = "https://$host$path?$query";
        $timeStr = strftime("%a, %d %b %Y %H:%M:%S %Z");
        $headers["x-amz-date"] = $timeStr;

        $headers["x-amzn-authorization"] = $this->createRequestSignature($method, $host, $path, $query, $postBody, $headers);

        //dd($headers);

        $response = $this->client->request($method, $url, [
            "headers" => $headers,
            'allow_redirects' => false
        ]);

        $statusCode = $response->getStatusCode();

        if ($statusCode == 301 || $statusCode == 302)
        {
            return $this->request(json_decode($response->getBody(), true)["link"], $queryItems, $method, $host, $postBody);
        }

        return $response;
    }

    private function createRequestSignature($method, $host, $path, $query, $postBody, $headers)
    {
        $signedHeaders = [];
        foreach ($headers as $key => $value)
        {
            if (stripos($key, "amz")!= false)
            {
                $signedHeaders[$key] = $value;
            }
        }

        //$headersStr = join(";", $signedHeaders);

        $cHeaders = "";
        $cHeaders .= "host:" . $host . "\n";

        foreach ($signedHeaders as $key => $value) {
            $cHeaders .= $key . ":" . $value . "\n";
        }

        $toSign = "$method\n$path\n$query\n$cHeaders\n";
        //var_dump($toSign);
        //dd($toSign);

        $signature = base64_encode(hash_hmac("sha256", hash("sha256", $toSign, true), $this->API_SECRET, True));

        return "AWS3 AWSAccessKeyId={$this->API_KEY},Algorithm=HmacSHA256,Signature=$signature,SignedHeaders=" . join(";", array_keys($signedHeaders));
    }

    private function getCanocialQueryStr($queryItems)
    {
        $queryStr = "";
        foreach ($queryItems as $key => $value) {
            if (is_array($value))
            {
                foreach ($value as $v)
                {
                    $queryStr .= $key . "=" . $v . "&";
                }
            }
            else
            {
                $queryStr .= $key . "=" . $value . "&";
            }

        }

        $queryStr = rtrim($queryStr, ",");
        return $queryStr;
    }


}
<?php
declare(strict_types=1);

namespace MediaParkPK\VeChainThor;

use Comely\Http\Exception\HttpException;
use Comely\Http\Exception\HttpRequestException;
use Comely\Http\Exception\HttpResponseException;
use Comely\Http\Exception\SSL_Exception;
use Comely\Http\Request;
use MediaParkPK\VeChainThor\Exception\VechainThorAPIException;
use MediaParkPK\VeChainThor\Exception\VchainAPIException;

/**
 * Class HttpClient
 * @package SkyCoin
 */
class HttpClient
{
    /** @var string */
    private string $ip;
    /** @var int|null */
    private ?int $port;
    /** @var string|null */
    private ?string $username;
    /** @var string|null */
    private ?string $password;
    /** @var bool */
    private bool $https;

    /**
     * HttpClient constructor.
     * @param string $ip
     * @param int|null $port
     * @param string|null $username
     * @param string|null $password
     * @param bool $https
     */
    public function __construct(string $ip, ?int $port = null, ?string $username = "", ?string $password = "", bool $https = false)
    {
        $this->ip = $ip;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->https = $https;
    }

    /**
     * @param string $endpoint
     * @param array $params
     * @param array $headers
     * @param string $httpMethod
     * @return array
     * @throws VechainThorAPIException
     */
    public function sendRequest(string $endpoint, array $params = [], array $headers = [], string $httpMethod = "GET"): array
    {
        try {
            $url = $this->generateUri($endpoint);
            $req = new Request($httpMethod, $url);

            // Set Request Headers
            $req->headers()->set("accept", "application/json");

            // Set Dynamic Headers
            if ($headers) {
                foreach ($headers as $key => $value) {
                    $req->headers()->set($key, $value);
                }
            } else {
                $req->headers()->set("content-type", "application/json");
            }

            // Set Request Body/Params
            if ($params) {
                $req->payload()->use($params);
            }

            $request = $req->curl();
            if ($this->username && $this->password) {
                $request->auth()->basic($this->username, $this->password);
            }

            // Send The Request
            $res = $request->send();
            $errCode = $res->code();
            if ($errCode !== 200) {
                $errMsg = $res->body()->value();
                if ($errMsg) {
                    $errMsg = trim(strval(explode("-", $errMsg)[1] ?? ""));
                    throw new VechainThorAPIException($errMsg ? $errMsg : sprintf('HTTP Response Code %d', $errCode), $errCode);
                }
            }

            return $res->payload()->array();
        }
        catch (HttpException $e)
        {
            throw new VechainThorAPIException(sprintf('[%s][%s] %s', get_class($e), $e->getCode(), $e->getMessage()));
        }


    }

    /**
     * @param string|null $endpoint
     * @return string
     */
    public function generateUri(?string $endpoint = null): string
    {
        $url = sprintf('%s://%s', $this->https ? "https" : "http", $this->ip);
        if ($this->port) {
            $url .= ":" . $this->port;
        }

        if ($endpoint) {
            $url .= "/" . ltrim($endpoint, "/");
        }

        return $url;
    }
}

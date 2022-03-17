<?php

namespace KalprajSolutions\Bitly\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

use KalprajSolutions\Bitly\Exceptions\AccessDeniedException;
use KalprajSolutions\Bitly\Exceptions\InvalidResponseException;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

use function json_decode;
use function json_encode;

/**
 * Class BitlyClient
 */
class BitlyClient
{
    /** @var string $token */
    private $endpoint = 'https://api-ssl.bitly.com/v4/bitlinks';

    /** @var ClientInterface */
    private $client;

    private $token;
    private $url;
    private $title;
    private $domain = "bit.ly";
    private $tags;
    private $guid;
    private $proxy;

    private $quick = false;

    /**
     * @param ClientInterface $client
     * @param string          $token
     */
    public function __construct(ClientInterface $client, $token)
    {
        $this->client = $client;
        $this->token  = $token;
    }

    public function url(string $url)
    {
        empty($url) ? null : $this->url = $url;
        return $this;
    }

    public function title(string $title)
    {
        empty($title) ? null : $this->title = $title;
        return $this;
    }

    public function domain(string $domain = "bit.ly")
    {
        empty($domain) ? null : $this->domain = $domain;
        return $this;
    }

    public function tags(array $tags)
    {
        empty($tags) ? null : $this->tags = $tags;
        return $this;
    }

    public function guid(string $guid)
    {
        empty($guid) ? null : $this->guid = $guid;
        return $this;
    }


    /**
     * Note: You can provide proxy URLs that contain a scheme, username, and password. For example, "http://username:password@192.168.16.1:10".
     */
    public function proxy(string $proxy)
    {
        empty($proxy) ? null : $this->proxy = $proxy;
        return $this;
    }

    public function get()
    {
        return $this->BitlinkShorten();
    }

    public function short(string $url)
    {
        $this->url = $url;
        $this->quick = true;
        return $this->BitlinkShorten();
    }

    /**
     * @return string shorten URL.
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \KalprajSolutions\Bitly\Exceptions\AccessDeniedException
     * @throws \KalprajSolutions\Bitly\Exceptions\InvalidResponseException
     */
    public function BitlinkShorten()
    {
        if($this->quick){

            $this->endpoint = 'https://api-ssl.bitly.com/v4/shorten';
            $data = array_filter([
                'long_url' => $this->url,
                'domain' => $this->domain,
                'group_guid' => $this->guid,
            ]);

        }else{
            $data = array_filter([
                'long_url' => $this->url,
                'domain' => $this->domain,
                'title' => $this->title,
                'tags' => $this->tags,
                'group_guid' => $this->guid,
            ]);
        }



        try {

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type'  => 'application/json'
            ]);

            //If proxy is provided then add proxy option
            if ($this->proxy) {
                $response = $response->withOptions([
                    'proxy' => $this->proxy,
                ]);
            }

            $response = $response->post($this->endpoint, $data);
            
        } catch (RequestException $e) {

            if ($e->getResponse() !== null && $e->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN) {
                throw new AccessDeniedException('Invalid access token.', $e->getCode(), $e);
            }

            throw new InvalidResponseException($e->getMessage(), $e->getCode(), $e);
        }

        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();

        if ($statusCode === Response::HTTP_FORBIDDEN) {
            throw new AccessDeniedException('Invalid access token.');
        }

        if (!in_array($statusCode, [Response::HTTP_OK, Response::HTTP_CREATED])) {
            throw new InvalidResponseException('The API does not return a 200 or 201 status code. Response: ' . $content);
        }

        $data = json_decode($content, true);

        if (isset($data['link'])) {
            return $data['link'];
        }

        if (isset($data['data']['link'])) {
            return $data['data']['link'];
        }

        throw new InvalidResponseException('The response does not contain a shortened link. Response: ' . $content);
    }
}

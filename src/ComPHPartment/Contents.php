<?php
namespace ComPHPartment;

use LogWrapper\LogWrapper;
use GuzzleHttp\Exception\ClientException;

class Contents
{
    public $client;
    public static $dataFolder;

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
        self::$dataFolder = ComPHPartment::getBasePath() . '/data';
    }

    public function create($url, $title, array $tags = [])
    {
        $tags = implode(',', $tags);

        try {
            $response = $this->client->post(
                ComPHPartment::POCKET_ADD_URI,
                [
                    'headers'     => ComPHPartment::$requestHeaders,
                    'form_params' => [
                        'consumer_key' => ComPHPartment::$key,
                        'access_token' => ComPHPartment::$accessToken,
                        'url'          => $url,
                        'title'        => $title,
                    ],
                ]
            );
        } catch (ClientException $e) {
            $pocketExtraInfo = implode(', ', $e->getResponse()->getHeaders());
            throw new \Exception('Failed to create content on the Pocket API: ' . $pocketExtraInfo);
        }
    }

    public function retrieve($pocketParams = [], $itemID = null)
    {
        $params = array_merge(
            [
                'consumer_key' => ComPHPartment::$key, 
                'access_token' => ComPHPartment::$accessToken
            ], 
            $pocketParams
        );

        try {
            $response = $this->client->post(
                ComPHPartment::POCKET_GET_URI,
                [
                    'headers'     => ComPHPartment::$requestHeaders,
                    'form_params' => $params,
                ]
            );
        } catch (ClientException $e) {
            $pocketExtraInfo = implode(', ', $e->getResponse()->getHeaders());
            throw new \Exception(('Failed to retrieve contents from the Pocket API: ' . $pocketExtraInfo));
        }

        $contents = json_decode($response->getBody());

        return $contents;
    }

    public function update()
    {
    }

    public function delete()
    {
    }

    public function store($contents)
    {
        file_put_contents(self::$dataFolder . '/' . time() . '.data', serialize($contents));
    }
}

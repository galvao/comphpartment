<?php
/**
 * Contents - Creating, Retrieving, Updating and Deleting Content through ComPHPartment.
 *
 * @package ComPHPartment
 * @author Er Galvão Abbott <galvao@php.net>
 * @see https://github.com/galvao/comphpartment
 * @version 0.1.0-alpha
 * @license BSD
 */

namespace ComPHPartment;

use LogWrapper\LogWrapper;
use GuzzleHttp\Exception\ClientException;

class Contents
{
    /** @var \GuzzleHttp\Client $client The Guzzle client used for the API access */
    public $client;
    /** @var string $dataFolder Where to store the Contents if desired.  */
    public static $dataFolder;

    /**
     * Constructor - Stores the client and defines the data folder
     * @param \GuzzleHttp\Client $client Guzzle's client to perform the requests
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
        self::$dataFolder = ComPHPartment::getBasePath() . '/data';
    }

    /**
     * Creates content on the user's Pocket
     * @param string $url The url of the item to be created
     * @param string $title of the item to be created
     * @throws \Exception If the request has failed
     */
    public function create($url, $title)
    {
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

    /**
     * Retrieve Content from the user's Pocket
     * @param array $pocketParams Parameters to be used on Pocket's API (@see https://getpocket.com/developer/docs/v3/retrieve)
     * @param int $itemID ID of the item to be retrieved
     * @throws \Exception If the request has failed
     *
     * @return object The Content that was retrieved
     */
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

    /**
     * [Placeholder] Updates an item on the user's Pocket
     */
    public function update()
    {
    }

    /**
     * [Placeholder] Deletes an item on the user's Pocket
     */
    public function delete()
    {
    }

    /**
     * Stores Content for offline usage. To be supersceded by the Cache Implementation (See issue #4)
     * @param object The Content to be stored
     */
    public function store($contents)
    {
        file_put_contents(self::$dataFolder . '/' . time() . '.data', serialize($contents));
    }
}

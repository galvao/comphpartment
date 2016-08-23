<?php
namespace ComPHPartment;

use ComPHPartment\LogWrapper\LogWrapper;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * ComPHPartment - Pocket's API access through PHP + Guzzle
 * @package ComPHPartment
 * @author Er Galvão Abbott <galvao@php.net>
 * @see https://github.com/galvao/comphpartment
 * @version 1.0.0-alpha
 * @license BSD
 */

class ComPHPartment
{
    /** @var \ComPHPartment\LogWrapper\LogWrapper $executionLog Execution LogWrapper */
    public $executionLog;
    /** @var \GuzzleHttp\Client $client The Guzzle client used for the API access */
    public $client;
    /** @var array $requestHeaders Headers used for the requests and responses to/from Pocket's API */
    public static $requestHeaders = [
        'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF8',
        'X-Accept'     => 'application/json',
    ];
    /** @var string $redirectURI Where to redirect once API access is authorized */
    public static $redirectURI;
    /** @var string $key Consumer Key for the Application */
    public static $key;
    /** @var string $token Authentication  Token from Pocket */
    public $token;
    /** @var string $accessToken 'Final' Token from Pocket after Authorization */
    public static $accessToken;
    /** @var string $basePath The base path for the entire application*/
    public static $basePath;

    /**
     * Constants related to the Pocket API
     */

    const POCKET_API_VERSION        = 3;
    const POCKET_BASE_URI           = 'https://getpocket.com';
    const POCKET_AUTHENTICATION_URI = '/v' . self::POCKET_API_VERSION . '/oauth/request';
    const POCKET_AUTHORIZATION_URI  = '/auth/authorize';
    const POCKET_TOKEN_URI          = '/v' . self::POCKET_API_VERSION . '/oauth/authorize';
    const POCKET_GET_URI            = '/v' . self::POCKET_API_VERSION . '/get';
    const POCKET_ADD_URI            = '/v' . self::POCKET_API_VERSION . '/add';
    const POCKET_MOD_URI            = '/v' . self::POCKET_API_VERSION . '/send';

    /**
     * Gets the base path for the entire application
     * @return string The absolute base path for ComPHPartment
     * @since 0.1.0-alpha
     */
    public static function getBasePath()
    {
        if (is_null(self::$basePath)) {
            self::$basePath = realpath(__DIR__ . '/../..');
        }

        return self::$basePath;
    }

    /**
     * Gets the configuration used to access the Pocket API
     * @return array The configuration parameters
     * @throws \Exception if the configuration file can't be found or read
     * @since 0.1.0-alpha
     */
    public function getConfig()
    {
        $configFile = self::getBasePath() . '/config.json';

        if (!is_file($configFile) or !is_readable($configFile)) {
            throw new \Exception('Can\'t read the config file');
        }

        return json_decode(file_get_contents(self::getBasePath() . '/config.json'));
    }

    /**
     * Sets the configuration needed to access the Pocket API
     * @return void
     * @since 0.1.0-alpha
     */
    public function setConfig()
    {
        $config = $this->getConfig();

        if (!isset($config->consumerKey)) {
            throw new \Exception('\'consumerKey\' is a required config key.');
        }

        self::$key = $config->consumerKey;
    }

    /**
     * Instantiates the loggers and the Guzzle client
     * @param boolean If Guzzle should debug the requests
     * @todo Adjust the code so Guzzle writes to the proper log file.
     */
    public function __construct($guzzleDebug = false)
    {
        LogWrapper::$loggers['request'] = $guzzleDebug;

        LogWrapper::setLoggers();

        $this->executionLog = LogWrapper::getLogger('execution');

        if ($guzzleDebug === true) {
            $guzzleDebug = $this->requestLog = LogWrapper::getLogger('request');
        }

        try {
            $this->setConfig();
        } catch (\Exception $e) {
            throw new \Exception('Impossible to instantiate ComPHPartment without a proper config: ' . $e->getMessage());
        }

        $this->client = new Client(['base_uri' => self::POCKET_BASE_URI, 'debug' => $guzzleDebug]);
    }

    /**
     * Tries to autnenticate with the Pocket API
     * @throws \Exception - If the authentication process has failed
     * @return boolean 
     */
    public function authenticate()
    {
        if (is_null(self::$redirectURI)) {
            throw new \Exception('A URI to redirect the user is required.');
        }

        $this->executionLog->info('Trying to authenticate to Pocket API');

        try {
            $response = $this->client->post(
                self::POCKET_AUTHENTICATION_URI,
                [
                    'headers' => self::$requestHeaders,
                    'form_params' => [
                        'consumer_key' => self::$key,
                        'redirect_uri' => self::$redirectURI,
                    ],
                    'timeout' => 10,
                ]
            );
        } catch (ClientException $e) {
            $pocketExtraInfo = implode(', ', $e->getResponse()->getHeaders());
            throw new \Exception('Autnenticaton with the Pocket API has failed: ' .  $pocketExtraInfo);
        }

        $this->executionLog->info('Successfully authenticated to Pocket API');

        $responseBody = json_decode($response->getBody());
        
        $this->token = $responseBody->code;
        return true;
    }

    /**
     * Authorization process from the Pocket API
     * @throws \Exception - If the authorization process has failed
     * @return string The username
     */
    public function authorize()
    {
        if (is_null(self::$redirectURI)) {
            throw new \Exception('A URI to redirect the user is required.');
        }

        $this->executionLog->info('Waiting for User Authorization');

        try {
            $response = $this->client->post(
                self::POCKET_TOKEN_URI,
                [
                    'headers' => self::$requestHeaders,
                    'form_params' => [
                        'consumer_key' => self::$key,
                        'code'         => $this->token,
                    ],
                ]
            );
        } catch (ClientException $e) {
            $responseHeaders = $e->getResponse()->getHeaders();
            $pocketExtraInfo = implode(', ', array_column($responseHeaders, 0));
            throw new \Exception('Autnorization with the Pocket API has failed: ' . $pocketExtraInfo);
        }

        $this->executionLog->info('Successfully authorized.');
        
        $responseBody = json_decode($response->getBody());

        self::$accessToken = $responseBody->access_token;

        return $responseBody->username;
    }
}

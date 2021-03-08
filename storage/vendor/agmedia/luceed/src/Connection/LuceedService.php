<?php

namespace Agmedia\Luceed\Connection;

use Agmedia\Helpers\Log;

/**
 * Class LuceedService
 * @package Agmedia\Luceed\Connection
 */
class LuceedService
{

    /**
     * @var mixed|string
     */
    private $base_url;

    /**
     * @var mixed|string
     */
    private $username;

    /**
     * @var mixed|string
     */
    private $password;

    /**
     * @var string
     */
    public $env;


    /**
     * LuceedService constructor.
     */
    public function __construct()
    {
        $this->base_url = agconf('service.base_url');
        $this->username = agconf('service.username');
        $this->password = agconf('service.password');
        $this->env      = agconf('env');
    }


    /**
     * @param string $url
     * @param string $option
     *
     * @return mixed
     */
    public function get(string $url, string $option = '')
    {
        Log::store($url, 'luceed_get_array');
        Log::store($option, 'luceed_get_array');

        // Local or testing enviroment.
        if ($this->env == 'local') {
            return file_get_contents(DIR_UPLOAD . 'luceed_json/' . $url);
        }

        // Production or live enviroment.
        try {
            $ch = curl_init($this->base_url . $url . $option);
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        } catch (\Exception $exception) {
            $this->log($url . $option, $exception);
        }
    }


    /**
     * @param string $url
     * @param array  $body
     *
     * @return mixed
     */
    public function post(string $url, array $body)
    {
        Log::store($url, 'luceed_post_array');
        Log::store($body, 'luceed_post_array');
        Log::store(json_encode($body), 'luceed_post_array');

        // Local or testing enviroment.
        if ($this->env == 'local') {
            return [];
        }

        // Production or live enviroment.
        try {
            $ch = curl_init($this->base_url . $url);
            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
            curl_setopt ($ch, CURLOPT_POST, true);
            curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;

        } catch (\Exception $exception) {
            $this->log($url, $exception);
        }
    }


    /**
     * @param string     $type
     * @param \Exception $exception
     */
    private function log(string $type, \Exception $exception): void
    {
        Log::store($type, 'luceed_service_error');
        Log::store($exception->getMessage(), 'luceed_service_error');
    }
}
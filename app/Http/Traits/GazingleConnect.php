<?php

namespace App\Http\Traits;


use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\JsonResponse;
use \GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait GazingleConnect {

    public $currentMicroservice = 'equipment';

    protected $servers = [
        'equipment' => [
            'url' => 'https://equipment.gazingle.com',
            'crud_prefix' => '/equipment',
            'mutations' => '/mutations',
        ],
        'vendors' => [
            'url' => 'https://vendors.gazingle.com',
            'crud_prefix' => '/vendors',
            'mutations' => '/mutations',
        ],
        'activities' => [
            'url' => 'http://159.89.146.220',
            'crud_prefix' => '/activities',
            'mutations' => '/mutations',
        ],
    ];


    /*
     * Determines if server is exist
     */
    protected function _serverExist($serverName){
        $exist = false;
        foreach($this->servers as $key => $val){
            if($key === $serverName){
                return $val;
            }
        }
        return $exist;
    }

    protected function _parseGuzzleException($exception, $server){
        if($exception instanceof RequestException){
            if($exception->getResponse())
                return [$exception->getRequest(), $exception->getResponse()];
        }
        if($exception instanceof ConnectException){
            return $this->serverError('Cannot connect to url'.$server['url']);
        }
        return $this->serverError($exception->getMessage());
    }

    /**
     * Call GazingleCrud index method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function indexFrom($serverName, $params = []){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->get($server['url'].$server['crud_prefix'], [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ],
                    'query' => (!empty($params)) ? $params : [],
                ]);
            }catch(\Exception $exception){
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }

    /**
     * Call GazingleCrud get method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function getFrom($serverName, $id){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->get($server['url'].$server['crud_prefix'].'/'.$id, [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ]
                ]);
            }catch(\Exception $exception){
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }

    /**
     * Call GazingleCrud create method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function createFrom($serverName, $params = []){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->post($server['url'].$server['crud_prefix'], [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ],
                    'query' => (!empty($params)) ? $params : [],
                ]);
            }catch(\Exception $exception){
                //Log::alert($exception->getMessage());
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }

    /**
     * Call GazingleCrud update method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function updateFrom($serverName, $id, $params = []){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->put($server['url'].$server['crud_prefix'].'/'.$id, [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ],
                    'query' => (!empty($params)) ? $params : [],
                ]);
            }catch(\Exception $exception){
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }

    /**
     * Call GazingleCrud delete method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function deleteFrom($serverName, $id){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->delete($server['url'].$server['crud_prefix'].'/'.$id, [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ]
                ]);
            }catch(\Exception $exception){
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }

    /**
     * Call GazingleCrud restore method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function restoreFrom($serverName, $id){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->post($server['url'].$server['crud_prefix'].'/'.$id.'/restore', [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ]
                ]);
            }catch(\Exception $exception){
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }

    /**
     * Call GazingleCrud purge method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function purgeFrom($serverName, $id){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->delete($server['url'].$server['crud_prefix'].'/'.$id.'/purge', [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ]
                ]);
            }catch(\Exception $exception){
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }

    /**
     * Call GazingleCrud attach method on other service.
     *
     * @param string $serverName
     * @return mixed
     */
    public function attachTo($serverName, $id, $params = []){
        if($server = $this->_serverExist($serverName)){
            $client = new Client();
            try{
                $response = $client->post($server['url'].$server['crud_prefix'].'/'.$id.'/attach', [
                    'headers' => [
                        'Authorization' => ($this->token) ? 'Bearer '.$this->token : '',
                    ],
                    'query' => (!empty($params)) ? $params : [],
                ]);
            }catch(\Exception $exception){
                //Log::alert($exception->getMessage());
                return $this->_parseGuzzleException($exception, $server);
            }

            $responseJson = json_decode($response->getBody(), true);
            return $responseJson;
        }
        return $this->serverError('Server '.$serverName.' is not listed as available');
    }
}
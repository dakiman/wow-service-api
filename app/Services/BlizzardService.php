<?php
/**
 * Created by PhpStorm.
 * User: dvanchov
 * Date: 10/5/2018
 * Time: 3:33 PM
 */

namespace App\Services;
use \GuzzleHttp\Client;

class BlizzardService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCharacter($name, $realm) {
        return $this->client->get('character/' . $realm . '/' . $name);
    }

}
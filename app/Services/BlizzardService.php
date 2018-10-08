<?php
/**
 * Created by PhpStorm.
 * User: Daki
 * Date: 10/6/2018
 * Time: 7:54 PM
 */

namespace App\Services;

use GuzzleHttp\Client;

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

    public function getRealmsData() {
        return $this->client->get('realm/status');
    }
}
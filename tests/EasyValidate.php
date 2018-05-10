<?php

namespace Tests;

trait EasyValidate
{
    public function validationRequired($type, $route = '/api/register', $data = false)
    {
        $data = $data ? $data : $this->getUser();
        unset($data[$type]);
        $response = $this->json('POST', $route, $data);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' field is required.']);
    }

    public function validationMaxLength($type, $limit, $route = '/api/register', $data = false)
    {
        $data = $data ? $data : $this->getUser();
        $data[$type] = str_random($limit + 1);
        $response = $this->json('POST', $route, $data);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' may not be greater than ' . $limit . ' characters.']);
    }

    public function validationMinLength($type, $limit, $route = '/api/register', $data = false)
    {
        $data = $data ? $data : $this->getUser();
        $data[$type] = str_random($limit - 1);
        $response = $this->json('POST', $route, $data);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' must be at least ' . $limit . ' characters.']);
    }
}

<?php

namespace App\Api;


interface HandleApi
{
    public function __construct($api);

    public function sendRequest();

    public function getAnswer();
}
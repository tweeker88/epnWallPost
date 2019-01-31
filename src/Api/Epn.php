<?php

namespace App\Api;


use App\Epn\clEPNAPIAccess;

class Epn
{
    /**@var $epn clEPNAPIAccess */
    private $epn;

    public function __construct(clEPNAPIAccess $epn)
    {
        $this->epn = $epn;
    }

    public function sendRequest(): bool
    {
        $this->epn->AddRequestSearch('random_goods_1', [
            'query' => '',
            'orderby' => 'orders_count',
            'limit' => 10,
            'offset' => 0,              //@TODO Изменить статику
            'category' => '200000345',
            'lang' => 'ru',
            'currency' => 'RUR'
        ]);
        dump($this->epn->RunRequests());
        dump($this->epn);die;
        return $this->epn->RunRequests();
    }

    public function getAnswer(): array
    {
        return $this->epn->GetRequestResult('random_goods_1');
    }
}
<?php

namespace App\Api;


use App\Epn\clEPNAPIAccess;

class Epn implements HandleApi
{

    private const USER_KEY = '2fa7b3ebac5a069ea822a1f6e9b3cc78';
    private const USER_HASH = 'plstykkckaa63hps96m5phcmuewg7gc1';

    /**
     * @var $epn clEPNAPIAccess
     */
    private $epn;

    public function __construct($epn)
    {
        $this->epn = $epn;
    }

    public function sendRequest(): bool
    {
        $this->epn->AddRequestCategoriesList('categories_list_1', 'ru');
        $this->epn->AddRequestSearch('random_goods_1', [
            'query' => '',
            'orderby' => 'orders_count',
            'limit' => 10,
            'offset' => 0,
            'category' => '1511',
            'lang' => 'ru'
        ]);

        return $this->epn->RunRequests();
    }

    public function getAnswer(): ?array
    {
        return $this->epn->GetRequestResult('random_goods_1');
    }
}
<?php

namespace App\Api;


use App\Epn\clEPNAPIAccess;

class Epn
{
    /**@var $epn clEPNAPIAccess */
    private $epn;

    /**
     * @param clEPNAPIAccess $epn
     */
    public function __construct(clEPNAPIAccess $epn)
    {
        $this->epn = $epn;
    }

    /**
     * @return array
     */
    public function sendRequestCategory(): array
    {
        $this->epn->AddRequestCategoriesList('categories_list_1', 'ru');

        $this->epn->RunRequests();

        return $this->epn->GetRequestResult('categories_list_1');
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param string $category
     * @return bool
     */
    public function sendRequestSearch(string $category): array
    {
        $this->epn->AddRequestSearch('random_goods_1', [
            'query' => '',
            'orderby' => 'orders_count',
            'limit' => 500,
            'offset' => 0,
            'category' => $category,
            'lang' => 'ru',
            'currency' => 'RUR'
        ]);


        $this->epn->RunRequests();

        return $this->epn->GetRequestResult('random_goods_1');
    }

    /**
     * @return array
     */
    public function getAnswer(): array
    {
        return $this->epn->GetRequestResult('random_goods_1');
    }

    /**
     * @return string
     */
    public function getErrorsList(): string
    {
        return $this->epn->LastError();
    }
}
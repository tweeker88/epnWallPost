<?php

namespace App\Api;


use App\Epn\clEPNAPIAccess;

class Epn
{
    /**@var $epn clEPNAPIAccess */
    private $epn;

    private $step = 0;


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
    public function sendRequestSearch(int $limit, int $offset, string $category): array
    {
        $this->step += $offset;

        $this->epn->AddRequestSearch('random_goods_1', [
            'query' => '',
            'orderby' => 'orders_count',
            'limit' => $limit,
            'offset' => $this->step,
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
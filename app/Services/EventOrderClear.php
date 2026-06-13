<?php
namespace App\Services;

use App\Models\AppEvent\AppEventOrder;

class EventOrderClear
{
    public $orders;

    public function __call($data,$values)
    {
        return $this->reserveTempClear();
    }

    public function reserveTempClear()
    {
        $this->orders = AppEventOrder::all();

        return $this->orders->count();
    }
}

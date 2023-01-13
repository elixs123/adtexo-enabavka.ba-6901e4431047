<?php

namespace App\FiscalModels;


class FiscalRequest
{
    public $ReceiptId;
    public $TaxRate;
    public $NetTotal;
    public $GrossTotal;
    public $DeliveryCost;
    public $ReceiptNo;
    public $Client;
    public $ReceiptItems;
    public $PaymentTypes;
}

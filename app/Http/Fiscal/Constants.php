<?php

namespace App\Http\Fiscal;

class Constants
{
    const API_URL = 'http://localhost:51739/';
//    const API_URL = 'http://localhost:5000/';
    const RECEIPT_METHOD = 'fiscal';
    const ACCESS_TOKEN = "ab7dd0ca-8090-49d4-b933-140328106e7a";
    const PRINTER_TYPE = "FP200"; //TODO: place in user config
    const BUSINESS_ITEM_NAME = "Stavke po fakturi br. ";
    const BUSINESS_ITEM_UNIT_ID = "kom";
    const BUSINESS_ITEM_FAKE_QTY = 1;
    const BUSINESS_ITEM_FAKE_PRODUCT_ID = 1;
    const DELIVERY_ITEM_NAME = "Isporuka";
    const DELIVERY_ITEM_FAKE_ID = 9876;
    const DELIVERY_ITEM_QUANTITY = 1;
    const DELIVERY_ITEM_DISCOUNT = 0;
    const RECEIPT_AMOUNT_ZERO = 0;
    const PAYMENT_TYPE_WIRE_TRANFER = "Virman";
    const PAYMENT_TYPE_CASH = "Gotovina";
    const FISCAL_SUCCESS_STATUS = 0;
    const FISCAL_ERROR_STATUS = 1;
    const FISCAL_WARNING_STATUS = 2;
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s+';

}

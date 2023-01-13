<?php

namespace App;

use App\FiscalModels\FiscalArticle;
use App\FiscalModels\FiscalClient;
use App\FiscalModels\FiscalPaymentType;
use App\FiscalModels\FiscalReceiptItem;
use App\FiscalModels\FiscalRequest;
use App\Http\Fiscal\Constants;

class InvoiceHelper
{
    private $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function getFiscalRequest($id): FiscalRequest
    {
        $fiscalRequest = new FiscalRequest();
        $invoice = $this->document->getOne($id);
        $client = $invoice->rClient;

        $fiscalRequest->Total = $invoice->total;
        $fiscalRequest->ReceiptId = $invoice->id;


        if ($client->type_id == 'business_client') {
            $fiscalRequest->Client = new FiscalClient();
            $fiscalRequest->Client->IdNo = $client->jib;
            $fiscalRequest->Client->TaxNo = $client->pib;
            $fiscalRequest->Client->Name = $client->name;
            $fiscalRequest->Client->Address = $client->address;
            $fiscalRequest->Client->PostalCode = $client->postal_code;
            $fiscalRequest->Client->City = $client->city;
        }

        $fiscalRequest->ReceiptNo = $invoice->fiscal_receipt_no;

        if ($invoice->payment_type == 'cash_payment') {
            $fiscalRequest->ReceiptItems = $this->getDocumentItems($invoice);
        } else {
            $fiscalRequest->ReceiptItems = $this->getTotal($invoice);
        }

        if($invoice->delivery_cost > 0){
            $deliveryItem = new FiscalReceiptItem();
            $article = new FiscalArticle();

            $article->ProductId = Constants::DELIVERY_ITEM_FAKE_ID;
            //$article->NetPrice = $invoice->delivery_cost; //TODO - without VAT, check if needed
            $article->DiscountedPrice = $invoice->delivery_cost;
            $article->GrossPrice = $invoice->delivery_cost;
            $article->TaxRate = $invoice->tax_rate;
            $article->Name = Constants::DELIVERY_ITEM_NAME;
            $article->UnitId = Constants::BUSINESS_ITEM_UNIT_ID;

            $deliveryItem->Article = $article;
            $deliveryItem->Qty = Constants::DELIVERY_ITEM_QUANTITY;
            $deliveryItem->Discount = Constants::DELIVERY_ITEM_DISCOUNT;

            array_push($fiscalRequest->ReceiptItems, $deliveryItem);
        }

        $paymentType = new FiscalPaymentType();

        $paymentType->TypeName = $invoice->payment_type == "wire_transfer_payment"
            ? Constants::PAYMENT_TYPE_WIRE_TRANFER
            : Constants::PAYMENT_TYPE_CASH;

        $paymentType->Amount = Constants::RECEIPT_AMOUNT_ZERO;

        $fiscalRequest->PaymentTypes = array($paymentType);
        $fiscalRequest->GrossTotal = $invoice->fiscal_discounted_price + $invoice->fiscal_delivery_price;
        $fiscalRequest->TaxRate = $invoice->tax_rate;

        return $fiscalRequest;
    }

    private function getDocumentItems($invoice): array
    {
        $items = $invoice->rDocumentProduct()->with(['rDocument', 'rUnit'])->get();

        $receiptItems = [];
        $counter = 0;

        foreach ($items as $item) {
            $counter++;
            $receiptItem = new FiscalReceiptItem();
            $artikal = new FiscalArticle();

            $artikal->ProductId = $item->product_id;
            $artikal->Name = $item->name;
            $artikal->UnitId = $item->unit_id;
            $artikal->NetPrice = $item->fiscal_net_price;
            $artikal->TaxRate = $invoice->tax_rate;
            $artikal->GrossPrice = $item->fiscal_gross_price;
            $artikal->DiscountedPrice = $item->mpc_discounted;

            $receiptItem->Article = $artikal;
            $receiptItem->Discount = $item->fiscal_discount_percent;
            $receiptItem->Qty = $item->qty;

            array_push($receiptItems, $receiptItem);
        }

        return $receiptItems;
    }

    private function getTotal($invoice): array
    {
        $receiptItems = [];
        $receiptItem = new FiscalReceiptItem();
        $artikal = new FiscalArticle();

        $artikal->ProductId = Constants::BUSINESS_ITEM_FAKE_PRODUCT_ID;
        $artikal->Name = Constants::BUSINESS_ITEM_NAME . $invoice->id;
        $artikal->UnitId = Constants::BUSINESS_ITEM_NAME;
        $artikal->NetPrice = $invoice->fiscal_net_price;
        $artikal->GrossPrice = $invoice->fiscal_gross_price;
        $artikal->DiscountedPrice = $invoice->fiscal_discounted_price;
        $artikal->TaxRate = $invoice->tax_rate;

        $receiptItem->Article = $artikal;
        $receiptItem->Discount = $invoice->fiscal_discount_percent;
        $receiptItem->Qty = Constants::BUSINESS_ITEM_FAKE_QTY;

        array_push($receiptItems, $receiptItem);

        return $receiptItems;
    }
}

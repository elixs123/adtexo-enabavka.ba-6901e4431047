<?php

namespace App\Libraries;

use App\Order;

/**
 * Class PantheonHelper
 *
 * @package App\Libraries
 */
class PantheonHelper
{
    /**
     * @param \App\Document|mixed $document
     * @return array
     */
    public static function mapDocumentForRequest($document)
    {
        $mapped = [
            'brojnarudzbe' => ''.$document->id,
            'kupac' => [
                'koddrzave' => $document->buyer_data['country_id'],
                'tipkupca' => $document->useMpcPrice() ? 'private_client' : $document->buyer_data['type_id'],
                'kupacid' => ''.(is_null($document->buyer_data['parent_id']) ? $document->buyer_data['id'] : $document->buyer_data['parent_id']),
            ],
            'primalac' => [
                'primalacid' => ''.$document->buyer_data['id'],
            ],
            'artikli' => self::mapDocumentProductsForRequest($document->rDocumentProduct, $document),
            'komercijalista' => '1', // @ToDo: Change
        ];
        
        return $mapped;
    }
    
    /**
     * @param \Illuminate\Support\Collection|mixed $documentProducts
     * @param \App\Document|mixed $document
     * @return array
     */
    public static function mapDocumentProductsForRequest($documentProducts, $document)
    {
        $mapped = []; $num = 0;
        foreach ($documentProducts as $product) {
            $mapped[] = [
                'poz' => $num + 1,
                'artikalid' => $product->code,
                'kol' => self::numberFormat($product->qty),
                'diskont1' => self::numberFormat($product->discount1),
                'diskont2' => self::numberFormat($product->discount2),
                'diskont3' => self::numberFormat($product->discount3),
            ];
    
            $num++;
        }
        
        if ($document->delivery_cost > 0) {
            $delivery_code = null;
            
            if ($document->buyer_data['country_id'] == 'bih') {
                if ($document->delivery_cost == config('app.delivery_cost.bih.full')) {
                    $delivery_code = 'PRE';
                } else if ($document->delivery_cost == config('app.delivery_cost.bih.half')) {
                    $delivery_code = 'PRE1';
                }
            } else if ($document->buyer_data['country_id'] == 'srb') {
                if ($document->delivery_cost == config('app.delivery_cost.srb.full')) {
                    $delivery_code = 'PRE';
                } else if ($document->delivery_cost == config('app.delivery_cost.srb.half')) {
                    $delivery_code = 'PRE1';
                }
            }
            
            if (!is_null($delivery_code)) {
                $mapped[] = [
                    'poz' => $num + 1,
                    'artikalid' => $delivery_code,
                    'kol' => self::numberFormat(1),
                    'diskont1' => self::numberFormat(0),
                    'diskont2' => self::numberFormat(0),
                    'diskont3' => self::numberFormat(0),
                ];
            }
        }
        
        return $mapped;
    }
    
    /**
     * @param array|mixed $response
     * @param int $documentId
     * @return \App\Order
     */
    public static function createOrderFromResponse($response, $documentId)
    {
        $attributes = [
            'document_id' => $documentId,
            'name' => data_get($response, 'podaci.HeadInvoice.0.BrojFakture'),
            'client_id' => data_get($response, 'podaci.HeadInvoice.0.KupacId'),
            'client_data' => collect(data_get($response, 'podaci.HeadInvoice.0', []))->only(['KupacNaziv', 'KupacJIB', 'KupacPIB'])->toArray(),
            'location_id' => data_get($response, 'podaci.HeadInvoice.0.PrimalacId'),
            'location_data' => collect(data_get($response, 'podaci.HeadInvoice.0', []))->only(['PrimalacNaziv', 'PrimalacJIB', 'PrimalacPIB'])->toArray(),
            'stock_name' => data_get($response, 'podaci.HeadInvoice.0.IzdajnoSkladiste'),
            'subtotal' => self::numberFormat((float) data_get($response, 'podaci.HeadInvoice.0.Osnova', 0)),
            'discount' => self::numberFormat((float) data_get($response, 'podaci.HeadInvoice.0.Diskont', 0)),
            'tax' => self::numberFormat((float) data_get($response, 'podaci.HeadInvoice.0.IznosPdv', 0)),
            'total' => self::numberFormat((float) data_get($response, 'podaci.HeadInvoice.0.UkupanIznos', 0)),
            'status' => data_get($response, 'status'),
        ];
        
        $order = new Order($attributes);
        $order->save();
        
        $records = [];
        foreach (data_get($response, 'podaci.BodyInvoice', []) as $key => $data) {
            $records[] = [
                'position' => (int) data_get($data, 'Poz', $key + 1),
                'product_code' => data_get($data, 'ArtikalID'),
                'name' => data_get($data, 'Naziv'),
                'quantity' => self::numberFormat((float) data_get($data, 'Kol', 1)),
                'price' => self::numberFormat((float) data_get($data, 'Cijena', 0)),
                'discount_1' => self::numberFormat((float) data_get($data, 'Rabat1', 0)),
                'discount_2' => self::numberFormat((float) data_get($data, 'Rabat2', 0)),
                'discount_3' => self::numberFormat((float) data_get($data, 'Rabat3', 0)),
                'discount_total' => self::numberFormat((float) data_get($data, 'UkupnoRabat', 0)),
                'total' => self::numberFormat((float) data_get($data, 'MPCijena', 0)),
            ];
        }
        
        if (isset($records[0])) {
            $order->rItems()->createMany($records);
        }
        
        return $order;
    }
    
    /**
     * @param \App\Document|mixed $document
     * @param int $orderId
     * @return \App\Document
     */
    public static function syncDocumentWithOrder($document, $orderId)
    {
        $document->order_id = $orderId;
        $document->save();
        
        return $document;
    }
    
    /**
     * @param integer|float $number
     * @return string
     */
    public static function numberFormat($number)
    {
        return number_format(round($number, 2), 2, '.', '');
    }
}

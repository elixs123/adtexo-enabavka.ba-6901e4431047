<?php

namespace App\Support\Scoped;

use Illuminate\Support\Facades\Facade;

/**
 * Class ScopedDocumentFacade
 *
 * @method static boolean exist()
 * @method static \App\Document getDocument()
 * @method static integer id()
 * @method static string currency()
 * @method static string typeId()
 * @method static string type()
 * @method static integer totalItems()
 * @method static float totalValue()
 * @method static float totalValueDiscounted()
 * @method static integer clientId()
 * @method static \App\Support\Model\Client client()
 * @method static integer stockId()
 * @method static float discount1()
 * @method static float discount2()
 * @method static float discount3()
 * @method static \Illuminate\Database\Eloquent\Collection products()
 * @method static boolean open(int $id)
 * @method static boolean close()
 * @method static boolean hasProduct(int $productId)
 * @method static null|\App\DocumentProduct getProduct(int $productId)
 * @method static void removeFromStock()
 * @method static boolean isOrder()
 * @method static boolean isPreOrder()
 * @method static boolean isOffer()
 * @method static boolean isReturn()
 * @method static boolean isReversal()
 * @method static boolean isCash()
 * @method static string backgroundColor()
 * @method static string color()
 * @method static string paymentType()
 * @method static boolean isCashPayment()
 * @method static string deliveryType()
 * @method static float taxRateValue()
 * @method static boolean showMpcPrice()
 * @method static boolean useMpcPrice()
 * @method static boolean withTax()
 * @method static \Illuminate\Database\Eloquent\Collection scopedCategories()
 * @method static boolean withScopedCategories()
 * @method static \Illuminate\Database\Eloquent\Collection scopedProducts()
 * @method static boolean withScopedProducts()
 * @method static boolean isAction()
 * @method static \App\Action|null action()
 * @method static null|\App\ActionProduct getActionProduct(int $productId)
 * @method static integer getProductMinQty(int $productId)
 *
 * @package App\Support\Scoped
 */
class ScopedDocumentFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'ScopedDocument';
    }
}

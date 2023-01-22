<?php

namespace App\Support\Scoped;

use App\Document;
use App\User;
use App\The_Order;

/**
 * Class ScopedDocument
 *
 * @package App\Support\Scoped
 */
class ScopedDocument
{
    /**
     * @var \App\User
     */
    private $user;
    
    /**
     * @var \App\Client
     */
    private $client;
    
    /**
     * @var \App\Document
     */
    private $document;
    
    /**
     * @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
     */
    protected $products;
    
    /**
     * @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
     */
    protected $scopedCategories;
    
    /**
     * @var bool
     */
    protected $withScopedCategories = false;
    
    /**
     * @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
     */
    protected $scopedProducts;
    
    /**
     * @var bool
     */
    protected $withScopedProducts = false;
    
    /**
     * @var \App\Action
     */
    private $action;
    
    /**
     * @var \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection
     */
    private $actionProducts;
    
    /**
     * ScopedDocument constructor.
     *
     * @param \App\User|\Illuminate\Contracts\Auth\Authenticatable $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $this->checkForScopedDocument();
    }
    
    public function orders(){
       /* $orders = The_Order::with('subject')->where('acStatus', 'N')->get();

        return $orders;*/
    }

    /**
     * @return void
     */
    private function checkForScopedDocument()
    {
        if (is_null($this->user)) {
            return;
        }
        
        $this->document = $this->user->rScopedDocument()->latest()->first();

        $this->products = $this->products();
        
        $this->client = $this->user->client;
        
        $this->action = is_null($this->document) ? null : $this->document->rAction;
        
        $this->actionProducts = is_null($this->action) ? null : $this->document->rAction->rActionProducts;
    
        if ($this->user->isFocuser()) {
            $this->scopeCategoriesAndProducts($this->user->rPerson);
        } else {
            if ($this->user->isClient() || $this->user->isSalesAgent()) {
                $this->client = $this->user->client;
            } else {
                if ($this->exist()) {
                    $this->client = $this->document->rClient;
                }
            }
            
            $this->scopeCategoriesAndProducts($this->client);
        }
    }
    
    /**
     * @param \App\Client|\App\Person $model
     */
    private function scopeCategoriesAndProducts($model)
    {
        $this->scopedCategories = collect([]);
        $this->scopedProducts = collect([]);
        
        if (!is_null($model) && ($model->id > 0)) {
            $this->scopedCategories = $model->rCategories()->active()->get();
            if ($this->scopedCategories->count() > 0) {
                $this->withScopedCategories = true;
            }
        
            $this->scopedProducts = $model->rProducts()->active()->get();
            if ($this->scopedProducts->count() > 0) {
                $this->withScopedCategories = false;
                
                $this->withScopedProducts = true;
            }
        }
    }
    
    /**
     * @return bool
     */
    public function exist()
    {
        return !is_null($this->document);
    }
    
    /**
     * @return \App\Document
     */
    public function getDocument()
    {
        return $this->document;
    }
    
    /**
     * @return int
     */
    public function id()
    {
        if ($this->exist()) {
            return $this->document->id;
        }
        
        return null;
    }
	
    /**
     * @return int
     */
    public function currency()
    {
        if ($this->exist()) {
            return $this->document->currency;
        }
        
        return null;
    }
    
    /**
     * @return string
     */
    public function typeId()
    {
        if ($this->exist()) {
            return $this->document->type_id;
        }
        
        return '';
    }
    
    /**
     * @return string
     */
    public function type()
    {
        if ($this->exist()) {
            return $this->document->rType->name;
        }
        
        return '';
    }

    /**
     * @return int
     */
    public function totalItems()
    {
        if ($this->exist()) {
            return $this->document->rDocumentProduct->count();
        }
        
        return 0;
    }

    /**
     * @return float
     */
    public function totalValue()
    {
        if ($this->exist()) {
            return $this->document->total_value;
        }
        
        return 0;
    }

    /**
     * @return float
     */
    public function totalDiscountedValue()
    {
        if ($this->exist()) {
            // if ($this->isAction()) {
            //     if ($this->action->isDiscount()) {
            //         return $this->products->sum('total_discounted_value');
            //     }
            // }
            
            return $this->document->total_discounted_value;
        }
        
        return 0;
    }

    /**
     * @return int
     */
    public function clientId()
    {
        if ($this->exist()) {
            return $this->document->client_id;
        }
        
        if (!is_null($this->client)) {
            return $this->client->id;
        }
        
        return null;
    }
    
    /**
     * @return \App\Support\Model\Client
     */
    public function client()
    {
        if ($this->exist()) {
            return $this->document->rClient;
        }
        
        return null;
    }

    /**
     * @return int
     */
    public function stockId()
    {
        if ($this->exist()) {
            return $this->document->stock_id;
        }
        
        return null;
    }
    
    /**
     * @return float
     */
    public function discount1()
    {
        if ($this->exist()) {
            return $this->document->discount1;
        }
        
        return 0;
    }
    
    /**
     * @return float
     */
    public function discount2()
    {
        if ($this->exist()) {
            return $this->document->discount2;
        }
        
        return 0;
    }
    
    /**
     * @return float
     */
    public function discount3()
    {
        if ($this->exist()) {
            return $this->document->discount3;
        }
        
        return 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function products()
    {
        if ($this->exist()) {
            return $this->document->rDocumentProduct;
        }
        
        return collect([]);
    }
    
    /**
     * @return bool
     */
    public function close()
    {
        if ($this->exist()) {
            $this->user->rScopedDocument()->detach();
            
            $this->document = null;
            
            return true;
        }
        
        return false;
    }
    
    /**
     * @param $id
     * @return bool
     */
    public function open($id)
    {
        if (!$this->exist()) {
            $document = new Document();
            $document->statusId = 'draft';
            if (userIsSalesman()) {
                $document->createdBy = $this->user->id;
            }
         
            $this->document = $document->getOne($id);
            
            if (!$this->exist()) {
                abort(404, trans('document.errors.not_found', ['id' => $id]));
            }
    
            $this->user->rScopedDocument()->detach();
            $this->user->rScopedDocument()->attach($id);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * @param int $productId
     * @return bool
     */
    public function hasProduct($productId)
    {
        if ($this->exist()) {
            return $this->products->filter(function($item) use ($productId) {
                return $item->product_id == $productId;
            })->first();
        }
        
        return false;
    }
    
    /**
     * @param int $productId
     * @return null|\App\DocumentProduct
     */
    public function getProduct($productId)
    {
        if ($this->exist()) {
            return $this->products->filter(function($item) use ($productId) {
                return $item->product_id == $productId;
            })->first();
        }
        
        return null;
    }
    
    /**
     * @return void
     */
    public function removeFromStock()
    {
        foreach($this->products() as $product)
        {
            $productStock = [
                'product_id' => $product->product_id,
                'stock_id' => $this->stockId(),
                'qty' => -abs($product->qty),
                'note' => '#' . $this->id()
            ];

            (new \App\ProductStock())->add($productStock);
        }
    }
    
    /**
     * @return bool
     */
    public function isOrder()
    {
        if ($this->exist() && $this->getDocument()->isOrder()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isPreOrder()
    {
        if ($this->exist() && $this->getDocument()->isPreOrder()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isOffer()
    {
        if ($this->exist() && $this->getDocument()->isOffer()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isReturn()
    {
        if ($this->exist() && $this->getDocument()->isReturn()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isReversal()
    {
        if ($this->exist() && $this->getDocument()->isReversal()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return bool
     */
    public function isCash()
    {
        if ($this->exist() && $this->getDocument()->isCash()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * @return string
     */
    public function backgroundColor()
    {
        if ($this->exist()) {
            return $this->getDocument()->rType->background_color;
        }
    
        return trans('codebook.vars.colors.background_color');
    }
    
    /**
     * @return string
     */
    public function textColor()
    {
        if ($this->exist()) {
            return $this->getDocument()->rType->color;
        }
    
        return trans('codebook.vars.colors.color');
    }
    
    /**
     * @return string
     */
    public function color()
    {
        if ($this->exist()) {
            return $this->getDocument()->rType->background_color;
        }
    
        return trans('codebook.vars.colors.background_color');
    }
    
    /**
     * @return mixed|string
     */
    public function paymentType()
    {
        if ($this->exist()) {
            return $this->getDocument()->payment_type;
        }
        
        return 'wire_transfer_payment';
    }
    
    /**
     * @return bool
     */
    public function isCashPayment()
    {
        if ($this->exist()) {
            return $this->getDocument()->isCashPayment();
        }
    
        return false;
    }
    
    /**
     * @return string
     */
    public function deliveryType()
    {
        if ($this->exist()) {
            return $this->getDocument()->delivery_type;
        }
        
        return 'free_delivery';
    }
    
    /**
     * @return float|int
     */
    public function taxRateValue()
    {
        if ($this->exist()) {
            return 1 + ($this->getDocument()->tax_rate / 100);
        }
    
        if (!is_null($this->client)) {
            return 1 + ($this->client->rStock->tax_rate / 100);
        }
    
        return 1;
    }
    
    /**
     * @return bool
     * @deprecated
     */
    public function showMpcPrice()
    {
        return $this->useMpcPrice();
    }
    
    /**
     * @return bool
     */
    public function useMpcPrice()
    {
        if ($this->exist()) {
            return $this->document->useMpcPrice();
        }
    
        return false;
    }
    
    /**
     * @return bool
     * @deprecated
     */
    public function withTax()
    {
        if ($this->isCashPayment() || ($this->typeId() == 'cash')) {
            return true;
        }
    
        return false;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function scopedCategories()
    {
        return $this->scopedCategories;
    }
    
    /**
     * @return bool
     */
    public function withScopedCategories()
    {
        return $this->withScopedCategories;
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function scopedProducts()
    {
        return $this->scopedProducts;
    }
    
    /**
     * @return bool
     */
    public function withScopedProducts()
    {
        return $this->withScopedProducts;
    }
    
    /**
     * @return bool
     */
    public function isAction()
    {
        if ($this->exist()) {
            return !is_null($this->action);
        }
        
        return false;
    }
    
    /**
     * @return \App\Action|null
     */
    public function action()
    {
        return $this->action;
    }
    
    /**
     * @param int $productId
     * @return null|\App\ActionProduct
     */
    public function getActionProduct($productId)
    {
        if ($this->isAction()) {
            return $this->actionProducts->filter(function($item) use ($productId) {
                return $item->product_id == $productId;
            })->first();
        }
        
        return null;
    }
    
    /**
     * @param int $productId
     * @return int
     */
    public function getProductMinQty($productId)
    {
        if ($this->isAction()) {
            $product = $this->getActionProduct($productId);
            
            if (!is_null($product)) {
                return $product->qty;
            }
        }
        
        return 0;
    }
}

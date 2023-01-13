<style>
    .img-fluid {
        width: 100%;
    }
    
    @media (max-width: 1920px){
        .ecommerce-application .list-view .ecommerce-card .card-content .card-body {
            display: flex;
            justify-content: space-between;
            flex-direction: column;
            padding-right: 0;
        }
        .ecommerce-application .list-view .ecommerce-card .card-content {
            grid-template-columns: 3fr 1fr;
        }
        
        .ecommerce-application .list-view .ecommerce-card .card-content .item-options .item-wrapper .item-rating span{
            font-size: 10px;
        }
        
        .ecommerce-application .list-view .ecommerce-card .card-content .item-options .item-wrapper .item-cost .item-price {
            position: relative;
            top: 0px;
            font-size: 16px;
            text-align: right;
        }
        
        .item-price.old,
        .ecommerce-application .item-price.old {
            text-decoration: line-through;
            color: #bbb !important
        }
        
        .ecommerce-application .list-view .ecommerce-card .card-content .item-options .wishlist {
            margin-top: 40px;
            margin-bottom: 0;
            text-align: center;
        }
        .ecommerce-application .list-view .ecommerce-card .card-content .item-img{
            display: none;
        }
        .ecommerce-application .list-view .ecommerce-card .card-content .item-img img{
            max-height: 100px;
        }
        
        .ecommerce-application .grid-view .ecommerce-card .card-content .item-wrapper .item-rating span{
            font-size: 10px;
        }
        .ecommerce-application .grid-view .ecommerce-card .card-content .item-wrapper {
            margin-bottom: 10px;
        }
    }
    
    @media (min-width: 1024px){
        .ecommerce-application .list-view .ecommerce-card .card-content {
            grid-template-columns: 100px 1fr 200px;
        }
        .ecommerce-application .list-view .ecommerce-card .card-content .item-img{
            display: flex;
        }
        
        .ecommerce-application .list-view .ecommerce-card .card-content .barcode{
            display: block;
        }
    }
    
    .ecommerce-application .grid-view .ecommerce-card .card-content .barcode{
        display: block;
    }
    
    .ecommerce-application .grid-view .ecommerce-card .card-content .qty{
        margin-top: 0px;
    }
    
    .ecommerce-application .ecommerce-card .qty {
        background-color: #f6f6f6;
        color: #2c2c2c;
        border-radius: 6px;
        margin-top: 40px;
        margin-bottom: 0;
        text-align: center;
        padding: 5px;
        display: flex;
        justify-content: center;
    }
    
    .ecommerce-application .stock-in {
        font-size: 12px;
        color: #28c76f;
        display: block;
        margin-bottom: 5px;
    }
    
    .ecommerce-application .stock-out {
        font-size: 12px;
        color: #ea5455;
        display: block;
        margin-bottom: 5px;
    }
    
    .ecommerce-application .barcode {
        font-size: 12px;
        display: none;
    }
    
    .ecommerce-application .code {
        font-size: 12px;
        display: block;
    }
    
    .ecommerce-application .grid-view .ecommerce-card .card-content .item-options {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }
    #ecommerce-products .qty input{
        border: 0;
        padding: 5px;
        width: 50px;
        text-align: center;
    }
    
    #ecommerce-products .item-name {
        margin-bottom: auto;
    }
    
    #ecommerce-searchbar .serach-box button {border: 0; background: none}
    
    .level-2{
        margin-left: 30px
    }
</style>

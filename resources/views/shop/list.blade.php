@extends('layouts.app')

@section('head_title', $title = trans('product.title'))

@section('content')
    <!-- BEGIN: Content-->
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Shop</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="/shop">Proizvodi</a>
                                    </li>

                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- -->
            <div class="">
                <div class="content-body">
                    <!-- Ecommerce Content Section Starts -->
                    <section id="ecommerce-header">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="ecommerce-header-items">
                                    <div class="result-toggler">
                                        <button class="navbar-toggler shop-sidebar-toggler" type="button" data-toggle="collapse">
                                            <span class="navbar-toggler-icon d-block d-lg-none"><i class="feather icon-menu"></i></span>
                                        </button>
                                        <div class="search-results">
										
                                        </div>
                                    </div>
                                    <div class="view-options">
                                        <div class="btn-group">
                                           
                                        </div>
                                        <div class="view-btn-option">
                                            <button class="btn btn-white list-view-btn view-btn active">
                                                <i class="feather icon-list"></i>
                                            </button>
                                            <button class="btn btn-white view-btn grid-view-btn">
                                                <i class="feather icon-grid"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Ecommerce Content Section Starts -->
                    <!-- background Overlay when sidebar is shown  starts-->
                    <div class="shop-content-overlay"></div>
                    <!-- background Overlay when sidebar is shown  ends-->

                    <!-- Ecommerce Search Bar Starts -->
                    <section id="ecommerce-searchbar">
                        <div class="row mt-1">
                            <div class="col-sm-12">
								
                                <fieldset class="form-group position-relative">
                                    <input type="text" id="autocomplete" autocomplete="off" class="form-control search-acIdent" name="keywords"  placeholder="Pretražuj proizvode po nazivu, šifri, brandu ili opisu">
                                    <button type="submit" class="form-control-position">
                                        <i class="feather icon-search"></i>
                                    </button>
                                </fieldset>
								</form>
                            </div>
                        </div>
                    </section>
                    <!-- Ecommerce Search Bar Ends -->

                    <!-- Ecommerce Products Starts -->
                    <div class="pjax-container">
                        @include('shop.list_fragment')
                    </div>

                </div>
            </div>
            
        </div>
@endsection

@section('css-vendor')
    <link href="{{ asset('assets/app-assets/css/pages/app-ecommerce-shop.css').assetVersion() }}" rel="stylesheet" type="text/css">
@endsection

@section('css')
    @parent
    @include('shop._style')
@endsection

@section('script-vendor')
<script src="{{ asset('assets/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js').assetVersion() }}" type="text/javascript"></script>
<script src="{{ asset('assets/app-assets/vendors/js/extensions/wNumb.js').assetVersion() }}" type="text/javascript"></script>
<script src="{{ asset('assets/app-assets/js/scripts/pages/app-ecommerce-shop.js').assetVersion() }}" type="text/javascript"></script>
@endsection

@section('script')
<script>
@if(can('create-document'))
function shopDocumentCreated(response) {
    $('#form-modal1').modal('hide');
    documentReload();
}
@endif
</script>

<script>
    $(".search-acIdent").keyup(function(){
        
     $.ajax({
        type: "GET",
        url: location.origin+"/api/ba/projects/search",
        data:{
            search: $(this).val(),
        },
        success: function(res){
            console.log(res)
            
        },
        error:function(error)
        {
        }
    });
})
</script>
@endsection

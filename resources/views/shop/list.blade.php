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
            <div class="content-detached content-right">
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
										{{ 1 }} proizvoda
                                        </div>
                                    </div>
                                    <div class="view-options">
                                        <div class="btn-group">
                                            <div class="dropdown">
                                                <button class="btn btn-primary dropdown-toggle mr-1" type="button" id="dropdownSort" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Sortiranje
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownSort">
                                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_type' => 'rang', 'sort_mode' => 'asc']) }}">Prioritet</a>
                                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_type' => 'name', 'sort_mode' => 'asc']) }}">A-Z</a>
                                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_type' => 'name', 'sort_mode' => 'desc']) }}">Z-A</a>
                                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_type' => 'never_ordered', 'sort_mode' => 'asc']) }}">Nikad naručivano</a>
                                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_type' => 'most_ordered', 'sort_mode' => 'desc']) }}">Najviše naručivano</a>
                                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_type' => 'qty', 'sort_mode' => 'desc']) }}">Najviše na stanju</a>
                                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort_type' => 'new', 'sort_mode' => 'desc']) }}">Posljednje dodano</a>
													                        
                                                </div>
                                            </div>
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
								<form class="serach-box" action="{{ request()->url() }}" method="get">
                                <fieldset class="form-group position-relative">
                                    <input type="text" id="autocomplete" autocomplete="off" class="form-control search-product" name="keywords" value="{{ request('keywords') }}" placeholder="Pretražuj proizvode po nazivu, šifri, brandu ili opisu">
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
            <div class="sidebar-detached sidebar-left">
                <div class="sidebar">
                    <!-- Ecommerce Sidebar Starts -->
                    <div class="sidebar-shop" id="ecommerce-sidebar-toggler">
                        <div class="row">
                            <div class="col-sm-12">
                                <h6 class="filter-heading d-none d-lg-block">Filteri</h6>
                            </div>
                        </div>
                        <span class="sidebar-close-icon d-block d-md-none">
                            <i class="feather icon-x"></i>
                        </span>
                        <div class="card">
                            <div class="card-body">
                                @if(scopedAction()->hasActions())
                                <div>
                                    <div class="product-category-title">
                                        <h6 class="filter-title mb-1">Akcije</h6>
                                    </div>
                                    <ul class="list-unstyled categories-list">
                                        @foreach(scopedAction()->getActions() as $action)
                                        <li class="d-flex justify-content-between align-items-center py-25">
                                            <a href="{{ route('action.show', ['id' => $action->id])}}" title="Pregledaj akciju" data-toggle="tooltip">{{ $action->name }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
								<!-- Fixed links Starts -->
                                <div id="fixed-links">
                                    <div class="product-category-title">
                                        <h6 class="filter-title mb-1">Opcije</h6>
                                    </div>
                                    <ul class="list-unstyled categories-list">
                                        @if(ScopedContract::hasContract())
                                        <li class="d-flex justify-content-between align-items-center py-25">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_type' => 'contract', 'sort_mode' => 'asc']) }}">Ugovorena prodaja</a>
                                        </li>
                                        @endif
                                        <li class="d-flex justify-content-between align-items-center py-25">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_type' => 'never_ordered', 'sort_mode' => 'asc']) }}">Nikad naručivano</a>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center py-25">
                                            <a href="{{ request()->fullUrlWithQuery(['sort_type' => 'most_ordered', 'sort_mode' => 'desc']) }}">Najviše naručivano</a>
                                        </li>
                                        @if(!userIsClient())
                                        <li class="d-flex justify-content-between align-items-center py-25">
                                            <a href="{{ route('product.index') }}?export=pdf">{{ trans('skeleton.actions.export2pdf') }}</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                                <!-- Categories Ends -->
                                <hr>
                                @if(count($categories) > 0)
                                <!-- Categories Starts -->
                                <?php $selected_categories = explode('.', request()->get('f_category_id', null)); ?>
                                <div class="categories-filter" id="product-categories">
                                    <div class="product-category-title">
                                        <h6 class="filter-title mb-1">Kategorije</h6>
                                    </div>
                                    <ul class="list-unstyled categories-list">
                                        @foreach($categories as $id => $category)
                                        @if($id > 0)
                                        <li class="d-flex justify-content-between align-items-center py-25">
                                            <span class="vs-checkbox-con vs-checkbox-primary level-{{ $category->length }}">
                                                <input id="filter-category-{{ $category->id }}" class="parent-{{ $category->father_id }}" @if(in_array($category->id, $selected_categories)) checked="checked" @endif type="checkbox" value="{{ $category->id }}">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                            <span class="">{{ $category->name }}</span>
                                            </span>
                                        </li>
                                        @endif
                                        @endforeach
                                    </ul>
                                    <input class="options category" type="hidden" name="f_category_id" value="{{ request()->get('f_category_id', null) }}" />

                                </div>
                                <!-- Categories Ends -->
                                <hr>
                                @endif
                                <!-- Brands -->
                                @if(count($brands) > 0)
                                <?php $selected_brands = explode('.', request()->get('f_brand_id', null)) ?>
                                <div class="brands brands-filter">
                                    <div class="brand-title mt-1 pb-1">
                                        <h6 class="filter-title mb-0">Brendovi</h6>
                                    </div>
                                    <div class="brand-list" id="brands">
                                        <ul class="list-unstyled">
                                            @foreach($brands as $brand)
                                            <li class="d-flex justify-content-between align-items-center py-25">
                                                <span class="vs-checkbox-con vs-checkbox-primary">
                                                    <input id="brand{{ $brand->id }}" @if(in_array($brand->id, $selected_brands)) checked="checked" @endif type="checkbox" value="{{ $brand->id }}" />
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                <span class="">{{ $brand->name }}</span>
                                                </span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <input class="options brand" type="hidden" name="f_brand_id" value="{{ request()->get('f_brand_id', null) }}" />
                                    </div>
                                </div>
                                <!-- /Brand -->
                                @endif
								
                                <?php $selected_badges = explode('.', request()->get('f_badge_id', null)) ?>
                                <div class="brands badges-filter">
                                    <div class="brand-title mt-1 pb-1">
                                        <h6 class="filter-title mb-0">Oznake</h6>
                                    </div>
                                    <div class="brand-list" id="badges">
                                        <ul class="list-unstyled">
                                            @foreach(get_codebook_opts('product_badges') as $badge)
                                            <li class="d-flex justify-content-between align-items-center py-25">
                                                <span class="vs-checkbox-con vs-checkbox-primary">
                                                    <input id="badge{{ $badge->code }}" @if(in_array($badge->code, $selected_badges)) checked="checked" @endif type="checkbox" value="{{ $badge->code }}" />
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                <span class="">{{ $badge->name }}</span>
                                                </span>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <input class="options badge" type="hidden" name="f_badge_id" value="{{ request()->get('f_badge_id', null) }}" />
                                    </div>
                                </div>
                                <!-- /Brand -->
                            </div>
                        </div>
                    </div>
                    <!-- Ecommerce Sidebar Ends -->

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
@endsection

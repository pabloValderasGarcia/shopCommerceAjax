@extends('layouts.app')

@section('navItems')

<li><a href="{{ url('/') }}">Home</a></li>
<li><a href="{{ url('/shop') }}" class="active">Shop</a></li>
<li><a href="{{ url('/about') }}">About Us</a></li>
<li><a href="{{ url('/contact') }}">Contact</a></li>

@endsection

@section('content')

<script>
    window.addEventListener("load", (event) => {
        $.ajax({
            type: 'get',
            url: '{{ url("getDataProducts") }}',
            data: {'q': ''},
            success: function(data) {
                showDataProducts(data);
            }
        });
    });
</script>

<!-- Begin Li's Content Wraper Area -->
<div class="content-wraper pt-60 pb-60">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 order-1 order-lg-2">
                <!-- Begin Li's Banner Area -->
                <div class="single-banner shop-page-banner" style="background-image: url({{ url("/assets/images/bg-banner/1.jpg") }})"><p>Products<br/><span>Shopping</span></p></div>
                <!-- Li's Banner Area End Here -->
                <!-- shop-top-bar start -->
                <div class="shop-top-bar mt-30">
                    <div class="shop-bar-inner">
                        <div class="product-view-mode">
                            <!-- shop-item-filter-list start -->
                            <ul class="nav shop-item-filter-list" role="tablist">
                                <li class="active" role="presentation"><a aria-selected="true" class="active show" data-toggle="tab" role="tab" aria-controls="list-view" href="#list-view"><i class="fa fa-th-list text-dark"></i></a></li>
                            </ul>
                            <!-- shop-item-filter-list end -->
                        </div>
                    </div>
                    <!-- product-select-box start -->
                    <div class="product-select-box">
                        <div class="product-short">
                            <p>Sort By:</p>
                            <select id="order-select" class="nice-select" data-url="{{ url('shop') }}" onchange="orderByFunction(this)">
                                <option value="{{ $order['products.name']['asc'] }}" id="b1t1">Name (A - Z)</option>
                                <option value="{{ $order['products.name']['desc'] }}" id="b1t2">Name (Z - A)</option>
                                <option value="{{ $order['products.price']['asc'] }}" id="b2t1">Price (Low - High)</option>
                                <option value="{{ $order['products.price']['desc'] }}" id="b2t2">Price (High - Low)</option>
                            </select>
                        </div>
                    </div>
                    <!-- product-select-box end -->
                </div>
                <!-- shop-top-bar end -->
                <!-- shop-products-wrapper start -->
                <div class="shop-products-wrapper">
                    <div class="tab-content">
                        <div id="list-view" class="tab-pane fade product-list-view active show" role="tabpanel">
                            <div class="row">
                                <div class="col" id="products-container"></div>
                            </div>
                        </div>
                        <div class="paginatoin-area">
                            <div class="row">
                                <nav class="d-flex justify-items-center justify-content-between">
                                    <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between" id="pagination-container"></div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- shop-products-wrapper end -->
            </div>
            <div class="col-lg-3 order-2 order-lg-1">
                <!--sidebar-categores-box start  -->
                <div class="sidebar-categores-box">
                    <div class="sidebar-title" style="border: none">
                        <h2 style="margin-bottom: 0.6em">Filter By</h2>
                    </div>
                    <!-- btn-clear-all start -->
                    <a class="btn-clear-all mb-sm-30 mb-xs-30" style="margin-top: 0; padding: 0.5em 1em" onclick="clearAllFilters()">Clear all</a>
                    <!-- btn-clear-all end -->
                    <!-- filter-sub-area start -->
                    <div class="filter-sub-area pt-sm-10 pt-xs-10">
                        <h5 class="filter-sub-titel">Categories</h5>
                        <div class="categori-checkbox">
                            <ul>
                                @foreach($categories as $category)
                                    <li style="user-select: none; display: flex; align-items: center; gap: 0.5em">
                                        <input class="product-filter" id="category-{{ $category->id }}" name="category-{{ $category->id }}" type="checkbox" style="cursor: pointer" onClick="checkBox(this)">
                                        <label for="category-{{ $category->id }}" style="cursor: pointer; transform: translateY(1px)">{{ $category->name }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                     </div>
                    <!-- filter-sub-area end -->
                    <!-- filter-sub-area start -->
                    <div class="filter-sub-area pt-sm-10 pt-xs-10">
                        <h5 class="filter-sub-titel">Price</h5>
                        <div class="size-checkbox">
                            <div style="display: flex; gap: 1em">
                                <div class="products-price" style="width: auto">
                                    <input min="1" maxlength="9" id="priceMin" type="number" name="priceMin" placeholder="€ Min" oninput="fetchPrice()"/>
                                    <input min="1" maxlength="9" id="priceMax" type="number" name="priceMax" placeholder="€ Max" oninput="fetchPrice()"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- filter-sub-area end -->
                    <!-- filter-sub-area start -->
                    <div class="filter-sub-area">
                        <h5 class="filter-sub-titel">Brand</h5>
                        <div class="categori-checkbox">
                            <ul>
                                @foreach($brands as $brand)
                                    <li style="user-select: none; display: flex; align-items: center; gap: 0.5em">
                                        <input class="product-filter" id="brand-{{ ucfirst($brand->id) }}" name="brand-{{ ucfirst($brand->id) }}" type="checkbox" style="cursor: pointer" onClick="checkBox(this)">
                                        <label for="brand-{{ ucfirst($brand->id) }}" style="cursor: pointer; transform: translateY(1px)">{{ ucfirst($brand->name) }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                     </div>
                    <!-- filter-sub-area end -->
                    <!-- filter-sub-area start -->
                    <div class="filter-sub-area pt-sm-10 pt-xs-10">
                        <h5 class="filter-sub-titel">Color</h5>
                        <div class="categori-checkbox">
                            <ul>
                                @foreach($colors as $color)
                                    <li style="user-select: none; display: flex; align-items: center; gap: 0.5em">
                                        <span style="background-color: {{ $color->hex }}; width: 1em; height: 1em"></span>
                                        <input class="product-filter" id="color-{{ $color->id }}" name="color-{{ $color->id }}" type="checkbox" style="cursor: pointer" onClick="checkBox(this)">
                                        <label for="color-{{ $color->id }}" style="cursor: pointer; transform: translateY(1px)">{{ ucfirst($color->name) }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- filter-sub-area end -->
                    <!-- filter-sub-area start -->
                    <div class="filter-sub-area pt-sm-10 pb-sm-15 pb-xs-15">
                        <h5 class="filter-sub-titel">Stock</h5>
                        <div class="categori-checkbox">
                            <ul>
                                @foreach($stocks as $stock)
                                    <li style="user-select: none; display: flex; align-items: center; gap: 0.5em">
                                        <input class="product-filter" id="stock-{{ $stock->stock }}" name="stock-{{ $stock->stock }}" type="checkbox" style="cursor: pointer" onClick="checkBox(this)">
                                        <label for="stock-{{ $stock->stock }}" style="cursor: pointer; transform: translateY(1px)">{{ $stock->stock }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                     </div>
                    <!-- filter-sub-area end -->
                    <!-- filter-sub-area start -->
                    <div class="filter-sub-area pt-sm-10 pb-sm-15 pb-xs-15">
                        <h5 class="filter-sub-titel">Year</h5>
                        <div class="categori-checkbox">
                            <ul>
                                @foreach($years as $year)
                                    <li style="user-select: none; display: flex; align-items: center; gap: 0.5em">
                                        <input class="product-filter" id="year-{{ $year->year }}" name="year-{{ $year->year }}" type="checkbox" style="cursor: pointer" onClick="checkBox(this)">
                                        <label for="year-{{ $year->year }}" style="cursor: pointer; transform: translateY(1px)">{{ $year->year }}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                     </div>
                </div>
                <!--sidebar-categores-box end  -->
            </div>
        </div>
    </div>
</div>
<!-- Content Wraper Area End Here -->

<!-- Begin Quick View | Modal Area -->
<div class="modal fade modal-wrapper" style="z-index: 999999" id="exampleModalCenter" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-inner-area row">
                    <div class="col-lg-6 col-md-6">
                       <!-- Product Details Left -->
                        <div class="product-details-left">
                            <div class="product-details-images slider-navigation-1" id="product-details-images">
                                <div class="product-image photo-product" id="photo-product"></div>
                            </div>
                            <div class="product-details-thumbs slider-thumbs-1" id="product-details-thumbs"></div>
                        </div>
                        <!--// Product Details Left -->
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="product-details-view-content">
                            <div class="product-info modal-quick-view">
                                <h2><a href="{{ url('/shop/products/') }}" id="name-product"></a></h2>
                                <div class="rating-box">
                                    <ul class="rating rating-with-review-item">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li class="no-star"><i class="fa-solid fa-star"></i></li>
                                        <li class="no-star"><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </div>
                                <div class="price-box pt-20">
                                    <span class="new-price new-price-2" id="price-product"></span>
                                </div>
                                <div class="product-desc">
                                    <p>
                                        <span id="excerpt-product"></span>
                                    </p>
                                </div>
                                <form action="" method="POST" id="addCartForm">
                                    @csrf
                                </form>
                                <div class="single-add-to-cart cart-quantity">
                                    @if(Auth::user())
                                        <div style="display: flex; gap: 0.5em" id="addCartDiv">
                                            <input style="flex: .2" class="cart-plus-minus-box prod-qty" min="1" value="1" type="number">
                                        </div>
                                    @else
                                        <a href="{{ url('/home') }}" type="submit" class="add-to-cart">Login To Buy</a>
                                    @endif
                                </div>
                                <div style="padding-top: 4.6em">
                                    <a id="view-more">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ url('/assets/js/ajax.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
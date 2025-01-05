@extends('layouts.app')
@section('content')

<main class="pt-90">
    <section class="shop-main container ">
        <br>
        <br>
        <div class="d-flex justify-content-between mb-6 pb-md-4">
          <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
            <a href="{{route('home.index')}}" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
            <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
            <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
          </div>

        
        </div>

        <div class="products-grid row row-cols-3 row-cols-md-4" id="products-grid">
          @foreach ($products as $product)
          <div class="product-card-wrapper">
            <div class="product-card mb-3 mb-md-4 mb-xxl-5">
              <div class="pc__img-wrapper">
                <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                  <div class="swiper-wrapper">
                    <div class="swiper-slide">
                      <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}"><img loading="lazy" src="{{asset('uploads/products')}}/{{$product->image}}" width="330" height="400" alt="{{$product->name}}" class="pc__img"></a>
                    </div>
                    <div class="swiper-slide">
                      @foreach (explode(",",$product->images ) as $gimg)
                      <a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}"><img loading="lazy" src="{{asset('uploads/products')}}/{{$gimg}}" width="330" height="400" alt="{{$product->name}}" class="pc__img"></a>
                          @endforeach
                     </div>
                  </div>
                  <span class="pc__img-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_prev_sm" />
                    </svg></span>
                  <span class="pc__img-next"><svg width="7" height="11" viewBox="0 0 7 11"
                      xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_next_sm" />
                    </svg></span>
                </div>
                @if(Cart::instance('cart')->content()->where('id',$product->id)->count()>0)
                   <a href="{{route('cart.index')}}" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium btn-warning mb-3"> Go to Cart</a>
                @else
                <form name="addtocart-form" method='post' action="{{route('cart.add')}}">
                  @csrf
                  <input type="hidden" name="id" value="{{$product->id}}" />
                  <input type="hidden" name="name" value="{{$product->name}}" />
                  <input type="hidden" name="quantity" value="1" />
                  <input type="hidden" name="price" value="{{$product->sale_price == '' ? $product->regular_price : $product->sale_price}}" />
                  <button type="submit"  class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium" data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
                </form>
                   @endif
                </div>

              <div class="pc__info position-relative">
                <p class="pc__category">{{$product->category->name}}</p>
                <h6 class="pc__title"><a href="{{route('shop.product.details',['product_slug'=>$product->slug])}}">{{$product->name}}</a></h6>
                <div class="product-card__price d-flex">
                  <span class="money price">
                    @if($product->sale_price)
                       <s>RS.{{$product->regular_price}}</s>{{$product->sale_price}}
                    @else
                    ${{$product->regular_price}}
                    @endif
                  </span>
                </div>
                <div class="product-card__review d-flex align-items-center">
                  <div class="reviews-group d-flex">
                    <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/200 0/svg">
                      <use href="#icon_star" />
                    </svg>
                    <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_star" />
                    </svg>
                    <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_star" />
                    </svg>
                    <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_star" />
                    </svg>
                    <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                      <use href="#icon_star" />
                    </svg>
                  </div>
                  <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
                </div>

                <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                  title="Add To Wishlist">
                  <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_heart" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
          @endforeach

        </div>
        <div class="divider"></div>
        <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
          {{$products->links('pagination::bootstrap-5')}}
     
      </div>>
    </section>
  </main>

@endsection
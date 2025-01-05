
@extends('layouts.admin')
@section('content')

<div class="main-content-inner">
    <!-- main-content-wrap -->
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Add Product</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{route('admin.index')}}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <a href="{{route('admin.products')}}">
                        <div class="text-tiny">Products</div>
                    </a>
                </li>
                <li>
                    <i class="icon-chevron-right"></i>
                </li>
                <li>
                    <div class="text-tiny">Add product</div>
                </li>
            </ul>
        </div>

    <!-- Error messages block -->
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <!-- form-add-product -->
        <form class="register-form outer-top-xs" enctype="multipart/form-data" method="POST" action="{{ route('admin.product.store') }}">
            @csrf
            <div class="form-group">
                <input class="mb-10" type="text" placeholder="Enter product name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" value="{{ old('slug') }}" required>
                @error('slug')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <select class="mb-10" name="category_id" required>
                    <option value="" disabled selected>Choose category</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <select class="mb-10" name="brand_id" required>
                    <option value="" disabled selected>Choose brand</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select>
                @error('brand_id')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <textarea class="mb-10" name="short_description" placeholder="Enter a short description (max 255 characters)" required>{{ old('short_description') }}</textarea>
                @error('short_description')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <textarea class="mb-10" name="description" placeholder="Enter detailed product description" required>{{ old('description') }}</textarea>
                @error('description')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <input id="product_image" class="mb-10" type="file" name="image" accept="image/*" required>
                <img src="{{ asset('images/upload/upload-1.png') }}" class="effect8" alt="">
                @error('image')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <input id="gallery_images" class="mb-10" type="file" name="images[]" multiple accept="image/*">
                @error('images.*')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <input class="mb-10" type="number" placeholder="Enter regular price" name="regular_price" step="0.01" value="{{ old('regular_price') }}" required>
                @error('regular_price')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <input class="mb-10" type="number" placeholder="Enter sale price (less than regular price)" name="sale_price" step="0.01" value="{{ old('sale_price') }}" required>
                @error('sale_price')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU" value="{{ old('SKU') }}" required>
                @error('SKU')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <input class="mb-10" type="number" placeholder="Enter quantity" name="quantity" value="{{ old('quantity') }}" min="0" required>
                @error('quantity')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <select class="mb-10" name="stock_status" required>
                    <option value="" disabled selected>Choose stock status</option>
                    <option value="instock" {{ old('stock_status') == 'instock' ? 'selected' : '' }}>In Stock</option>
                    <option value="outofstock" {{ old('stock_status') == 'outofstock' ? 'selected' : '' }}>Out of Stock</option>
                </select>
                @error('stock_status')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <select class="mb-10" name="featured" required>
                    <option value="1" {{ old('featured') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('featured') == '0' ? 'selected' : '' }}>No</option>
                </select>
                @error('featured')
                <div class="alert alert-danger text-center">{{ $message }}</div>
                @enderror
            </div>
        
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Add Product</button>
            </div>
        </form>
        
        <!-- /form-add-product -->
    </div>
    <!-- /main-content-wrap -->
</div>


@endsection


@push('scripts')
<script>
    $(function(){
        $("#myFile").on("change",function(e){
              const photoInp = $("#myFile");
              const [file] = this.files;
              if(file)
              {
                $("#imgpreview img").attr('src',URL.createObjectURL(file));
                $("#imgpreview").show();
            }
        });

        $("#gFile").on("change",function(e){
              const photoInp = $("#gFile");
              const gphotos = this.files;
              $.each(gphotos,function(key,val)
              {
                $("#galUpload").prepend('<div class="item gitems"><img src="' + URL.createObjectURL(val) + '" /></div>');
              });
        });
        $("input[name='name']").on("change",function(){
            $("input[name='slug']").val(StringToSlug($(this).val()));
        });
    });

    function StringToSlug(Text)
    {
       return Text.toLowerCase()
       .replace(/[^\w]+/g,"")
       .replace(/ +/g,"-");
    }
</script>

@endpush  
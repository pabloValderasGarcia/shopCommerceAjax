@extends('layouts.app')

@section('navItems')

<li><a href="{{ url('/') }}">Home</a></li>
<li><a href="{{ url('/shop') }}">Shop</a></li>
<li><a href="{{ url('/about') }}">About Us</a></li>
<li><a href="{{ url('/contact') }}">Contact</a></li>

@endsection

@section('scripts')
    <script>
        // ADMIN
        function changeManagement(e) {
            let usersManagement = document.querySelector('.users-management');
            let productsManagement = document.querySelector('.products-management');
            let categoriesManagement = document.querySelector('.categories-management');
            if (e.innerHTML == 'Products') {
                usersManagement.style.display = 'none';
                productsManagement.style.display = 'block';
                categoriesManagement.style.display = 'none';
            } else if (e.innerHTML == 'Categories') {
                usersManagement.style.display = 'none';
                productsManagement.style.display = 'none';
                categoriesManagement.style.display = 'block';
            } else if (e.innerHTML == 'Users') {
                usersManagement.style.display = 'block';
                productsManagement.style.display = 'none';
                categoriesManagement.style.display = 'none';
            }
        }
        
        // DELETE PRODUCT
        function deleteProduct(e, form) {
            /* global $ */
            e.preventDefault();
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                type: 'delete',
                url: 'deleteProduct/' + form.dataset.id,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#deleteElement').hide();
                    $('#deleteElement').modal('hide');
                    $('#deleteElement').removeClass('show');
                    
                    $('.modal-backdrop').hide();
                    $('#message-alert').css('display', 'block');
                    if (data.error) {
                        $('#message-alert').removeAttr('class');
                        $('#message-alert').attr('class', 'alert alert-danger message-alert');
                    } else {
                        $('#message-alert').removeAttr('class');
                        $('#message-alert').attr('class', 'alert alert-success message-alert');
                    }
                    $('.message-alert .container').html(data.message);
                    renderProducts(data.products);
                }
            });
            $('.modal-backdrop').hide();
            return false;
        }
        
        // EDIT PRODUCT
        function editModal(target) {
            $('#editProduct').show();
            $('#editProduct').addClass('show');
            $('body').addClass('modal-open');
            $('.modal-backdrop').show();
                    
            let id = target.dataset.id;
            let thumbnail = target.dataset.thumbnail;
            let name = target.dataset.name;
            let excerpt = target.dataset.excerpt;
            let description = target.dataset.description;
            let brand = target.dataset.brand;
            let price = target.dataset.price;
            let color = target.dataset.color;
            let stock = target.dataset.stock;
            let year = target.dataset.year;
            let category = target.dataset.category;
            
            $('#editProductForm').attr('data-id', id);
            
            $('#editProductForm #name').val(name);
            $('#editProductForm #excerpt').val(excerpt);
            $('#editProductForm #description').val(description);
            $('#editProductForm #price').val(price);
            $('#editProductForm #stock').val(stock);
            $('#editProductForm #year').val(year);
            
            $('#editProductForm #idColor').val(color.charAt(0).toLowerCase() + color.slice(1));
            $('#editProductForm #idBrand').val(brand);
            $('#editProductForm #idCat').val(category);
            
            $('#editProductForm #thumbnail-div').attr('style', 'background-image: url(data:image/jpeg;base64,' + thumbnail + ')');
            
            $.ajax({
                type: 'get',
                url: 'getImagesProduct',
                data: {'id': id},
                success: (data) => {
                    let html = '';
                    $('#editProductForm #images_wrap').html('');
                    data.images.forEach(image => {
                        html += `
                            <div class="upload__img-box">
                                <div style="background-image: url({{ asset('../storage/app/imagesProduct-') }}` + id + `/` + image.path + `)" data-url="{{ url('deleteImage/') }}/` + image.id + `" class="img-bg">
                                    <div class="upload__img-close deleteImageClass"></div>
                                </div>
                            </div>
                        `;
                    });
                    $('#editProductForm #images_wrap').append(html);
                }
            });
        }
        function editProduct(e, form) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'editProduct/' + form.dataset.id,
                data: new FormData(document.getElementById("editProductForm")),
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#editProduct').hide();
                    $('#editProduct').removeClass('show');
                    $('body').removeClass('modal-open');
                    
                    $('.modal-backdrop').hide();
                    $('#message-alert').css('display', 'block');
                    if (data.error) {
                        $('#message-alert').removeAttr('class');
                        $('#message-alert').attr('class', 'alert alert-danger message-alert');
                    } else {
                        $('#message-alert').removeAttr('class');
                        $('#message-alert').attr('class', 'alert alert-success message-alert');
                    }
                    $('.message-alert .container').html(data.message);
                    renderProducts(data.products);
                }
            });
            return false;
        }
        
        // CREATE PRODUCT
        function createProduct(e, form) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'createProduct',
                data: new FormData(document.getElementById("createProductForm")),
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#createProduct').hide();
                    $('#createProduct').modal('hide');
                    $('#createProduct').removeClass('show');
                    
                    $('.modal-backdrop').hide();
                    $('#message-alert').css('display', 'block');
                    if (data.error) {
                        $('#message-alert').removeAttr('class');
                        $('#message-alert').attr('class', 'alert alert-danger message-alert');
                    } else {
                        $('#message-alert').removeAttr('class');
                        $('#message-alert').attr('class', 'alert alert-success message-alert');
                    }
                    $('.message-alert .container').html(data.message);
                    renderProducts(data.products);
                }
            });
            return false;
        }
        
        // RENDER PRODUCTS
        function renderProducts(products){
            let table = document.getElementById('table-products');
            let tableContent = "";
            for (product of products) {
                /* global product */
                let tr = document.createElement('tr');
                let color = product.idColor;
                tableContent += `
                    <tr>
                        <td>${product.id}</td>
                        <td colspan="2"><div style="height: 100%; display: flex; align-items: center"><img src="data:image/jpeg;base64,` + product.thumbnail + `" class="avatar"> <a href="{{ url('/shop/products/') }}/` + product.id + `">` + product.name + `</a></div></td>
                        <td>${product.idBrand}</td>
                        <td>${product.price}€</td>
                        <td><div style="display: flex; align-items: center; gap: 0.6em"><div style="background-color: ${product.colorHex}; width: 1em; height: 1em"></div><p style="margin: 0; color: black; transform: translateY(1.4px)">${color.charAt(0).toUpperCase() + color.slice(1)}</p></div></td>
                        <td>${product.stock}</td>
                        <td>${product.year}</td>
                        <td>${product.created_at.substr(0, 10)}</td>
                        <td>${product.idCat}</td>
                        <td>
                            @if (Auth::user()->role_as == 1)
                                <div style="display: flex;">
                                    <!-- Edit -->
                                    <a href="javascript:void(0)" 
                                        
                                        data-id="${product.id}" 
                                        data-thumbnail="${product.thumbnail}" 
                                        data-name="${product.name}" 
                                        data-excerpt="${product.excerpt}" 
                                        data-description="${product.description}" 
                                        data-brand="${product.idBrand}" 
                                        data-price="${product.price}" 
                                        data-color="${color.charAt(0).toUpperCase() + color.slice(1)}" 
                                        data-stock="${product.stock}" 
                                        data-year="${product.year}" 
                                        data-category="${product.idCat}"
                                        
                                        data-toggle="modal" 
                                        data-target="#editProduct" onclick="editModal(this)">
                                            <i class="fas fa-edit" style="color: green; margin: 0; transform: translateY(1px)"></i>
                                    </a>
                                    &nbsp;
                                    <!-- Delete -->
                                    <a href="" class="deleteLinkElement" data-id="` + product.id + `" data-bs-toggle="modal" 
                                    data-bs-target="#deleteElement" data-type="product"
                                    data-name="` + product.name + ` <span style='font-weight: 400'>product</span>">
                                        <button class="material-icons delete-button">&#xE5C9;</button>
                                    </a>
                                </div>
                            @endif
                        </td>
                    </tr>
                `
            }
            table.innerHTML = tableContent;
            
            // DELETE ELEMENT (INIT)
            let deletesElement = document.querySelectorAll('.deleteLinkElement');
            deletesElement.forEach(deletes => {
                let id = deletes.dataset.id;
                let typeElement = deletes.dataset.type;
                let element = deletes.dataset.name;
                deletes.addEventListener("click", () => {
                    document.getElementById('deleteFormElement').setAttribute('data-id', id);
                    document.getElementById('deleteElementType').innerHTML = typeElement;
                    document.getElementById('deleteElementTypeInput').setAttribute('value', 'Delete ' + typeElement);
                    document.getElementById('deleteElementName').innerHTML = element;
                    $('.modal-backdrop').show();
                });
            });
        }
    </script>
@endsection

@section('content')

<div id="message-alert">
    <p class="container"></p>
</div>

<!-- CREATE CATEGORY MODAL -->
<div class="modal fade" id="createCategory" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ url('shop/categories') }}" method="POST">
                @csrf
                <div class="modal-header d-flex align-items-center">
                    <h6 class="modal-title mb-0" id="threadModalLabel">| New Category</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input required value="{{ old('name') }}" type="text" class="form-control" name="name" placeholder="Enter name" minlength="4" maxlength="25"/>
                    </div>
                    <div class="modal-footer" style="padding: 0; padding-top: 1em; display: flex; gap: 0.5em">
                        <a type="button" class="btn btn-danger bg-danger text-white" data-dismiss="modal" style="flex: .2; margin: 0; border: 0; border-radius: 0">Cancel</a>
                        <button type="submit" class="btn btn-primary bg-success" style="flex: .8; margin: 0; border: 0; border-radius: 0">Create category</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CREATE PRODUCT MODAL -->
<div class="modal fade" id="createProduct" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 1200px">
        <div class="modal-content" style="padding: 2.2em 3em">
            <div class="mb-30" style="font-size: 2em; font-weight: bold">Create Product</div>
            <form class="row g-3" onsubmit="return createProduct(event, this)" id="createProductForm">
                @csrf
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input minlength="3" maxlength="20" type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                </div>
                @error('name')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-md-6">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" min="1" step="any" class="form-control" name="price" id="price" value="{{ old('price') }}" required>
                </div>
                @error('price')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-md-12">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <input minlength="10" maxlength="100" type="text" class="form-control" name="excerpt" id="excerpt" value="{{ old('excerpt') }}" required>
                </div>
                @error('excerpt')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea minlength="50" maxlength="600" type="text" class="form-control" name="description" id="description" style="min-height: 10em" required>{{ old('description') }}</textarea>
                </div>
                @error('description')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                <div class="col-4">
                    <label for="idColor" class="form-label">Color</label>
                    <select required style="cursor: pointer;" id="idColor" class="form-select" name="idColor" value="{{ old('idColor') }}" required>
                        <option disabled selected>Choose...</option>
                        @foreach($colors as $color)
                            <option style="cursor: pointer; background-color: {{ $color->hex }}; color: transparent" value="{{ $color->name }}">{{ ucfirst($color->name) }}</option>
                        @endforeach
                    </select>
                </div>
                @error('color')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-4">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" min="1" step="any" class="form-control" name="stock" id="stock" value="{{ old('stock') }}" required>
                </div>
                @error('stock')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-4">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" min=1990 max=2023 step="any" class="form-control" name="year" id="year" value="{{ old('year') }}" required>
                </div>
                @error('year')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-md-6">
                    <label for="idBrand" class="form-label">Brand</label>
                    <select required style="cursor: pointer" id="idBrand" class="form-select" name="idBrand" value="{{ old('idBrand') }}" required>
                        <option disabled selected>Choose...</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('brand')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-md-6">
                    <label for="idCat" class="form-label">Category</label>
                    <select required style="cursor: pointer" id="idCat" class="form-select" name="idCat" value="{{ old('idCat') }}" required>
                        <option disabled selected>Choose...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('category')
                    <div class="alert alert-danger container">{{ $message }}</div>
                @enderror
                
                <div class="col-md-12 form-group">
        	        <label class="form-label" for="thumbnail">Thumbnail</label>
        	        <div class="upload__box">
                        <div class="upload__btn-box" style="position: relative">
                            <label id="thumbnail_label" class="upload__btn" style="position: relative; z-index: 1">
                                <i class="fas fa-image" style="font-size: 3em; color: rgb(220, 220, 220)"></i>
                                <p class="mb-0">Choose thumbnail</p>
                            </label>
                            <input type="file" id="thumbnail_input" name="thumbnail" data-type="thumbnail" class="upload__thumbnail" required style="width: 10px; color: transparent; background-color: transparent; position: absolute; bottom: 0; left: 35%; z-index: 0">
                        </div>
                        <div id="thumbnail_wrap" class="upload__img-wrap w-100"></div>
                    </div>
                    @error('thumbnail')
                        <div class="alert alert-danger container">{{ $message }}</div>
        	        @enderror
        	    </div>
        	    
        	    <div class="col-md-12 form-group mb-3">
        	        <label class="form-label" for="image">Upload images</label>
        	        <div class="upload__box">
                        <div class="upload__btn-box">
                            <label class="upload__btn">
                                <i class="fa-solid fa-folder-open" style="font-size: 3em; color: rgb(220, 220, 220)"></i>
                                <p class="mb-0">Choose images</p>
                                <input type="file" id="images_input" name="image[]" multiple="" data-type="images" class="upload__inputfile" required>
                            </label>
                        </div>
                        <div id="images_wrap" class="upload__img-wrap upload_images_wrap"></div>
                    </div>
                    @error('image')
                        <div class="alert alert-danger container">{{ $message }}</div>
        	        @enderror
        	    </div>
                
                <div class="col-12" style="display: flex; text-align: center; gap: 1em">
                    <a href="{{ url('/dashboard') }}" class="element-management element-management2 create_product" style="flex: .2" data-dismiss="modal">Back</a>
                    <button type="submit" class="element-management create_product" style="border: none; flex: .8">Create Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT PRODUCT MODAL -->
<div class="modal fade" id="editProduct" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 1200px">
        <div class="modal-content" style="padding: 2.2em 3em">
            <div class="mb-30" style="font-size: 2em; font-weight: bold">Edit Product</div>
            <form class="row g-3" onsubmit="return editProduct(event, this)" id="editProductForm">
                @csrf
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input minlength="3" maxlength="20" type="text" class="form-control" name="name" id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" min="1" step="any" class="form-control" name="price" id="price" value="{{ old('price') }}" required>
                    @error('price')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <input minlength="10" maxlength="100" type="text" class="form-control" name="excerpt" id="excerpt" value="{{ old('excerpt') }}" required>
                    @error('excerpt')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-12">
                    <label for="description" class="form-label">Description</label>
                    <textarea minlength="50" maxlength="600" type="text" class="form-control" name="description" id="description" style="min-height: 10em" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-4">
                    <label for="idColor" class="form-label">Color</label>
                    <select required style="cursor: pointer;" id="idColor" class="form-select" name="idColor" value="{{ old('idColor') }}" required>
                        <option disabled selected>Choose...</option>
                        @foreach($colors as $color)
                            <option style="cursor: pointer; background-color: {{ $color->hex }}; color: transparent" value="{{ $color->name }}">{{ ucfirst($color->name) }}</option>
                        @endforeach
                    </select>
                    @error('color')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-4">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" min="1" step="any" class="form-control" name="stock" id="stock" value="{{ old('stock') }}" required>
                    @error('stock')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-4">
                    <label for="year" class="form-label">Year</label>
                    <input type="number" min=1990 max=2023 step="any" class="form-control" name="year" id="year" value="{{ old('year') }}" required>
                    @error('year')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="idBrand" class="form-label">Brand</label>
                    <select required style="cursor: pointer" id="idBrand" class="form-select" name="idBrand" value="{{ old('idBrand') }}" required>
                        <option disabled selected>Choose...</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-6">
                    <label for="idCat" class="form-label">Category</label>
                    <select required style="cursor: pointer" id="idCat" class="form-select" name="idCat" value="{{ old('idCat') }}" required>
                        <option disabled selected>Choose...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <div class="alert alert-danger container">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="col-md-12 form-group">
        	        <label class="form-label" for="thumbnail">Thumbnail</label>
        	        <div class="upload__box">
                        <div class="upload__btn-box" style="position: relative">
                            <label id="thumbnail_label" class="upload__btn" style="position: relative; z-index: 1">
                                <i class="fas fa-image" style="font-size: 3em; color: rgb(220, 220, 220)"></i>
                                <p class="mb-0">Choose thumbnail</p>
                            </label>
                            <input type="file" id="thumbnail_input" name="thumbnail" data-type="thumbnail" class="upload__thumbnail" style="width: 10px; color: transparent; background-color: transparent; position: absolute; bottom: 0; left: 35%; z-index: 0">
                        </div>
                        <div id="thumbnail_wrap" class="upload__img-wrap w-100">
                            <div class="upload__img-box">
                                <div id="thumbnail-div" class="img-bg"><div class="upload__img-close"></div></div>
                            </div>
                        </div>
                    </div>
                    @error('thumbnail')
                        <div class="alert alert-danger container">{{ $message }}</div>
        	        @enderror
        	    </div>
        	    
        	    <div class="col-md-12 form-group mb-3">
        	        <label class="form-label" for="image">Upload images</label>
        	        <div class="upload__box">
                        <div class="upload__btn-box">
                            <label class="upload__btn">
                                <i class="fa-solid fa-folder-open" style="font-size: 3em; color: rgb(220, 220, 220)"></i>
                                <p class="mb-0">Choose images</p>
                                <input type="file" id="images_input" name="image[]" multiple="" data-type="images" class="upload__inputfile">
                            </label>
                        </div>
                        <div id="images_wrap" class="upload__img-wrap upload_images_wrap"></div>
                    </div>
                    @error('image')
                        <div class="alert alert-danger container">{{ $message }}</div>
        	        @enderror
        	    </div>
                
                <div class="col-12" style="display: flex; text-align: center; gap: 1em">
                    <a href="{{ url('/dashboard') }}" class="element-management element-management2 create_product" style="flex: .2" data-dismiss="modal">Back</a>
                    <button type="submit" class="element-management create_product" style="border: none; flex: .8">Edit Product</button>
                </div>
            </form>
        </div>
        
        <form action="" method="POST" id="deleteImageForm">
            @csrf
            @method('delete')
        </form>
    </div>
</div>

<!-- USERS -->
<div class="container-xl users-management" style="color: #566787;
    background: #f5f5f5;
    font-family: 'Varela Round', sans-serif;
    font-size: 13px;">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div style="width: 100%; display: flex; justify-content: space-between;">
                <p class="element-management element-management2" onclick="changeManagement(this)">Products</p>
                <div style="display: flex; gap: 1em">
                    <p class="element-management element-management2" onclick="changeManagement(this)">Categories</p>
                    <p class="element-management" data-toggle="modal" data-target="#createCategory">Create Category</p>
                </div>
            </div>
            <div class="table-title" style="padding: 16px 30px">
                <div class="row">
                    <div class="col-sm-5">
                        <h2>Users <b>Management</b></h2>
                    </div>
                    <div class="col-sm-7">
                        <button class="btn btn-secondary"><i class="material-icons">&#xE24D;</i> <span>Export to Excel</span></button>						
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover" id="table-export">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>						
                        <th>Email</th>
                        <th>Date Created</th>
                        <th>Role</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- foreach -->
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td id="user-element" data-picture="https://placekitten.com/{{ $rand = rand(100, 200) }}/{{$rand}}"><img src="https://placekitten.com/{{$rand}}/{{$rand}}" class="avatar" alt="Avatar"> {{ $user->name }}</td>
                            <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                            <td id="date-element">{{ substr($user->created_at, 0, 10) }}</td>                        
                            <td>@if ($user->role_as != 0)Admin @else User @endif</td>
                            <td>
                                @if ($user->role_as != 1)
                                    <a href="" class="deleteLinkElement" data-bs-toggle="modal" 
                                        data-bs-target="#deleteElement" data-type="user"
                                        data-name="'{{ $user->name }}' <span style='font-weight: 400'>user</span>" data-url="{{ url('/admin/' . $user->id) }}">
                                        <button class="material-icons delete-button">&#xE5C9;</button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 

<!-- PRODUCTS -->
<div class="container-xl products-management" style="color: #566787;
    background: #f5f5f5;
    font-family: 'Varela Round', sans-serif;
    font-size: 13px;">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div style="width: 100%; display: flex; justify-content: space-between;">
                <p class="element-management element-management2" onclick="changeManagement(this)">Users</p>
                <div style="display: flex; gap: 1em">
                    <p class="element-management element-management2" onclick="changeManagement(this)">Categories</p>
                    <p class="element-management" data-toggle="modal" data-target="#createCategory">Create Category</p>
                </div>
            </div>
            <div class="table-title" style="padding: 16px 30px">
                <div class="row">
                    <div class="col-sm-5">
                        <h2>Products <b>Management</b></h2>
                    </div>
                    <div class="col-sm-7">
                        <div style="display: flex; justify-content: flex-end">
                            <a href="javascript:void(0)" class="element-management mb-0 create_product" data-toggle="modal" data-target="#createProduct">Create Product</a>
                            <button class="btn btn-secondary"><i class="material-icons">&#xE24D;</i> <span>Export to Excel</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover" id="table-export">
                <thead>
                    <tr>
                        <th>#</th>
                        <th colspan="2">Name</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Color</th>
                        <th>Stock</th>
                        <th>Year</th>
                        <th>Created</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-products">
                    <!-- foreach -->
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td colspan="2"><div style="height: 100%; display: flex; align-items: center"><img src="data:image/jpeg;base64,{{ $product->thumbnail }}" class="avatar"> <a href="{{ url('/shop/products/' . $product->id) }}">{{ $product->name }}</a></div></td>
                            <td>{{ DB::table('brands')->where('id', $product->idBrand)->value('name') }}</td>
                            <td>{{ $product->price }}€</td>
                            <td><div style="display: flex; align-items: center; gap: 0.6em"><div style="background-color: {{ DB::table('colors')->where('id', $product->idColor)->value('hex') }}; width: 1em; height: 1em"></div><p style="margin: 0; color: black; transform: translateY(1.4px)">{{ ucfirst(DB::table('colors')->where('id', $product->idColor)->value('name')) }}</p></div></td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->year }}</td>
                            <td>{{ substr($product->created_at, 0, 10) }}</td>
                            <td>{{ DB::table('categories')->where('id', $product->idCat)->value('name') }}</td>
                            <td>
                                @if (Auth::user()->role_as == 1)
                                    <div style="display: flex;">
                                        <!-- Edit -->
                                        <a href="javascript:void(0)" 
                                        
                                        data-id="{{ $product->id }}" 
                                        data-thumbnail="{{ $product->thumbnail }}" 
                                        data-name="{{ $product->name }}" 
                                        data-excerpt="{{ $product->excerpt }}" 
                                        data-description="{{ $product->description }}" 
                                        data-brand="{{ DB::table('brands')->where('id', $product->idBrand)->value('name') }}" 
                                        data-price="{{ $product->price }}" 
                                        data-color="{{ ucfirst(DB::table('colors')->where('id', $product->idColor)->value('name')) }}" 
                                        data-stock="{{ $product->stock }}" 
                                        data-year="{{ $product->year }}" 
                                        data-category="{{ DB::table('categories')->where('id', $product->idCat)->value('name') }}"
                                        
                                        data-toggle="modal" 
                                        data-target="#editProduct" onclick="editModal(this)">
                                            <i class="fas fa-edit" style="color: green; margin: 0; transform: translateY(1px)"></i>
                                        </a>
                                        &nbsp;
                                        <!-- Delete -->
                                        <a href="" class="deleteLinkElement" data-id="{{ $product->id }}" data-bs-toggle="modal" 
                                        data-bs-target="#deleteElement" data-type="product"
                                        data-name="'{{ $product->name }}' <span style='font-weight: 400'>product</span>">
                                            <button class="material-icons delete-button">&#xE5C9;</button>
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 

<!-- CATEGORIES -->
<div class="container-xl categories-management" style="color: #566787;
    background: #f5f5f5;
    font-family: 'Varela Round', sans-serif;
    font-size: 13px;">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div style="width: 100%; display: flex; justify-content: space-between;">
                <p class="element-management element-management2" onclick="changeManagement(this)">Users</p>
                <p class="element-management" data-toggle="modal" data-target="#createCategory">Create Category</p>
            </div>
            <div class="table-title" style="padding: 16px 30px">
                <div class="row">
                    <div class="col-sm-5">
                        <h2>Categories <b>Management</b></h2>
                    </div>
                    <div class="col-sm-7">
                        <button class="btn btn-secondary"><i class="material-icons">&#xE24D;</i> <span>Export to Excel</span></button>						
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover" id="table-export">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- foreach -->
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td id="category-element"> {{ $category->name }}</td>
                            <td>
                                @if (Auth::user()->role_as == 1)
                                    <a href="" class="deleteLinkElement" data-bs-toggle="modal" 
                                        data-bs-target="#deleteElement" data-type="category"
                                        data-name="'{{ $category->name }}' <span style='font-weight: 400'>category</span>" data-url="{{ url('/shop/categories/' . $category->id) }}">
                                        <button class="material-icons delete-button">&#xE5C9;</button>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 

@endsection
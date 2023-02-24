// SEARCH PRODUCT
function showDataProducts(data) {
    if (data.products) {
        let container = document.getElementById('products-container');
        let html = '';
        data.products.forEach(product => {
            let starsRand = Math.floor(Math.random() * (5 - 1 + 1) + 1);
            let cont = 1;
            html += `
                <div class="row product-layout-list">
                    <div class="col-lg-3 col-md-5 ">
                        <div class="product-image">
                            <a href="" class="quick-view" data-toggle="modal" data-target="#exampleModalCenter" 
                            data-id="${product.id}"
                            data-name="${product.name}"
                            data-price="${product.price}"
                            data-excerpt="${product.excerpt}"
                            data-photo="${product.thumbnail}" 
                            data-url="${data.URL}" 
                            data-imagesurl="${data.imagesURL}" 
                            data-carturl="${data.cartURL}">
                                <img id="${product.id}" src="data:image/jpeg;base64,${product.thumbnail}">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-7">
                        <div class="product_desc">
                        
                            <div class="product_desc_info">
                                <div class="product-review">
                                    <h5 class="manufacturer">${product.idCat}</h5>
                                    <div class="rating-box">
                                        <ul class="rating"> `;
                                            while(cont <= starsRand) {
                                                html += '<li><i class="fa-solid fa-star"></i></li>';
                                                cont++;
                                            }
                                            if(cont <= 5) {
                                                while(cont <= 5) {
                                                    html += '<li class="no-star"><i class="fa-solid fa-star"></i></li>';
                                                    cont++;
                                                }
                                            }
            html += `
                                        </ul>
                                    </div>
                                </div>
                                <h4><a href="" class="quick-view product_name" data-toggle="modal" data-target="#exampleModalCenter" 
                                    data-id="${product.id}"
                                    data-name="${product.name}"
                                    data-price="${product.price}"
                                    data-excerpt="${product.excerpt}"
                                    data-photo="${product.thumbnail}"
                                    data-url="${data.URL}" 
                                    data-imagesurl="${data.imagesURL}" 
                                    data-carturl="${data.cartURL}">
                                        ${product.name}
                                    </a>
                                </h4>
                                <div class="price-box" style="margin-bottom: 0.5em">
                                    <span class="new-price">${product.price}€</span>
                                </div>
                                <p style="white-space: normal; word-break: break-all;">${product.excerpt}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="shop-add-action mb-xs-30">
                            <ul class="add-actions-link">
                                <li class="add-cart">
                                    <a href="" class="quick-view product_name" data-toggle="modal" data-target="#exampleModalCenter" 
                                    data-id="${product.id}"
                                    data-name="${product.name}"
                                    data-price="${product.price}"
                                    data-excerpt="${product.excerpt}"
                                    data-photo="${product.thumbnail}"
                                    data-url="${data.URL}" 
                                    data-imagesurl="${data.imagesURL}" 
                                    data-carturl="${data.cartURL}">SHOW AND BUY</a>
                                </li>
                                <li class="wishlist" style="margin: 0; height: fit-content"><p style="color: black; margin: 0">Stock&nbsp;&nbsp;<span style="font-weight: bold; `; if(product.stock < 30) { html += `color: #e80f0f` }; html += `">${product.stock}</span></p></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <hr class="shop-separator"/>
            `
        })
        container.innerHTML = html;
        renderPagination(data.productsPagination);
    }
}

// PAGINATION
function renderPagination(pagination) {
    // Pagination
    let paginationDiv = document.getElementById('pagination-container');
    let string = '';
    if(pagination.from == null) pagination.from = 0;
    if(pagination.to == null) pagination.to = 0;
    string += '<div><p class="small text-muted">Showing <span class="fw-semibold">' + pagination.from + '</span> to <span class="fw-semibold">' + pagination.to + '</span> of <span class="fw-semibold">' + pagination.total + '</span> results</p></div><div><ul class="pagination" id="pagination-div">';
    
    let links = pagination.links;
    links.forEach(pag => {
        if (pag.active) {
            string += `
                <li class="page-item active" aria-current="page">
                    <span class="page-link data-url="${pag.url}" clickable">${pag.label}</span>
                </li>
            `;
        } else if (pag.url != null) {
            string += `
                <li class="page-item">
                    <span class="page-link clickable" data-url="${pag.url}" id="${'pag' + pag.label}">${pag.label}</span>
                </li>
            `;
        } else {
            string += `
                <li class="page-item disabled">
                    <span class="page-link" data-url="${pag.url}" aria-hidden="true">${pag.label}</span>
                </li>
            `;
        }
    });
    
    string += '</ul></div>';
    paginationDiv.innerHTML = string;
    addPaginationFunctionality();
}

function addPaginationFunctionality() {
    let pagination = document.getElementById('pagination-div');
    pagination.addEventListener('click', handleClick);
}

function handleClick(e){
    if(e.target.classList.contains('clickable')){
        $.ajax({
            type: 'get',
            url: e.target.dataset.url,
            data: {'q': ''},
            success: function(data) {
                showDataProducts(data);
            }
        });
    }
}

// ORDER BY
function orderByFunction(target) {
    let value = document.getElementById('search-input').value;
    let orderBy = target.value.split('?')[1].split('=')[1].split('&')[0];
    let orderType = target.value.split('?')[1].split('=')[2];
    
    let checkboxs = [];
    $('.product-filter').each(function(key, value) {
        if ($('#' + value.id).attr('checked')) {
            checkboxs.push(value.id);
        }
    });
    
    let priceMin = document.getElementById('priceMin').value;
    let priceMax = document.getElementById('priceMax').value;
    $.ajax({
        type: 'get',
        url: 'getDataProducts',
        data: {'q': value, 'orderby': orderBy, 'ordertype': orderType, 'checkboxs': checkboxs, 'priceMin': priceMin, 'priceMax': priceMax},
        success: function(data) {
            showDataProducts(data);
        }
    });
}

// FILTERS
function clearAllFilters() {
    $.ajax({
      type: 'get',
      url: 'getDataProducts',
      data: {'q': ''},
      success: function(data) {
          showDataProducts(data);
      }
    });
    
    $('#search-input').remove();
    $('#search-div').prepend('<input style="padding: 0 33px" type="search" id="search-input" name="q" placeholder="Enter your search key ..." value aria-label="Search" aria-describedby="search-addon">')
    
    document.getElementById('search-input').addEventListener('input', () => {
        let value = document.getElementById('search-input').value;
        $.ajax({
            type: 'get',
            url: 'getDataProducts',
            data: {'q': value},
            success: function(data) {
                showDataProducts(data);
            }
        });
    });
    
    $('.product-filter').each(function(key, value) {
        $("#" + value.id).attr('checked', function(index, attr) {
            if (attr == 'checked') {
                return null;
            }
        });
    });
}

function checkBox(target) {
    $("#" + target.id).attr('checked', function(index, attr) {
        return attr == 'checked' ? null : 'checked';
    });
    
    let value = document.getElementById('search-input').value;
    let checkboxs = [];
    
    $('.product-filter').each(function(key, value) {
        if ($('#' + value.id).attr('checked')) {
            checkboxs.push(value.id);
        }
    });
    
    let priceMin = document.getElementById('priceMin').value;
    let priceMax = document.getElementById('priceMax').value;
    $.ajax({
        type: 'get',
        url: 'getDataProducts',
        data: {'q': value, 'checkboxs': checkboxs, 'priceMin': priceMin, 'priceMax': priceMax},
        success: function(data) {
            showDataProducts(data);
        }
    });
}

function fetchPrice() {
    let value = document.getElementById('search-input').value;
    let checkboxs = [];
    $('.product-filter').each(function(key, value) {
        if ($('#' + value.id).attr('checked')) {
            checkboxs.push(value.id);
        }
    });
    
    let priceMin = document.getElementById('priceMin').value;
    let priceMax = document.getElementById('priceMax').value;
    $.ajax({
        type: 'get',
        url: 'getDataProducts',
        data: {'q': value, 'checkboxs': checkboxs, 'priceMin': priceMin, 'priceMax': priceMax},
        success: function(data) {
            showDataProducts(data);
        }
    });
}
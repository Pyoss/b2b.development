let checkLoad = function () {
    if ((window.innerHeight + window.scrollY) + 20 >= document.querySelector('main').offsetHeight) {
        product_list.getAjaxCatalog()
    }
}
var product_list = {
    element_num: 0,
    section_ids: [],
    display_properties: [],
    loading: false,
    tableDOM: document.querySelector('.product-table'),
    filterForm: BX('product-filter'),
    filter_props: {},
    offer_variant: 2,
    no_products: false,
    page: 'default',
    order_id: 0,

    validateCellInput: function (product_id, value) {
        this.loading = true
        let row = BX('product-' + product_id)
        let max = parseInt(row.querySelector('.stock-amount').dataset.quantity)
        if (!value) {
            value = 0
        }
        if (value > max) {
            value = max
        }
        if (value < 0) {
            value = 0
        }
        BX("product-" + product_id).querySelector('.quantity-input').value = value
        if (BX("product-" + product_id).querySelector('.quantity-input').value > 0) {
            if (product_list.page === 'default') {
                row.classList.add('add')
            }
            ajax_basket.update(product_id, value)
            this.updateSum(product_id, value)
        } else {
            ajax_basket.delete(product_id)
            if (product_list.page === 'default') {
                row.classList.remove('add')
                this.updateSum(product_id, value)
            } else if (product_list.page === 'basket') {
                this.deleteUpwards(row)
            }
        }
        this.loading = false
    },

    deleteUpwards(row) {
        let category_dom
        if (row.classList.contains('offer')) {
            let productBody = row.parentNode;
            if (row === productBody.firstChild && productBody.children.length > 1) {
                let picture = row.querySelector('.picture');
                BX.insertBefore(picture.cloneNode(true), productBody.children[1].querySelector('.name'))
            }
            row.remove()
            if (productBody.children.length === 0) {
                let product_dom = productBody.parentNode.parentNode.parentNode;
                category_dom = product_dom.parentNode
                product_dom.remove();
                this.element_num--
            }
        } else {
            category_dom = row.parentNode
            row.remove()
            this.element_num--
        }
        if (category_dom) {
            if (category_dom.children.length <= 1) {
                category_dom.remove()
            }
        }
        if (this.element_num === 0) {
            location.reload()
        }
    }
    ,

    updateSum: function (product_id, quantity) {
        let sumDOM = BX("product-" + product_id).querySelector('.sum')
        let price = parseInt(BX("product-" + product_id).querySelector('.price').innerHTML.replace(/\s/g, ''))
        sumDOM.innerHTML = quantity ? numberWithSpaces(parseInt(price * quantity)) : ''
    }
    ,

    deleteItem: function (product_id) {
        this.loading = true
        ajax_basket.delete(product_id)
        let row = BX('product-' + product_id)
        if (product_list.page === 'default') {
            row.classList.remove('add')
            BX("product-" + product_id).querySelector('.quantity-input').value = 0
            this.updateSum(product_id, 0)
        } else {
            this.deleteUpwards(row)
        }
        this.loading = false
    }
    ,

    createBinds: function () {
        BX.bindDelegate(this.tableDOM, 'click', {
            tagName: 'th',
            className: 'product-table__cell'
        }, function (event) {
            BX.toggleClass(BX('offers-' + event.target.dataset.productId), ["collapsed", ""])
            BX.toggleClass(event.target, ['opened', ""])
        })
        if (this.page !== 'order') {
            BX.bindDelegate(this.tableDOM, 'click', {className: 'amount-input__increment'}, function (event) {
                let current_value = BX("product-" + event.target.dataset.productId).querySelector('.quantity-input').value
                product_list.validateCellInput(event.target.dataset.productId, parseInt(current_value) + 1)
            })
            BX.bindDelegate(this.tableDOM, 'click', {className: 'amount-input__decrement'}, function (event) {
                let current_value = BX("product-" + event.target.dataset.productId).querySelector('.quantity-input').value
                product_list.validateCellInput(event.target.dataset.productId, parseInt(current_value) - 1)
            })
            BX.bindDelegate(this.tableDOM, 'change', {className: 'quantity-input'}, function (event) {
                let current_value = BX("product-" + event.target.dataset.productId).querySelector('.quantity-input').value
                product_list.validateCellInput(event.target.dataset.productId, current_value)
            })
            BX.bindDelegate(this.tableDOM, 'click', {className: 'delete'}, function (event) {
                product_list.deleteItem(event.target.dataset.productId)
            })

        }
        BX.bindDelegate(this.tableDOM, 'click', {className: 'picture', tagName: 'td'}, function (event) {
            let product_id = event.target.parentElement.id.split('-')[1]
            product_list.getAjaxDetail(product_id)
        })
        BX.bindDelegate(this.tableDOM, 'click', {className: 'name', tagName: 'td'}, function (event) {
            let product_id = event.target.parentElement.id.split('-')[1]
            product_list.getAjaxDetail(product_id)
        })

        /* Выбор раздела */
        if (product_list.page === 'default') {
            BX.bindDelegate(this.filterForm, 'click', {className: 'section-input'}, function (event) {
                let section_id = event.target.dataset.value
                let section_name = event.target.querySelector('span').textContent
                BX('section-input').value = section_id
                BX('current-select').textContent = section_name
                product_list.reloadAjax()
            })
        }
    }
    ,

    createSection: function (section) {
        let tbody = BX.create('tbody', {
            attrs: {id: 'section-' + section.ID},
        })
        let head = BX.create('tr', {
                prop: {className: 'product-table__row--header'},
                html: '<th class="product-table__cell section-cell" colspan=9>' + section.NAME + '<i class="product-table__arrow"></i>' + '</th>',
                events: {
                    click: function (event) {
                        BX.toggleClass(BX('section-' + section.ID), ['section-hidden', ''])
                        checkLoad()
                    }
                }
            }
        )
        BX.append(head, tbody)
        BX.append(tbody, this.tableDOM)
        return tbody;
    }
    ,

    addAmountCell: function (trow, product) {
        let amount_cell = BX.create('td', {
            html: '<div class="amount-input">' +
                '<i class="fa fa-trash delete" data-product-id="' + product.ID + '" ></i> ' +
                '<div data-product-id="' + product.ID + '" class="amount-input__decrement">-</div>' +
                '<input class="quantity-input" data-product-id="' + product.ID +
                '" type="number" value="' + product.BASKET_QUANTITY + '">' +
                '<div data-product-id="' + product.ID + '" class="amount-input__increment">+</div>' +
                '</div>',
            props: {className: 'product-table__cell'}
        })
        BX.append(amount_cell, trow)
    }
    ,

    addFixedAmountCell: function (trow, product) {
        let amount_cell = BX.create('td', {
            html: product.BASKET_QUANTITY + '&nbspшт.',
            props: {className: 'product-table__cell'}
        })
        BX.append(amount_cell, trow)
    }
    ,

    addSumCell: function (trow, product) {
        let price = parseInt(product.PRICE_3)
        let quantity = parseInt(product.BASKET_QUANTITY)
        BX.append(BX.create('td', {
            html: quantity ? parseInt(price * quantity) : '',
            props: {className: 'product-table__cell sum'}
        }), trow)
    }
    ,

    generateStockBlock: function (quantity) {
        let stockBlock = BX.create('div',
            {props: {className: 'stock-amount'}}
        )
        if (quantity > 50) {
            stockBlock.textContent = 'Много'
            stockBlock.classList.add('lots')
        } else if (quantity > 10) {
            stockBlock.textContent = 'Достаточно'
            stockBlock.classList.add('enough')
        } else if (quantity > 0) {
            stockBlock.textContent = 'Несколько'
            stockBlock.classList.add('few')
        } else {
            stockBlock.textContent = 'Закончился'
            stockBlock.classList.add('none')
        }
        stockBlock.dataset.quantity = quantity
        return stockBlock
    }
    ,

    addProduct: function (dstNode, product) {
        if (product.HIDDEN === 'hidden') {
            return
        }
        if (product.OFFERS === 'N') {
            this.addSimpleProduct(dstNode, product)
        } else {
            this.addProductWithOffers(dstNode, product)
        }
    }
    ,

    addSimpleProduct: function (dstNode, product, picture_set = undefined, offer = false) {
        let available = product.QUANTITY > 0
        let class_name = product.BASKET_QUANTITY > 0 && this.page !== 'order' ? 'product-table__row add' : 'product-table__row'
        if (offer) {
            class_name += ' offer'
        }
        let row = BX.create(
            'tr',
            {
                'props': {
                    className: class_name,
                    id: 'product-' + product.ID
                },
            }
        )

        if (!available) {
            row.classList.add('no-stock')
        }

        BX.append(BX.create('td', {
            text: product.PROPERTY_ARTNUMBER_VALUE,
            props: {className: 'product-table__cell article'}
        }), row)
        if (picture_set === 'none') {
        } else if (picture_set === undefined) {
            BX.append(BX.create('td', {
                html: '<img src="' + product.DETAIL_PICTURE.src + '">',
                props: {className: 'product-table__cell picture'}
            }), row)
        } else if (picture_set !== false) {
            BX.append(BX.create('td', {
                html: '<img style="max-height:' + (50 * parseInt(picture_set)) + 'px"  src="' + product.DETAIL_PICTURE_REAL.src + '">',
                props: {className: 'product-table__cell picture'},
                attrs: {rowspan: picture_set}
            }), row)
        }
        BX.append(BX.create('td', {text: product.NAME, props: {className: 'product-table__cell name'}}), row)


        if (this.page === 'default') {
            let saleBlock = '<div class="product-table__sale">'
            if (product.b2b_sale) {
                if (product.b2b_sale.includes('Хит')) {
                    saleBlock += '<div class="product-table__sale-image"> <img src="/src/figma-images/hit.png"></div>'
                }
                if (product.b2b_sale.includes('Новинка')) {

                    saleBlock += '<div class="product-table__sale-image"><img src="/src/figma-images/new.png"></div>'
                }
                if (product.b2b_sale.includes('Скидка')) {
                    saleBlock += '<div class="product-table__sale-image"><img src="/src/figma-images/sale.png"></div>'
                }
            }
            saleBlock += '</div>'
            BX.append(BX.create('td', {html: saleBlock, props: {className: 'product-table__cell sale'}}), row)

        }

        BX.append(BX.create('td', {
            html: available ? numberWithSpaces(parseInt(product.PRICE_3)) : 'Нет&nbspв&nbspналичии',
            props: {className: 'product-table__cell price'},
            attrs: available ?  null : {colspan: 10}
        }), row)
        BX.append(BX.create('td', {
            html: available ? numberWithSpaces(Math.ceil(parseInt(product.PRICE_2))) : '',
            props: {className: 'product-table__cell retail-price'}
        }), row)
        BX.append(BX.create('td', {
            children: [this.generateStockBlock(parseInt(product.QUANTITY))],
            props: {className: 'product-table__cell stock'},
            attrs: {style: 'display:none'}
        }), row)
        if (this.page === 'default') {
            BX.append(BX.create('td', {
                html: available ? (numberWithSpaces(parseInt(product.PRICE_2)
                    - parseInt(product.PRICE_3))) : '',
                props: {className: 'product-table__cell margin'},
                style: {color: '#FF6100'}
            }), row)
        }
        if (available) {
            if (this.page !== 'order') {
                this.addAmountCell(row, product)
            } else {

                this.addFixedAmountCell(row, product)
            }
            this.addSumCell(row, product)
        } else {
            BX.append(BX.create('td', {props: {className: 'product-table__cell'}}), row)
            BX.append(BX.create('td', {props: {className: 'product-table__cell'}}), row)
        }

        BX.append(row, dstNode)
    }
    ,

    addProductWithOffers: function (dstNode, product) {
        product.OFFERS = product.OFFERS.filter(function( offer ) {
            return offer.HIDDEN !== 'hidden';
        });
        console.log(product.OFFERS)
        if (product.OFFERS.length === 0 ){
            return;
        }
        let nested_table = BX.create('tr',
            {
                html: '<td colspan="11">' +
                    '<table class="offer-table" id="offers-table-' + product.ID + '">' +
                    '<thead>' +
                    '<tr>' +
                    '<th colspan="3" class="product-table__cell offer-head" data-product-id="' + product.ID + '">' +
                    product.NAME +
                    '</th>' +
                    (this.page !== 'order' ? '<th colspan="2" class="product-table__cell offer-head" data-product-id="' + product.ID + '"> от&nbsp' +
                        numberWithSpaces(parseInt(product.PRICE_3)) + '&nbsp₽' +
                        '</th>' : '') +
                    '<th colspan="4" class="product-table__cell offer-head"></th>' +
                    '</tr></thead><tbody id="offers-' + product.ID + '">' +
                    '</tbody></table></td>'
            })
        BX.append(nested_table, dstNode)
        let offer_table = BX('offers-table-' + product.ID)
        offer_table.insertBefore(BX('colgroup_fixed').cloneNode(true), offer_table.firstChild)

        let tbody = BX('offers-' + product.ID)
        let picture_set = product.OFFERS.length
        for (let offer of product.OFFERS) {
            if (this.offer_variant == 1) {
                picture_set = undefined
            }
            this.addSimpleProduct(tbody, offer, picture_set, true)
            picture_set = 'none'
        }
    }
    ,

    reloadAjax: function () {
        this.clear()
        this.getFilterValues()
        this.getAjaxCatalog()
    }
    ,

    getAjaxCatalog: function () {
        if (this.loading || this.no_products) {
            return
        }
        BX('loading').style.display = 'block';
        product_list.loading = true
        this.getFilterValues()
        let url = '/catalog/';
        if (url.indexOf('?') > -1) {
            url += '&AJAX=catalog'
        } else {
            url += '?AJAX=catalog'
        }
        if (this.filter_props) {
            for (let prop in this.filter_props) {
                url += '&' + prop + '=' + this.filter_props[prop]
            }
        }
        url += '&offset=' + this.element_num
        BX.ajax.get(
            url,
            this.processUpdate.bind(this)
        )
    }
    ,

    createDetailPopup: function (result) {
        let popup = BX.create(
            'div', {
                props: {
                    className: 'detail-popup'
                },
                attrs: {
                    id: 'popup',
                },
                html: '<div class="details">' +
                    '    <div class="details__image-wrapper">' +
                    `        <img src="${result.PICTURE_PATH}" class="details__image">` +
                    '    </div>' +
                    '    <div class="details__text-wrapper" id="details-wrapper">' +
                    `        <h2 class="details__name">${result.NAME}</h2>` +
                    '        <table><tbody id="details-char"></tbody></table>' +
                    '</div>' +
                    '</div>'
            }
        );
        let overlay = BX.create('div',
            {
                props: {className: 'popup-overlay'},
                children: [popup],
                events: {
                    click: function (event) {
                        if (event.target.className === 'popup-overlay') {
                            event.target.remove()
                        }
                    }
                }
            })

        document.querySelector('main').appendChild(overlay)

        for (let price_group of result.PRODUCT.PRICES) {
            if (price_group.CATALOG_GROUP_ID == '3') {
                document.getElementById('details-char').appendChild(
                    BX.create('tr', {
                            children: [
                                BX.create('td', {html: 'Цена'}),
                                BX.create('td', {html: result.OFFERS === 'Y' ? 'от ' + parseInt(price_group.PRICE) : parseInt(price_group.PRICE)})
                            ]
                        }
                    ))
            } else if (price_group.CATALOG_GROUP_ID == '2') {
                document.getElementById('details-char').appendChild(
                    BX.create('tr', {
                            children: [
                                BX.create('td', {html: 'РРЦ'}),
                                BX.create('td', {html: result.OFFERS === 'Y' ? 'от ' + parseInt(price_group.PRICE) : parseInt(price_group.PRICE)})
                            ]
                        }
                    ))
            }
        }
        for (let char_id in result.PROPS) {

            if (this.display_properties.includes(char_id) && char_id !== '34') {
                document.getElementById('details-char').appendChild(
                    BX.create('tr', {
                            children: [
                                BX.create('td', {html: result.PROPS[char_id].NAME}),
                                BX.create('td', {html: result.PROPS[char_id].VALUE})
                            ]
                        }
                    ))
            }
        }
        if (result.PROPS['34']) {
            let featureDoms = [BX.create('span', {props: {className: 'features__title'}, text: 'Особенности:'})]
            for (let feature of result.PROPS['34'].VALUE) {
                featureDoms.push(BX.create('div', {
                    props: {className: 'feature__wrapper'},
                    html:
                        '<div class="feature">' + feature +
                        '</div>'
                }),)
            }
            document.getElementById('details-wrapper').appendChild(
                BX.create('div', {
                        props: {className: 'features'},
                        children: featureDoms

                    }
                ))
        }
    }
    ,

    getAjaxDetail: function (product_id) {
        let url = '/catalog/';
        if (url.indexOf('?') > -1) {
            url += '&AJAX=details'
        } else {
            url += '?AJAX=details'
        }
        url += '&product_id=' + product_id
        BX.ajax.get(
            url,
            this.processDetail.bind(this)
        )
    }
    ,

    processDetail: function (result) {
        this.createDetailPopup(JSON.parse(result))
    }
    ,

    processUpdate: function (result) {
        let current_elem_n = this.element_num
        this.update(result)
        this.loading = false
        BX('loading').style.display = 'none';
        if (current_elem_n < this.element_num) {
            checkLoad()
        }
    }
    ,

    clear: function () {
        this.no_products = false
        while (this.tableDOM.children.length > 2) {
            this.tableDOM.removeChild(this.tableDOM.lastChild)
        }
        this.element_num = 0
        this.section_ids = []
    }
    ,

    getFilterValues: function () {
        if (product_list.page === 'default') {
            console.log(this.filterForm)
            if (this.filterForm.elements.sections.value) {
                this.filter_props.sections = this.filterForm.elements.sections.value
            } else {
                delete this.filter_props.sections
            }
            if (this.filterForm.elements.search.value) {
                this.filter_props.search = this.filterForm.elements.search.value
            } else {
                delete this.filter_props.search
            }
            if (this.filterForm.elements.BRAND.value !== 0) {
                this.filter_props.BRAND = this.filterForm.elements.BRAND.value
            } else {
                delete this.filter_props.BRAND
            }
            if (this.filterForm.elements.b2b_sale.value !== 0) {
                this.filter_props.b2b_sale = this.filterForm.elements.b2b_sale.value
            } else {
                delete this.filter_props.b2b_sale
            }
        } else if (product_list.page === 'basket') {

            this.filter_props.basket = 'Y'
        } else if (product_list.page === 'order') {

            this.filter_props.order = this.order_id
        }
    }
    ,

    update: function (result) {
        if (result.length < 10) {
            this.no_products = true;
            return;
        }
        let new_data = JSON.parse(result)
        this.no_products = true
        for (let section of new_data) {
            if (section.PRODUCTS && section.PRODUCTS.length > 0) {
                let tbody = null
                console.log(section)
                console.log(this.section_ids)
                if (!(this.section_ids.includes(section.ID))) {
                    tbody = this.createSection(section)
                } else {
                    tbody = BX("section-" + section.ID)
                }
                for (let product of section.PRODUCTS) {
                    if (this.no_products) {
                        this.no_products = false;
                    }
                    this.addProduct(BX('section-' + section.ID), product)
                    this.element_num++
                }
                if (tbody.children.length < 2) {
                    tbody.remove()
                } else {
                    this.section_ids.push(section.ID)

                }
            }
        }
        if (this.no_products) {
            BX('loading').display = 'none';
        }
    }
}

BX.ready(function () {
    product_list.getAjaxCatalog()
    product_list.createBinds()
    window.onscroll = function (ev) {
        checkLoad()
    };
    if (product_list.page === 'default') {
        product_list.filterForm.addEventListener("submit", function (evt) {
            product_list.reloadAjax.bind(product_list)
            evt.preventDefault();
        })
        BX('search-input').addEventListener('input',
            product_list.reloadAjax.bind(product_list)
        )
        BX('brands-input').addEventListener('change',
            product_list.reloadAjax.bind(product_list)
        )
        BX('sale-input').addEventListener('change',
            product_list.reloadAjax.bind(product_list)
        )
    }
})

function numberWithSpaces(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
var ajax_basket = {

    header_fixed: false,

    update: function(product_id, quantity){
        let arg_string = `&product_id=${product_id}&quantity=${quantity}`
        this.request('update', arg_string)

    },

    delete: function(product_id, quantity){
        let arg_string = `&product_id=${product_id}`
        this.request('delete', arg_string)

    },

    process: function (response){
        let result = JSON.parse(response);
        console.log(BX('header-quantity'))

        BX('header-quantity').innerText = result.quantity;
        if (BX("order-sum")){
            BX("order-sum").innerText = result.sum;
        }
        if (result.quantity > 0 && !this.header_fixed){
            this.fixHeader()
        } else if (result.quantity == 0 && this.header_fixed){
            this.releaseHeader()
        }
    },

    request: function (action, arg_string){
        let url = '/basket_ajax/?action=' + action + arg_string
        BX.ajax.get(
            url,
            this.process.bind(this)
        )
    },


    fixHeader: function (){
        BX('header').classList.add('fixed')
        this.header_fixed = true;
    },

    releaseHeader: function(){
        BX('header').classList.remove('fixed')
        this.header_fixed = false;
    },
}

if (BX('header-quantity').innerText != 0){
    ajax_basket.fixHeader()
}
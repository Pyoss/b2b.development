var dadata_api = {
    link:  '/local/classes/dada_test.php',
    searchByInn: function (inn, handler){
        BX.ajax.get(this.link + '?search_inn=' + inn,
            handler.bind(this))
    }
}

var api1C = {
    link: '/local/classes/test.php',
    getCompany: function (handler){
        BX.ajax.get(this.link + '?get_company=1',
            handler.bind(this))
    }
}
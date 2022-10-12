var api1C = {
    link: '/ajax/',
    getCompany: function (handler){
        BX.ajax.get(this.link + '?get_company=1',
            handler.bind(this))
    },

    findByINN: function (handler, inn){
        BX.ajax.get(this.link + '?get_by_inn=1&inn=' + inn,
            handler.bind(this))
    },

    mailExists: function (handler, mail){
        BX.ajax.get(this.link + '?mail_exists=1&mail=' + mail,
            handler.bind(this))},

    createClient: function (handler, mail, inn, name, phone){
        BX.ajax.get(this.link + '?create_client=1&mail=' + mail + '&inn=' + inn + '&name=' + name + '&phone=' + phone,
            handler.bind(this))}
}
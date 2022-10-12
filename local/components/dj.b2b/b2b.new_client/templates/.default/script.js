var new_client = {
    company_data: {},

    checkCompany: function (inn) {
        BX.cleanNode(BX('client-error'))
        BX('client-data').classList.add('hidden')
        BX('client-input').classList.add('hidden')
        BX('client-email').classList.add('hidden')
        BX('client-act_address').classList.add('hidden')
        BX('client-reg_address').classList.add('hidden')
        BX('client-phone').classList.add('hidden')
        BX('input-tel').value = ''
        BX('input-mail').value = ''
        BX('input-name').value = ''
        api1C.findByINN(new_client.showClient, inn)
    },

    showClient: function (result) {

        new_client.company_data = JSON.parse(result)
        if (new_client.company_data.error === true) {
            new_client.displayNotFound()
        } else {
            new_client.displayClient()
        }
    },

    createClient: function (handler){
        let inn = BX('client-inn').value
        let name = BX('input-name').value
        let phone = BX('input-tel').value
        let mail = BX('input-mail').value
        if (!inn || !mail) {

            BX('client-error').innerHTML = `<span>Почта и ИНН должны быть указаны</span>`
        }
        api1C.createClient(handler, mail, inn, name, phone)
    },

    displayNotFound: function () {

        let error = BX.create('div', {
            props: {
                className: 'error'
            },
            html: '<span>Клиент не найден в базе данных 1С</span>'
        })
        BX.append(error, BX('client-error'))
    },

    displayMailExists: function () {

        let error = BX.create('div', {
            props: {
                className: 'error'
            },
            html: '<span>Клиент с данным почтовым ящиком уже существует на сайте.' +
                ' При создании нового клиента история заказов сотрется из базы данных.</span>'
        })
        BX.append(error, BX('client-error'))
    },

    displayClient: function () {
        let name = new_client.company_data.name
        let email = new_client.company_data.mail[0]
        let act_address = new_client.company_data.act_address[0]
        let reg_address = new_client.company_data.reg_address[0]
        let phone = new_client.company_data.phone[0]
        BX('client-name').innerHTML = `<span>${name}</span>`
        if (email) {
            BX('client-email').classList.remove('hidden')
            BX('client-email').innerHTML = `<span>Почта: ${email}</span>`
            BX('input-mail').value = email
        }
        if (act_address) {
            BX('client-act_address').classList.remove('hidden')
            BX('client-act_address').innerHTML = `<span>Фактический адрес: ${act_address}</span>`
        }
        if (reg_address) {
            BX('client-reg_address').classList.remove('hidden')
            BX('client-reg_address').innerHTML = `<span>Юридический адрес: ${reg_address}</span>`
        }
        if (phone) {
            BX('client-phone').classList.remove('hidden')
            BX('client-phone').innerHTML = `<span>Телефон: ${phone}</span>`
            BX('input-tel').value = phone
        }
        BX('client-data').classList.remove('hidden')
        BX('input-name').value = name
        BX('client-input').classList.remove('hidden')
        if (email) {
            new_client.checkMailExists(email)
        }
    },

    checkMailExists: function (mail) {
        api1C.mailExists(function (result) {
            if (result) {
                new_client.displayMailExists()
            }
        }, mail)
    },

    showResult: function (result) {
        BX('client-error').innerHTML = `<span>${result}</span>`
    }
}

BX.ready(function () {
    BX('inn-search').addEventListener('click', function () {
        new_client.checkCompany(BX('client-inn').value)
    })

    BX('create-client').addEventListener('click', function () {
        new_client.createClient(new_client.showResult)
    })
})
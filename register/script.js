BX.ready(function () {

    BX.bind(BX('inn-search'), 'click', function () {
        hide_suggestions()
        let inn_value = BX('inn').value

        // Получаем предложения через api
        if (inn_value) {
            dadata_api.searchByInn(inn_value, function (json_result) {
                dadata_api.result = JSON.parse(json_result)
                create_suggestions(inn_value)
            })
        } else {
            create_suggestions()
        }
    })

    BX.bind(document, 'click', function (){
        hide_suggestions()
    })

    function create_suggestions(inn_value){

        // Создаем новый элемент предложений
        let suggestion_box = BX.create(
            'div', {
                props: {className: 'inn-suggestions', id: 'inn-suggestions'}
            }
        )

        // формируем DOM предложений
        if (!inn_value || !dadata_api.result.suggestions.length) {
            let suggestionDOM = BX.create('div', {
                props: {className: 'inn-suggestion', id: 0},
                text: 'Ничего не найдено'
            })
            suggestion_box.appendChild(suggestionDOM)
        } else {
            for (let i=0; i < dadata_api.result.suggestions.length; i++) {
                let suggestionDOM = BX.create('div',
                    {
                        props: {className: 'inn-suggestion', id: 'inn-suggestion-' + i},
                        text: dadata_api.result.suggestions[i].value,
                        events: {
                            click: function (event){
                                fill_registration_inputs(dadata_api.result.suggestions[i])
                            }
                        }
                    })
                suggestion_box.appendChild(suggestionDOM)
            }
        }

        // Вставляем элемент в древо
        BX('inn-search').after(suggestion_box)
    }

    function hide_suggestions(){

        //Удаляем активный элемент предложений если он существует
        if (BX('inn-suggestions')){
            BX('inn-suggestions').remove()
        }

    }

    function fill_registration_inputs(company){
        console.log(company)
        let address = company.data.address.unrestricted_value
        let ogrn = company.data.ogrn
        let name = company.data.name.full
        if (company.data.opf.short === 'ИП' ){
            BX('IP').checked = true
        } else {
            BX('OOO').checked = true
        }
        BX('ogrn').value = ogrn
        BX('company-name').value = name
        BX('act-address').value = address
        BX('reg-address').value = address
    }
})
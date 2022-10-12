BX.ready(function () {
    api1C.getCompany(fill_up)
    document.querySelector('#profile-edit').addEventListener('click', function (){
            if (!form_active){
                form_active = true;
                convert_to_input()
            }
        }
    )
})
var form_active = false

function fill_up(result){
    let json = JSON.parse(result)
    let profile_company = BX('profile-company')
    let company_table = BX('company-table')
    let table = form_action_table(json)
    profile_company.classList.remove('loading')
    company_table.classList.remove('loading')
    profile_company.textContent = json.name
    company_table.appendChild(table);
}

function form_action_table(json){
    console.log(json)
    let rows = []
    if (json.name){
        rows.push(BX.create('tr', {
            html: `<td>Название:</td><td>${json.name}</td>`
        }))
    }
    if (json.inn){
        rows.push(BX.create('tr', {
            html: `<td>ИНН:</td><td>${json.inn}</td>`
        }))
    }
    if (json.bank_req && json.bank_req.bik){
        rows.push(BX.create('tr', {
            html: `<td>БИК:</td><td>${json.bank_req.bik}</td>`
        }))
    }
    if (json.bank_req && json.bank_req.kor){
        rows.push(BX.create('tr', {
            html: `<td>К/С:</td><td>${json.bank_req.kor}</td>`
        }))
    }
    if (json.bank_req && json.bank_req.num){
        rows.push(BX.create('tr', {
            html: `<td>Р/С:</td><td>${json.bank_req.num}</td>`
        }))
    }
    if (json.act_address && json.act_address.length > 0){
        rows.push(BX.create('tr', {
            html:`<td>Фактический адрес:</td><td>${json.act_address}</td>`
        }))
    }
    if (json.reg_address && json.reg_address.length > 0){
        rows.push(BX.create('tr', {html: `<td>Юридический адрес:</td><td>${json.act_address}</td>`}))
    }
    let tbody = BX.create('tbody', {children: rows})
    return BX.create('table', {children: [tbody]})
}

function convert_to_input(){
    let node = BX('profile-form').cloneNode(true)
    BX('profile-form').remove()
    let form = BX.create('form', {
        children: [node],
        props: {method: 'POST'}
    })
    document.querySelector('.profile-wrapper').appendChild(form)
    switch_to_input(BX('profile-name'))
    switch_to_input(BX('profile-phone'))
    switch_to_input(BX('profile-email'))
    BX('profile-edit').textContent = 'Принять'
}

function switch_to_input(DOM){
    let _default = DOM.textContent
    let _id = DOM.id
    DOM.innerHTML = `<input type=text name=${_id} id=${_id} value='${_default}'>`
}


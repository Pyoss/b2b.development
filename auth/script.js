BX.ready(function (){

BX.bind(BX('password-reveal'), 'click', function (){
    BX.toggleClass(BX('password-reveal'), ['fa-eye', "fa-eye-slash"])
    if(BX('password').type === 'password'){
        BX('password').type = 'text'
    } else {
        BX('password').type = 'password'
    }
})})
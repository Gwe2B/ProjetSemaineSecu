const mailRe  = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
      phoneRe = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;

let nomInput    = document.getElementById('nom'),
    prenomInput = document.getElementById('prenom'),
    mailInput   = document.getElementById('mail'),
    telInput    = document.getElementById('tel'),
    formulaire  = document.getElementsByTagName('form')[0];

nomInput.addEventListener('input', function() {
    if(this.value.length <= 0) {
        this.parentElement.classList.add('error');
    } else {
        this.parentElement.classList.remove('error');
    }
});

prenomInput.addEventListener('input', function() {
    if(this.value.length <= 0) {
        this.parentElement.classList.add('error');
    } else {
        this.parentElement.classList.remove('error');
    }
});

mailInput.addEventListener('input', function() {
    if(!mailRe.test(this.value)) {
        this.parentElement.classList.add('error');
    } else {
        this.parentElement.classList.remove('error');
    }
});

telInput.addEventListener('input', function() {
    if(!phoneRe.test(this.value) && this.value.length > 0) {
        this.parentElement.classList.add('error');
    } else {
        this.parentElement.classList.remove('error');
    }
});

function validateForm() {
    let result = false;

    if(nomInput.value.length <= 0) {
        nomInput.parentElement.classList.add('error');
    } else if(prenomInput.value.length <= 0) {
        prenomInput.parentElement.classList.add('error');
    } else if(!mailRe.test(mailInput.value)) {
        mailInput.parentElement.classList.add('error');
    } else if(!phoneRe.test(telInput.value) && telInput.value.length > 0) {
        telInput.parentElement.classList.add('error');
    } else {
        result = true;
    }

    return result;
};
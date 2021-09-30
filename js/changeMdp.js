"use strict";

const mdpRe = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{8,15})$/;

let newMdpIn = document.getElementById("newMdp"),
    confirmIn = document.getElementById("confirm");

newMdpIn.addEventListener('input', function() {
    if(!mdpRe.test(this.value)) {
        this.invalid = true;
        this.parentElement.classList.add('error');
    } else {
        this.invalid = false;
        this.parentElement.classList.remove('error');
    }
});

confirmIn.addEventListener('input', function() {
    if(this.value.lenght != newMdpIn.value.lenght) {
        this.invalid = true;
        this.parentElement.classList.add('error');
    } else {
        this.invalid = false;
        this.parentElement.classList.remove('error');
    }
});

function validateForm() {
    let result = false;
    if(!mdpRe.test(newMdpIn.value)) {
        newMdpIn.invalid = true;
        newMdpIn.parentElement.classList.add('error');
    } else if(confirmIn.value.lenght != newMdpIn.value.lenght) {
        confirmIn.invalid = true;
        confirmIn.parentElement.classList.add('error');
    } else {
        result = true;
    }

    return result;
}
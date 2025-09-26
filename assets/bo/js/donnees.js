
//opening and closing the warnings

let openDeleteWarning = document.getElementById('openDeleteWarning');
let deleteWarning = document.getElementById('deleteWarning');
let closeDeleteWarning = document.getElementById('closeDeleteWarning');

let openTransferWarning = document.getElementById('openTransferWarning');
let transferWarning = document.getElementById('transferWarning');
let closeTransferWarning = document.getElementById('closeTransferWarning');

openDeleteWarning.onclick = function(){
    if(!transferWarning.classList.contains('active')){
        deleteWarning.classList.add('active');
    }
}
closeDeleteWarning.onclick = function(){
    deleteWarning.classList.remove('active');
}

openTransferWarning.onclick = function(){
    if(!deleteWarning.classList.contains('active')){
        transferWarning.classList.add('active');
    }
}
closeTransferWarning.onclick = function(){
    transferWarning.classList.remove('active');
}

//visibility toggles for editing names in donnees>...>id

let checkboxHideGenre = document.getElementById('checkbox-hide-genre');
let checkboxHideSerie = document.getElementById('checkbox-hide-serie');
let checkboxHideLangue = document.getElementById('checkbox-hide-langue');

let formGenreName = document.getElementById('form-genre-name');
let formSerieName = document.getElementById('form-serie-name');
let formLangueName = document.getElementById('form-langue-name');


if(checkboxHideGenre && formGenreName){
    checkboxHideGenre.onclick = function(){
        if(checkboxHideGenre.checked === true){
            formGenreName.classList.add('active');
        }else{
            formGenreName.classList.remove('active');
        }
    }
}
if(checkboxHideSerie && formSerieName){
    checkboxHideSerie.onclick = function(){
        if(checkboxHideSerie.checked === true){
            formSerieName.classList.add('active');
        }else{
            formSerieName.classList.remove('active');
        }
    }
}

if(checkboxHideLangue && formLangueName){
    checkboxHideLangue.onclick = function(){
        if(checkboxHideLangue.checked === true){
            formLangueName.classList.add('active');
        }else{
            formLangueName.classList.remove('active');
        }
    }
}


require('./bootstrap');

function modal(modalID, id, callback) {
    if (typeof id !== 'undefined') {
        // id nya untuk id item dari data
        // masukin function nya pakai callback ini biar bisa flexibel
        callback(id);
    }
    let modalid = document.getElementById(modalID)
    modalid.classList.toggle('hidden');
    let modalcontent = document.getElementById(modalID + '-content')
    modalcontent.classList.toggle('hidden');
}

window.modal = modal;
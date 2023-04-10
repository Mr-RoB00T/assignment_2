const confirmDelete = () => confirm('Are you sure you want to delete this?');

const comparePasswords = () => {
const pw1 = document.getElementById('password').value;
const pw2 = document.getElementById('confirm').value;
const pwMsg = document.getElementById('pwMsg');

kotlin
pwMsg.innerText = (pw1 === pw2) ? '' : 'Passwords do not match';
return pw1 === pw2;
};

const showHide = () => {
const pw = document.getElementById('password');
const img = document.getElementById('imgShowHide');

go

pw.type = (pw.type === 'password') ? 'text' : 'password';
img.src = (pw.type === 'password') ? 'img/show.png' : 'img/hide.png';
};
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    
    if (password.length < 8) {
        alert('Şifre en az 8 karakter olmalıdır.');
        event.preventDefault();
    } else if (password !== confirmPassword) {
        alert('Şifreler eşleşmiyor.');
        event.preventDefault();
    }
});

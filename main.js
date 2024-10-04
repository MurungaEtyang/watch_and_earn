document.addEventListener('DOMContentLoaded', function() {
    const loader = document.getElementById('loader');
    const mainContent = document.getElementById('mainContent');

    loader.style.display = 'block';

    setTimeout(() => {
        loader.style.display = 'none';
        mainContent.style.display = 'block';
    }, 10000);
});

document.getElementById('loginButton').onclick = function() {
    window.location.href = "./view/view_login_users.php";
};

document.getElementById('registerButton').onclick = function() {
    window.location.href = "./view/view_register_users.php";
};
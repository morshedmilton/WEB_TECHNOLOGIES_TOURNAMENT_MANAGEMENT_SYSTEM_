function validateSignup() {
    let name = document.getElementById('name').value;
    let username = document.getElementById('username').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;
    let errorMsg = document.getElementById('jsError');

    if (name == "" || username == "" || email == "" || password == "" || confirmPassword == "") {
        errorMsg.innerHTML = "All fields are required!";
        return false;
    }
    if (password.length < 8) {
        errorMsg.innerHTML = "Password must be at least 8 characters long!";
        return false;
    }
    if (password !== confirmPassword) {
        errorMsg.innerHTML = "Passwords do not match!";
        return false;
    }
    errorMsg.innerHTML = "";
    return true;
}

function validateLogin() {
    let username = document.getElementById('username').value;
    let password = document.getElementById('password').value;
    let errorMsg = document.getElementById('jsError');

    if (username == "" || password == "") {
        errorMsg.innerHTML = "Please enter both username and password!";
        return false;
    }
    errorMsg.innerHTML = "";
    return true;
}

function validateChangePassword() {
    let current = document.getElementById('currentPassword').value;
    let newPass = document.getElementById('newPassword').value;
    let confirmPass = document.getElementById('confirmNewPassword').value;
    let errorMsg = document.getElementById('jsError');

    if (current == "" || newPass == "" || confirmPass == "") {
        errorMsg.innerHTML = "All fields are required!";
        return false;
    }
    if (newPass.length < 8) {
        errorMsg.innerHTML = "New password must be at least 8 characters!";
        return false;
    }
    if (newPass !== confirmPass) {
        errorMsg.innerHTML = "New passwords do not match!";
        return false;
    }
    return true;
}

function validateTournament() {
    let title = document.getElementById('title').value;
    let category = document.getElementById('category').value;
    let content = document.getElementById('content').value;
    let errorMsg = document.getElementById('jsError');

    if (title == "" || category == "" || content == "") {
        errorMsg.innerHTML = "Title, Category and Description are required!";
        return false;
    }
    return true;
}

function confirmDelete(id) {
    let result = confirm("Are you sure you want to delete this tournament? This action cannot be undone.");
    if (result) {
        window.location.href = "../controller/deleteTournament.php?id=" + id;
    }
}

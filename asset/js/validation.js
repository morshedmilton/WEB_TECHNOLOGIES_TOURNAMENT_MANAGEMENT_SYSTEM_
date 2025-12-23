// Registration form validation
function validateSignup() {
    let name = document.getElementById('name').value;
    let username = document.getElementById('username').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;
    let errorMsg = document.getElementById('jsError');

    // Checking for empty fields
    if (name == "" || username == "" || email == "" || password == "" || confirmPassword == "") {
        errorMsg.innerHTML = "All fields are required!";
        return false;
    }

    // Password length check (8 characters as per PRD)
    if (password.length < 8) {
        errorMsg.innerHTML = "Password must be at least 8 characters long!";
        return false;
    }

    // Password matching check
    if (password !== confirmPassword) {
        errorMsg.innerHTML = "Passwords do not match!";
        return false;
    }

    errorMsg.innerHTML = "";
    return true;
}

// Login form validation
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
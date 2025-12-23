// রেজিস্ট্রেশন ফর্ম ভ্যালিডেশন
function validateSignup() {
    let name = document.getElementById('name').value;
    let username = document.getElementById('username').value;
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirmPassword').value;
    let errorMsg = document.getElementById('jsError');

    // খালি ফিল্ড চেক করা
    if (name == "" || username == "" || email == "" || password == "" || confirmPassword == "") {
        errorMsg.innerHTML = "All fields are required!";
        return false;
    }

    // পাসওয়ার্ডের দৈর্ঘ্য চেক (PRD অনুযায়ী ৮ অক্ষর)
    if (password.length < 8) {
        errorMsg.innerHTML = "Password must be at least 8 characters long!";
        return false;
    }

    // পাসওয়ার্ড ম্যাচিং চেক
    if (password !== confirmPassword) {
        errorMsg.innerHTML = "Passwords do not match!";
        return false;
    }

    errorMsg.innerHTML = "";
    return true;
}

// লগইন ফর্ম ভ্যালিডেশন
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
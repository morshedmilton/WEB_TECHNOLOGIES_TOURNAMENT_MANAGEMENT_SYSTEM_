



// Theme Toggle Script - Dark/Light Mode

function toggleTheme() {
    let body = document.body;
    let currentTheme = localStorage.getItem('theme') || 'light';

    if (currentTheme === 'light') {
        applyDarkTheme();
        localStorage.setItem('theme', 'dark');
    } else {
        applyLightTheme();
        localStorage.setItem('theme', 'light');
    }
}

function applyDarkTheme() {
    document.body.style.backgroundColor = '#1a1a2e';
    document.body.style.color = '#eee';

    // Style all fieldsets
    let fieldsets = document.querySelectorAll('fieldset');
    fieldsets.forEach(function (fs) {
        fs.style.backgroundColor = '#16213e';
        fs.style.borderColor = '#0f3460';
        fs.style.color = '#eee';
    });


    
    // Style all tables
    let tables = document.querySelectorAll('table');
    tables.forEach(function (t) {
        t.style.backgroundColor = '#16213e';
        t.style.color = '#eee';
    });


    
    // Style all inputs
    let inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(function (inp) {
        inp.style.backgroundColor = '#0f3460';
        inp.style.color = '#eee';
        inp.style.borderColor = '#e94560';
    });

    
    // Style all links
    let links = document.querySelectorAll('a');
    links.forEach(function (a) {
        a.style.color = '#e94560';
    });
}


function applyLightTheme() {
    document.body.style.backgroundColor = '';
    document.body.style.color = '';

    // Reset fieldsets
    let fieldsets = document.querySelectorAll('fieldset');
    fieldsets.forEach(function (fs) {
        fs.style.backgroundColor = '';
        fs.style.borderColor = '';
        fs.style.color = '';
    });


    
    // Reset tables
    let tables = document.querySelectorAll('table');
    tables.forEach(function (t) {
        t.style.backgroundColor = '';
        t.style.color = '';
    });

    // Reset inputs
    let inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(function (inp) {
        inp.style.backgroundColor = '';
        inp.style.color = '';
        inp.style.borderColor = '';
    });


    
    // Reset links
    let links = document.querySelectorAll('a');
    links.forEach(function (a) {
        a.style.color = '';
    });
}

// Apply saved theme on page load
document.addEventListener('DOMContentLoaded', function () {
    let savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        applyDarkTheme();
    }
});

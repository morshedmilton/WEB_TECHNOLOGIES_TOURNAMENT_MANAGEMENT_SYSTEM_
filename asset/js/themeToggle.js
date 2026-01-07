function toggleTheme() {
    let currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'light') {
        applyDarkTheme(); localStorage.setItem('theme', 'dark');
    } else { applyLightTheme(); localStorage.setItem('theme', 'light'); }
}
function applyDarkTheme() {
    document.body.style.backgroundColor = '#1a1a2e'; document.body.style.color = '#eee';
    document.querySelectorAll('fieldset, table').forEach(el => { el.style.backgroundColor = '#16213e'; el.style.color = '#eee'; });
}
function applyLightTheme() {
    document.body.style.backgroundColor = ''; document.body.style.color = '';
    document.querySelectorAll('fieldset, table').forEach(el => { el.style.backgroundColor = ''; el.style.color = ''; });
}
document.addEventListener('DOMContentLoaded', () => { if (localStorage.getItem('theme') === 'dark') applyDarkTheme(); });

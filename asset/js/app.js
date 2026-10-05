/* TourneyHub front-end: theme, nav, toasts, dialogs, tabs, live search, validation + motion layer. */
(function () {
    'use strict';

    var root = document.documentElement;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var started = Date.now();

    /* ---------- Splash screen (first visit per session) ---------- */
    var splash = document.getElementById('splash');
    function hideSplash() {
        if (!splash || !root.classList.contains('splash')) return;
        var wait = Math.max(0, 900 - (Date.now() - started));
        setTimeout(function () {
            splash.classList.add('hide');
            try { sessionStorage.setItem('splash', '1'); } catch (e) { /* ignore */ }
            setTimeout(function () { root.classList.remove('splash'); }, 600);
        }, wait);
    }
    if (document.readyState === 'complete') hideSplash(); else window.addEventListener('load', hideSplash);
    setTimeout(hideSplash, 4000); // never trap the user behind the splash

    /* ---------- Top progress bar ---------- */
    var bar = document.getElementById('topbar');
    function barStart() {
        if (!bar || reduceMotion) return;
        bar.className = '';
        void bar.offsetWidth;
        bar.className = 'run';
    }
    function barDone() {
        if (!bar) return;
        bar.className = 'done';
        setTimeout(function () { bar.className = ''; }, 700);
    }
    window.addEventListener('pageshow', function () { barDone(); var m = document.querySelector('main'); if (m) m.classList.remove('leaving'); });

    document.addEventListener('click', function (ev) {
        var a = ev.target.closest && ev.target.closest('a[href]');
        if (!a || ev.defaultPrevented || ev.metaKey || ev.ctrlKey || ev.shiftKey || a.target === '_blank' || a.hasAttribute('download')) return;
        var url;
        try { url = new URL(a.href, location.href); } catch (e) { return; }
        if (url.origin !== location.origin || url.protocol.indexOf('http') !== 0) return;
        if (url.pathname === location.pathname && url.search === location.search && url.hash) return;
        if (a.dataset.filter) return;
        barStart();
        var main = document.querySelector('main');
        if (main && !reduceMotion) main.classList.add('leaving');
    });

    /* ---------- Theme ---------- */
    var themeBtn = document.getElementById('themeToggle');
    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            var next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            try { localStorage.setItem('theme', next); } catch (e) { /* private mode */ }
        });
    }

    /* ---------- Nav: mobile menu + user dropdown ---------- */
    var nav = document.querySelector('.nav');
    var menuBtn = document.getElementById('menuBtn');
    if (menuBtn && nav) menuBtn.addEventListener('click', function () { nav.classList.toggle('open'); });
    document.querySelectorAll('.dropdown-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function (ev) { ev.stopPropagation(); toggle.parentElement.classList.toggle('open'); });
    });
    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown.open').forEach(function (d) { d.classList.remove('open'); });
    });

    /* ---------- Toasts ---------- */
    document.querySelectorAll('.toast').forEach(function (toast) {
        var close = function () { toast.classList.add('out'); setTimeout(function () { toast.remove(); }, 300); };
        var x = toast.querySelector('.x');
        if (x) x.addEventListener('click', close);
        if (toast.parentElement && toast.parentElement.classList.contains('toasts')) setTimeout(close, 5500);
    });

    /* ---------- Scroll reveal ---------- */
    var REVEAL = 'main .page-head, main .card, main .match, main .tabs, main .chips, main .review, main .crumbs, .feature, .cta, .section-title, .hero-card, .stats-strip .card';
    var io = ('IntersectionObserver' in window && !reduceMotion) ? new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            io.unobserve(el);
            el.classList.add('in');
            countUp(el);
            // Drop the helper classes afterwards so hover transitions are not delayed.
            setTimeout(function () { el.classList.remove('reveal', 'in'); el.style.removeProperty('--i'); }, 1100);
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -6% 0px' }) : null;

    function revealAll(scope) {
        var nodes = (scope || document).querySelectorAll(REVEAL);
        var seen = new Map();
        nodes.forEach(function (el) {
            if (el.classList.contains('reveal') || el.closest('.toasts') || el.closest('dialog')) return;
            if (!io) { countUp(el); return; }
            var parent = el.parentElement;
            var idx = seen.get(parent) || 0;
            seen.set(parent, idx + 1);
            el.style.setProperty('--i', Math.min(idx, 8));
            el.classList.add('reveal');
            io.observe(el);
        });
    }

    /* ---------- Count-up numbers ---------- */
    function countUp(scope) {
        scope.querySelectorAll('.stat-value, .review-score .big').forEach(function (el) {
            if (el.dataset.counted) return;
            el.dataset.counted = '1';
            var text = el.textContent.trim();
            var isInt = /^\d+$/.test(text);
            var isDec = /^\d+\.\d$/.test(text);
            if (reduceMotion || !(isInt || isDec)) return;
            var target = parseFloat(text), dur = 1100, t0 = null;
            function step(t) {
                if (t0 === null) t0 = t;
                var p = Math.min(1, (t - t0) / dur);
                var eased = 1 - Math.pow(1 - p, 3);
                el.textContent = isDec ? (target * eased).toFixed(1) : Math.round(target * eased);
                if (p < 1) requestAnimationFrame(step); else el.textContent = text;
            }
            el.textContent = isDec ? '0.0' : '0';
            requestAnimationFrame(step);
        });
        if (scope.matches && scope.matches('.stat-value, .review-score .big')) { /* handled via parent */ }
    }
    revealAll(document);
    // Stat tiles are not themselves in REVEAL when nested; make sure numbers still animate.
    document.querySelectorAll('.stat-value').forEach(function (el) {
        if (!el.closest('.reveal')) countUp(el.parentElement || el);
    });

    /* ---------- Confirm dialog for destructive forms ---------- */
    var dialog = document.getElementById('confirmDialog');
    if (dialog && typeof dialog.showModal === 'function') {
        var pendingForm = null;
        document.querySelectorAll('form[data-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (ev) {
                if (form.dataset.confirmed === '1') return;
                ev.preventDefault();
                ev.stopImmediatePropagation();
                pendingForm = form;
                dialog.querySelector('[data-msg]').textContent = form.dataset.confirm;
                dialog.showModal();
            }, true);
        });
        dialog.querySelector('[data-cancel]').addEventListener('click', function () { dialog.close(); pendingForm = null; });
        dialog.querySelector('[data-ok]').addEventListener('click', function () {
            if (!pendingForm) return;
            pendingForm.dataset.confirmed = '1';
            var okBtn = dialog.querySelector('[data-ok]');
            okBtn.classList.add('loading');
            barStart();
            pendingForm.submit();
        });
    }

    /* ---------- Tabs ---------- */
    document.querySelectorAll('.tabs').forEach(function (tabs) {
        tabs.querySelectorAll('.tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.querySelectorAll('.tab').forEach(function (t) { t.classList.remove('active'); });
                document.querySelectorAll('.tab-panel').forEach(function (p) { p.classList.remove('active'); });
                tab.classList.add('active');
                var panel = document.getElementById(tab.dataset.tab);
                if (panel) { panel.classList.add('active'); revealAll(panel); }
                try { history.replaceState(null, '', '#' + tab.dataset.tab); } catch (e) { /* ignore */ }
            });
        });
        var hash = location.hash.replace('#', '');
        var initial = hash && tabs.querySelector('[data-tab="' + hash + '"]');
        if (initial) initial.click();
    });

    /* ---------- Live tournament search (AJAX) with skeleton loader ---------- */
    var search = document.getElementById('liveSearch');
    var results = document.getElementById('results');
    function skeletons(n) {
        var card = '<div class="card t-card sk"><div class="sk-banner"></div><div class="body">' +
            '<div class="sk-line" style="width:70%;height:16px"></div><div class="sk-line" style="width:90%"></div>' +
            '<div class="sk-line" style="width:55%"></div><div class="sk-pill"></div></div></div>';
        var out = '<div class="grid grid-3">';
        for (var i = 0; i < n; i++) out += card;
        return out + '</div>';
    }
    if (search && results) {
        var state = { category: search.dataset.category || '', status: search.dataset.status || '' };
        var timer = null, seq = 0;
        var run = function () {
            var mine = ++seq;
            var t0 = Date.now();
            var params = new URLSearchParams({ partial: '1', q: search.value.trim(), category: state.category, status: state.status });
            results.innerHTML = '<p class="muted small" style="margin-bottom:14px"><span class="inline-loader"></span>&nbsp; Searching…</p>' + skeletons(6);
            fetch('tournamentList.php?' + params.toString(), { credentials: 'same-origin' })
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    var delay = Math.max(0, 380 - (Date.now() - t0)); // let the skeleton register
                    setTimeout(function () {
                        if (mine !== seq) return;
                        results.innerHTML = html;
                        results.querySelectorAll('.t-card').forEach(function (c, i) {
                            c.style.animation = 'fade-up .5s cubic-bezier(.2,.7,.2,1) both';
                            c.style.animationDelay = Math.min(i, 8) * 60 + 'ms';
                        });
                    }, delay);
                })
                .catch(function () { results.innerHTML = '<p class="muted center">Could not load results. Check your connection.</p>'; });
            var shown = new URLSearchParams();
            if (search.value.trim()) shown.set('q', search.value.trim());
            if (state.category) shown.set('category', state.category);
            if (state.status) shown.set('status', state.status);
            try { history.replaceState(null, '', location.pathname + (shown.toString() ? '?' + shown.toString() : '')); } catch (e) { /* ignore */ }
        };
        search.addEventListener('input', function () { clearTimeout(timer); timer = setTimeout(run, 260); });
        document.querySelectorAll('.chip[data-filter]').forEach(function (chip) {
            chip.addEventListener('click', function (ev) {
                ev.preventDefault();
                var key = chip.dataset.filter;
                document.querySelectorAll('.chip[data-filter="' + key + '"]').forEach(function (c) { c.classList.remove('active'); });
                chip.classList.add('active');
                state[key] = chip.dataset.value;
                run();
            });
        });
    }

    /* ---------- Demo-account quick fill (login) ---------- */
    document.querySelectorAll('[data-demo-user]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('username').value = btn.dataset.demoUser;
            document.getElementById('password').value = btn.dataset.demoPass;
            btn.classList.add('loading');
            barStart();
            document.getElementById('loginForm').submit();
        });
    });

    /* ---------- Client-side validation (server re-validates everything) ---------- */
    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        form.addEventListener('submit', function (ev) {
            var err = form.querySelector('.form-error');
            var get = function (id) { var el = form.querySelector('#' + id); return el ? el.value.trim() : ''; };
            var msg = '';
            switch (form.dataset.validate) {
                case 'login':
                    if (!get('username') || !get('password')) msg = 'Please enter both username and password.';
                    break;
                case 'signup':
                    if (!get('name') || !get('username') || !get('email') || !get('password') || !get('confirmPassword')) msg = 'All fields are required.';
                    else if (!/^[A-Za-z0-9._-]{3,30}$/.test(get('username'))) msg = 'Username: 3-30 letters, numbers, dot, dash or underscore.';
                    else if (get('password').length < 8) msg = 'Password must be at least 8 characters.';
                    else if (get('password') !== get('confirmPassword')) msg = 'Passwords do not match.';
                    break;
                case 'password':
                    if (!get('currentPassword') || !get('newPassword') || !get('confirmNewPassword')) msg = 'All fields are required.';
                    else if (get('newPassword').length < 8) msg = 'New password must be at least 8 characters.';
                    else if (get('newPassword') !== get('confirmNewPassword')) msg = 'New passwords do not match.';
                    break;
                case 'tournament':
                    if (!get('title') || !get('description')) msg = 'Title and description are required.';
                    break;
            }
            if (msg) {
                ev.preventDefault();
                if (err) { err.textContent = msg; err.style.animation = 'none'; void err.offsetWidth; err.style.animation = 'fade-up .3s ease both'; }
            }
        });
    });

    /* ---------- Loading state on every submitting form (runs after validation) ---------- */
    document.addEventListener('submit', function (ev) {
        if (ev.defaultPrevented) return;
        var form = ev.target;
        if (form.dataset.noLoading !== undefined || form.dataset.submitting === '1') {
            if (form.dataset.submitting === '1') ev.preventDefault(); // block double submits
            return;
        }
        form.dataset.submitting = '1';
        var btn = ev.submitter || form.querySelector('button[type="submit"], input[type="submit"]');
        if (btn && btn.classList) btn.classList.add('loading');
        barStart();
        setTimeout(function () { form.dataset.submitting = ''; if (btn && btn.classList) btn.classList.remove('loading'); barDone(); }, 15000);
    });
})();

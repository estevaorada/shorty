async function handleLogin(event) {
    event.preventDefault();
    const form = event.currentTarget;
    const loginButton = document.getElementById('login-button');
    loginButton.disabled = true;
    loginButton.textContent = 'Entrando...';

    try {
        const response = await fetch('api/user_login.php', {
            method: 'POST',
            body: new FormData(form),
            credentials: 'same-origin'
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Não foi possível realizar o login.');
        }

        UIkit.notification({
            message: result.message,
            status: 'success',
            pos: 'top-center',
            timeout: 1500
        });

        window.setTimeout(() => {
            window.location.href = 'panel.php';
        }, 1500);
    } catch (error) {
        UIkit.notification({
            message: error.message || 'Erro de comunicação com o servidor.',
            status: 'danger',
            pos: 'top-center',
            timeout: 4000
        });
        loginButton.disabled = false;
        loginButton.textContent = 'Entrar';
    }
}

async function handleSignup(event) {
    event.preventDefault();
    const form = event.currentTarget;
    const signupButton = document.getElementById('signup-button');
    signupButton.disabled = true;
    signupButton.textContent = 'Cadastrando...';

    try {
        const response = await fetch('api/user_signup.php', {
            method: 'POST',
            body: new FormData(form),
            credentials: 'same-origin'
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Não foi possível realizar o cadastro.');
        }

        const signupEmail = form.elements.email.value;
        UIkit.modal('#signup-modal').hide();
        form.reset();
        document.getElementById('email').value = signupEmail;
        UIkit.notification({
            message: result.message,
            status: 'success',
            pos: 'top-center',
            timeout: 4000
        });
    } catch (error) {
        UIkit.notification({
            message: error.message || 'Erro de comunicação com o servidor.',
            status: 'danger',
            pos: 'top-center',
            timeout: 4000
        });
    } finally {
        signupButton.disabled = false;
        signupButton.textContent = 'Cadastrar';
    }
}

function handleRecovery(event) {
    event.preventDefault();
    UIkit.modal('#recovery-modal').hide();
    UIkit.notification({
        message: `<span uk-icon='icon: mail'></span> Instruções enviadas para seu e-mail corporativo.`,
        status: 'success',
        pos: 'top-center',
        timeout: 4000
    });
}

let topUrls = [];
let recentUrls = [];
let createdShortUrl = '';
let createdUrlItem = null;
let currentDetailsShortUrl = '';
const urlsPerPage = 20;
let userUrls = [];
let urlsCurrentPage = 1;
let urlsLoaded = false;

function renderTopUrlsTable() {
    const tbody = document.getElementById('top-urls-table-body');
    if (!tbody) return;
    tbody.innerHTML = '';

    topUrls.forEach(item => {
        const tr = document.createElement('tr');
        const shortCode = document.createElement('td');
        const longUrl = document.createElement('td');
        const source = document.createElement('td');
        const clicks = document.createElement('td');
        const actions = document.createElement('td');
        const badge = document.createElement('span');
        const copyButton = createCopyButton(item.short_code);
        const detailsButton = createDetailsButton(item);

        badge.className = 'uk-badge uk-text-xsmall';
        badge.style.cssText = 'background:#e5e7eb; color:#111827;';
        badge.textContent = item.short_code;
        shortCode.appendChild(badge);
        longUrl.className = 'uk-text-truncate';
        longUrl.style.maxWidth = '240px';
        longUrl.title = item.long_url;
        longUrl.textContent = item.long_url;
        source.className = 'uk-text-muted';
        source.textContent = formatUrlDate(item.created_at);
        clicks.className = 'uk-text-right uk-text-bold';
        clicks.textContent = Number(item.clicks_count || 0).toLocaleString('pt-BR');
        actions.className = 'uk-text-center';
        actions.append(copyButton, detailsButton);
        tr.append(shortCode, longUrl, source, clicks, actions);
        tbody.appendChild(tr);
    });
}

function createDetailsButton(item) {
    const button = document.createElement('button');
    button.className = 'uk-button uk-button-default uk-button-small';
    button.type = 'button';
    button.textContent = 'Detalhes';
    button.addEventListener('click', () => showUrlDetails(item));
    return button;
}

function showUrlDetails(item) {
    const shortUrl = item.short_url || getShortUrl(item.short_code);
    currentDetailsShortUrl = shortUrl;
    document.getElementById('modal-short-code').textContent = item.short_code;
    document.getElementById('modal-long-url').textContent = item.long_url;
    document.getElementById('modal-utm-source').value = '';
    document.getElementById('modal-utm-medium').value = '';
    document.getElementById('modal-utm-campaign').value = '';
    updateDetailsQrCode(shortUrl);
    document.getElementById('modal-stats-link').href = `stats.php?id=${encodeURIComponent(item.id)}`;
    UIkit.modal('#url-details-modal').show();
}

function updateDetailsQrCode(baseUrl) {
    const url = new URL(baseUrl);
    const parameters = {
        utm_source: document.getElementById('modal-utm-source').value.trim(),
        utm_medium: document.getElementById('modal-utm-medium').value.trim(),
        utm_campaign: document.getElementById('modal-utm-campaign').value.trim()
    };

    Object.entries(parameters).forEach(([key, value]) => {
        if (value) url.searchParams.set(key, value);
        else url.searchParams.delete(key);
    });

    const finalUrl = url.toString();
    document.getElementById('modal-short-url').value = finalUrl;
    document.getElementById('modal-qrcode').src =
        `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(finalUrl)}`;
}

function createCopyButton(shortCode) {
    const button = document.createElement('button');
    button.className = 'uk-button uk-button-default uk-button-small uk-margin-small-right';
    button.type = 'button';
    button.title = 'Copiar link curto';
    button.setAttribute('aria-label', 'Copiar link curto');
    button.innerHTML = '<span uk-icon="icon: copy"></span>';
    button.addEventListener('click', () => copyShortUrl(shortCode));
    UIkit.icon(button);
    return button;
}

function getShortUrl(shortCode) {
    const url = new URL('go.php', window.location.href);
    url.searchParams.set('code', shortCode);
    return url.toString();
}

async function copyShortUrl(shortCode) {
    const shortUrl = getShortUrl(shortCode);

    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(shortUrl);
        } else {
            const temporaryInput = document.createElement('textarea');
            temporaryInput.value = shortUrl;
            temporaryInput.style.position = 'fixed';
            temporaryInput.style.opacity = '0';
            document.body.appendChild(temporaryInput);
            temporaryInput.select();
            document.execCommand('copy');
            temporaryInput.remove();
        }

        UIkit.notification({
            message: 'Link curto copiado.',
            status: 'success',
            pos: 'top-center',
            timeout: 2000
        });
    } catch (error) {
        UIkit.notification({
            message: 'Não foi possível copiar o link curto.',
            status: 'danger',
            pos: 'top-center',
            timeout: 3000
        });
    }
}

function renderRecentUrlsTable() {
    const table = document.getElementById('recent-urls-table');
    if (!table) return;
    const tbody = document.getElementById('recent-urls-table-body');
    const empty = document.getElementById('recent-urls-empty');
    tbody.innerHTML = '';

    if (recentUrls.length === 0) {
        table.hidden = true;
        empty.hidden = false;
        return;
    }

    recentUrls.forEach(item => {
        const row = document.createElement('tr');
        const shortCode = document.createElement('td');
        const longUrl = document.createElement('td');
        const clicks = document.createElement('td');
        const createdAt = document.createElement('td');
        const actions = document.createElement('td');
        const detailsButton = createDetailsButton(item);

        shortCode.textContent = item.short_code;
        longUrl.className = 'uk-text-truncate';
        longUrl.style.maxWidth = '360px';
        longUrl.title = item.long_url;
        longUrl.textContent = item.long_url;
        clicks.className = 'uk-text-right';
        clicks.textContent = Number(item.clicks_count || 0).toLocaleString('pt-BR');
        createdAt.textContent = formatUrlDate(item.created_at);
        actions.className = 'uk-text-center';
        actions.append(createCopyButton(item.short_code), detailsButton);
        row.append(shortCode, longUrl, clicks, createdAt, actions);
        tbody.appendChild(row);
    });

    empty.hidden = true;
    table.hidden = false;
}

async function loadDashboardUrls() {
    const loading = document.getElementById('recent-urls-loading');
    const error = document.getElementById('recent-urls-error');
    if (!loading || !error) return;
    loading.hidden = false;
    error.hidden = true;

    try {
        const response = await fetch('api/user_dashboard_urls.php', {
            credentials: 'same-origin'
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Não foi possível carregar os links.');
        }

        topUrls = result.top_urls || [];
        recentUrls = result.recent_urls || [];
        renderTopUrlsTable();
        renderRecentUrlsTable();
    } catch (loadError) {
        error.textContent = loadError.message || 'Erro de comunicação com o servidor.';
        error.hidden = false;
    } finally {
        loading.hidden = true;
    }
}

async function handleShortenUrl(event) {
    event.preventDefault();
    const form = event.currentTarget;
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;
    button.textContent = 'Encurtando...';

    try {
        const formData = new FormData();
        formData.set('long_url', document.getElementById('input-long-url').value.trim());
        formData.set('slug', document.getElementById('input-slug').value.trim());

        const response = await fetch('api/user_url_create.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Não foi possível encurtar a URL.');
        }

        createdUrlItem = result.item;
        createdShortUrl = result.item.short_url;
        form.reset();
        document.getElementById('utm-source').value = '';
        document.getElementById('utm-medium').value = '';
        document.getElementById('utm-campaign').value = '';
        updateShortUrlQrCode();
        UIkit.modal('#short-url-modal').show();

        await loadDashboardUrls();
        urlsLoaded = false;

        UIkit.notification({
            message: 'URL encurtada com sucesso.',
            status: 'success',
            pos: 'top-center',
            timeout: 3000
        });
    } catch (error) {
        UIkit.notification({
            message: error.message || 'Erro de comunicação com o servidor.',
            status: 'danger',
            pos: 'top-center',
            timeout: 4000
        });
    } finally {
        button.disabled = false;
        button.innerHTML = '<span uk-icon="icon: plus" class="uk-margin-small-right"></span> Encurtar';
        UIkit.icon(button);
    }
}

function updateShortUrlQrCode() {
    if (!createdShortUrl) return;

    const url = new URL(createdShortUrl);
    const parameters = {
        utm_source: document.getElementById('utm-source').value.trim(),
        utm_medium: document.getElementById('utm-medium').value.trim(),
        utm_campaign: document.getElementById('utm-campaign').value.trim()
    };

    Object.entries(parameters).forEach(([key, value]) => {
        if (value) url.searchParams.set(key, value);
        else url.searchParams.delete(key);
    });

    const finalUrl = url.toString();
    document.getElementById('short-url-result').value = finalUrl;
    document.getElementById('short-url-qrcode').src =
        `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(finalUrl)}`;
}

async function loadUserUrls(search = '') {
    const loading = document.getElementById('urls-loading');
    const error = document.getElementById('urls-error');
    const table = document.getElementById('urls-table');
    const empty = document.getElementById('urls-empty');
    const pagination = document.getElementById('urls-pagination');
    if (!loading || !error || !table || !empty || !pagination) return;

    loading.hidden = false;
    error.hidden = true;
    table.hidden = true;
    empty.hidden = true;
    pagination.hidden = true;

    try {
        const params = new URLSearchParams();
        if (search) params.set('search', search);

        const response = await fetch(`api/user_urls.php?${params.toString()}`, {
            credentials: 'same-origin'
        });
        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Não foi possível carregar suas URLs.');
        }

        userUrls = result.items || [];
        urlsCurrentPage = 1;
        urlsLoaded = true;
        renderUserUrls();
    } catch (loadError) {
        error.textContent = loadError.message || 'Erro de comunicação com o servidor.';
        error.hidden = false;
    } finally {
        loading.hidden = true;
    }
}

function renderUserUrls() {
    const tbody = document.getElementById('urls-table-body');
    const table = document.getElementById('urls-table');
    const empty = document.getElementById('urls-empty');
    const pagination = document.getElementById('urls-pagination');
    const pageStatus = document.getElementById('urls-page-status');
    const previous = document.getElementById('urls-previous');
    const next = document.getElementById('urls-next');
    const totalPages = Math.max(1, Math.ceil(userUrls.length / urlsPerPage));

    tbody.innerHTML = '';
    if (userUrls.length === 0) {
        empty.hidden = false;
        return;
    }

    const start = (urlsCurrentPage - 1) * urlsPerPage;
    userUrls.slice(start, start + urlsPerPage).forEach(url => {
        const row = document.createElement('tr');
        const shortCode = document.createElement('td');
        const destination = document.createElement('td');
        const clicks = document.createElement('td');
        const createdAt = document.createElement('td');
        const actions = document.createElement('td');
        const detailsButton = createDetailsButton(url);

        shortCode.textContent = url.short_code;
        destination.textContent = url.long_url;
        destination.className = 'uk-text-truncate';
        destination.style.maxWidth = '360px';
        destination.title = url.long_url;
        clicks.textContent = Number(url.clicks_count || 0).toLocaleString('pt-BR');
        clicks.className = 'uk-text-right';
        createdAt.textContent = formatUrlDate(url.created_at);

        actions.className = 'uk-text-center';
        actions.append(createCopyButton(url.short_code), detailsButton);
        row.append(shortCode, destination, clicks, createdAt, actions);
        tbody.appendChild(row);
    });

    table.hidden = false;
    pagination.hidden = totalPages <= 1;
    pageStatus.textContent = `Página ${urlsCurrentPage} de ${totalPages}`;
    previous.disabled = urlsCurrentPage === 1;
    next.disabled = urlsCurrentPage === totalPages;
}

function formatUrlDate(dateValue) {
    if (!dateValue) return '—';
    const date = new Date(dateValue.replace(' ', 'T'));
    return Number.isNaN(date.getTime()) ? dateValue : date.toLocaleDateString('pt-BR');
}

function changeUrlsPage(direction) {
    const totalPages = Math.ceil(userUrls.length / urlsPerPage);
    urlsCurrentPage = Math.min(Math.max(urlsCurrentPage + direction, 1), totalPages);
    renderUserUrls();
}

function searchUrls(event) {
    event.preventDefault();
    loadUserUrls(document.getElementById('urls-search').value.trim());
}

function switchTab(event, tabId) {
    if (event && event.preventDefault) event.preventDefault();
    document.querySelectorAll('.dashboard-tab').forEach(tab => tab.hidden = true);
    const target = document.getElementById(tabId);
    if (target) target.hidden = false;

    document.querySelectorAll('.sidebar-link').forEach(link => link.classList.remove('uk-active'));
    if (event && event.currentTarget && event.currentTarget.classList.contains('sidebar-link')) {
        event.currentTarget.classList.add('uk-active');
    }

    if (tabId === 'tab-urls' && !urlsLoaded) {
        loadUserUrls();
    }
}

function saveSettings() {
    UIkit.notification({
        message: `<span uk-icon='icon: check'></span> Configurações atualizadas.`,
        status: 'success',
        pos: 'top-center',
        timeout: 3000
    });
}

function createStatsChart(elementId, type, labels, values, label) {
    const canvas = document.getElementById(elementId);
    if (!canvas || typeof Chart === 'undefined') return;

    const colors = ['#111827', '#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed'];
    new Chart(canvas, {
        type,
        data: {
            labels,
            datasets: [{
                label,
                data: values,
                backgroundColor: type === 'line' ? '#2563eb' : colors,
                borderColor: type === 'line' ? '#2563eb' : colors,
                borderWidth: 2,
                fill: type === 'line',
                tension: 0.25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: type !== 'line' }
            },
            scales: type === 'line' ? { y: { beginAtZero: true, ticks: { precision: 0 } } } : {}
        }
    });
}

async function loadStatsPage() {
    const params = new URLSearchParams(window.location.search);
    const urlId = params.get('id');
    const loading = document.getElementById('stats-loading');
    const error = document.getElementById('stats-error');
    const content = document.getElementById('stats-content');
    if (!loading || !error || !content) return;

    if (!urlId) {
        loading.hidden = true;
        error.textContent = 'URL inválida.';
        error.hidden = false;
        return;
    }

    try {
        const response = await fetch(`api/url_stats.php?id=${encodeURIComponent(urlId)}`, {
            credentials: 'same-origin'
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Não foi possível carregar as estatísticas.');
        }

        const shortUrl = getShortUrl(result.url.short_code);
        document.getElementById('stats-title').textContent = `Estatísticas: ${result.url.short_code}`;
        document.getElementById('stats-destination').textContent = result.url.long_url;
        const shortUrlLink = document.getElementById('stats-short-url');
        shortUrlLink.href = shortUrl;
        document.getElementById('stats-total-clicks').textContent = Number(result.total_clicks).toLocaleString('pt-BR');
        document.getElementById('stats-source-count').textContent = result.by_source.length.toLocaleString('pt-BR');
        document.getElementById('stats-campaign-count').textContent = result.by_campaign.length.toLocaleString('pt-BR');

        createStatsChart('daily-chart', 'line', result.daily.map(item => item.label), result.daily.map(item => Number(item.total)), 'Cliques');
        createStatsChart('source-chart', 'doughnut', result.by_source.map(item => item.label), result.by_source.map(item => Number(item.total)), 'Cliques');
        createStatsChart('medium-chart', 'bar', result.by_medium.map(item => item.label), result.by_medium.map(item => Number(item.total)), 'Cliques');
        createStatsChart('campaign-chart', 'bar', result.by_campaign.map(item => item.label), result.by_campaign.map(item => Number(item.total)), 'Cliques');
        content.hidden = false;
    } catch (loadError) {
        error.textContent = loadError.message || 'Erro de comunicação com o servidor.';
        error.hidden = false;
    } finally {
        loading.hidden = true;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const currentYear = document.getElementById('current-year');
    if (currentYear) currentYear.textContent = new Date().getFullYear();

    if (document.getElementById('top-urls-table-body')) {
        loadDashboardUrls();
    }

    if (document.getElementById('stats-content')) {
        loadStatsPage();
    }

    ['utm-source', 'utm-medium', 'utm-campaign'].forEach(id => {
        const input = document.getElementById(id);
        if (input) input.addEventListener('input', updateShortUrlQrCode);
    });

    ['modal-utm-source', 'modal-utm-medium', 'modal-utm-campaign'].forEach(id => {
        const input = document.getElementById(id);
        if (input) input.addEventListener('input', () => {
            updateDetailsQrCode(currentDetailsShortUrl);
        });
    });
});

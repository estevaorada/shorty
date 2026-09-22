<?php
// verify if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="uk-background-muted">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel — Shorty Enterprise</title>
    <!-- favicon -->
    <link rel="icon" type="image/png" href="static/favicon.png">
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/css/uikit.min.css" />
    <link rel="stylesheet" href="static/style.css" />
</head>
<body class="panel-page">

    <!-- Sidebar fixa para desktop -->
    <aside class="sidebar-enterprise">
        <div class="uk-padding-small uk-padding-remove-horizontal uk-padding-remove-bottom uk-text-center uk-margin-small-bottom">
            <div class="uk-flex uk-flex-middle uk-flex-center">
                <span uk-icon="icon: link; ratio: 1.1" class="uk-text-secondary uk-margin-small-right"></span>
                <span class="uk-h5 uk-margin-remove uk-text-bold">Shorty</span>
            </div>
            <p class="uk-text-xsmall uk-text-muted uk-margin-remove">Encurtador Privado</p>
        </div>
        
        <nav class="uk-margin-small-top uk-flex-1">
            <a href="#home" class="sidebar-link uk-active" onclick="switchTab(event, 'tab-home')">
                <span uk-icon="icon: home; ratio: 0.9" class="uk-margin-small-right"></span> Início
            </a>
            <a href="#all-urls" class="sidebar-link" onclick="switchTab(event, 'tab-urls')">
                <span uk-icon="icon: list; ratio: 0.9" class="uk-margin-small-right"></span> Minhas URLs
            </a>
            <!-- <a href="#settings" class="sidebar-link" onclick="switchTab(event, 'tab-settings')">
                <span uk-icon="icon: settings; ratio: 0.9" class="uk-margin-small-right"><span> Configurações
            </a> -->
        </nav>

        <div class="uk-padding-small uk-border-top uk-text-center">
            <a href="api/logout.php" class="uk-button uk-button-default uk-button-small uk-width-1-1 uk-margin-small-bottom">
                <span uk-icon="icon: sign-out" class="uk-margin-small-right"></span> Sair
            </a>
            <span class="uk-text-xsmall uk-text-muted">v0.1-beta</span>
        </div>
    </aside>

    <!-- Offcanvas para mobile -->
    <div id="mobile-offcanvas" uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar uk-background-default uk-light">
            <button class="uk-offcanvas-close uk-text-dark" type="button" uk-close></button>
            <div class="uk-h5 uk-text-bold uk-text-dark uk-margin-medium-bottom">Shorty</div>
            <ul class="uk-nav uk-nav-default">
                <li class="uk-active"><a href="#" onclick="switchTab(event, 'tab-home')"><span uk-icon="icon: home" class="uk-margin-small-right"></span> Início</a></li>
                <li><a href="#" onclick="switchTab(event, 'tab-urls')"><span uk-icon="icon: list" class="uk-margin-small-right"></span> Todas as URLs</a></li>
                <!-- <li><a href="#" onclick="switchTab(event, 'tab-settings')"><span uk-icon="icon: settings" class="uk-margin-small-right"></span> Configurações</a></li> -->
            </ul>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="main-content">
        <!-- Top bar mobile/responsive -->
        <div class="uk-hidden@m uk-background-default uk-padding-small uk-border-bottom uk-flex uk-flex-middle uk-flex-between">
            <button class="uk-button uk-button-default uk-button-small" uk-toggle="target: #mobile-offcanvas">
                <span uk-icon="icon: menu"></span> Menu
            </button>
            <span class="uk-text-bold uk-text-small">Shorty</span>
        </div>

        <div class="uk-container uk-padding-large">
            
            <!-- Mensagem de boas-vindas -->
            <div class="uk-flex uk-flex-middle uk-flex-between uk-margin-medium-bottom">
                <div>
                    <h1 class="uk-h3 uk-text-bold uk-margin-remove">Bem-vindo(a), <span id="user-name"><?php echo $_SESSION['email']; ?></span></h1>
                    <p class="uk-text-small uk-text-muted uk-margin-remove">Painel administrativo</p>
                </div>
                <div>
                    <!-- <span class="uk-badge uk-text-xsmall" style="background:#111827; color:#fff;">Corporativo Ativo</span> -->
                </div>
            </div>

            <!-- TAB INÍCIO -->
            <div id="tab-home" class="dashboard-tab">
                
                <!-- Campo para encurtar URL -->
                <div class="uk-card uk-card-enterprise uk-card-body uk-margin-medium-bottom">
                    <h2 class="uk-h5 uk-text-bold uk-margin-small-bottom">Encurtar Nova URL</h2>
                    <p class="uk-text-xsmall uk-text-muted uk-margin-small-bottom">Cole sua URL longa e defina opcionalmente um slug.</p>
                    
                    <form onsubmit="handleShortenUrl(event)">
                        <div class="uk-grid-small" uk-grid>
                            <div class="uk-width-expand@s">
                                <input class="uk-input uk-input-enterprise" id="input-long-url" type="url" placeholder="https://seu-destino.com/pagina-longa..." required>
                            </div>
                            <div class="uk-width-auto@s" style="width: 220px;">
                                <input class="uk-input uk-input-enterprise" id="input-slug" type="text" placeholder="Slug (ex: minha-url)">
                            </div>
                            <div class="uk-width-auto@s">
                                <button type="submit" class="uk-button uk-btn-enterprise uk-width-1-1">
                                    <span uk-icon="icon: plus" class="uk-margin-small-right"></span> Encurtar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- 10 URLs mais populares -->
                <div class="uk-card uk-card-enterprise uk-card-body">
                    <div class="uk-flex uk-flex-middle uk-flex-between uk-margin-bottom">
                        <div>
                            <h2 class="uk-h5 uk-text-bold uk-margin-remove">Seus 10 Links Mais Acessados</h2>
                            <p class="uk-text-xsmall uk-text-muted uk-margin-remove">Seus links mais visitados</p>
                        </div>
                        <a href="#tab-urls" class="uk-text-small uk-link-muted" onclick="switchTab(event, 'tab-urls')">Ver todas →</a>
                    </div>

                    <div class="uk-overflow-auto">
                        <table class="uk-table uk-table-divider uk-table-middle uk-table-small uk-text-small">
                            <thead>
                                <tr>
                                    <th>Short Code</th>
                                    <th>Long URL</th>
                                    <th>Criada em</th>
                                    <th class="uk-text-right">Cliques</th>
                                    <th class="uk-text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="top-urls-table-body"></tbody>
                        </table>
                    </div>
                </div>

                <div class="uk-card uk-card-enterprise uk-card-body uk-margin-medium-top">
                    <div class="uk-margin-bottom">
                        <h2 class="uk-h5 uk-text-bold uk-margin-remove">Últimos 10 Links Encurtados</h2>
                        <p class="uk-text-xsmall uk-text-muted uk-margin-remove">Links criados mais recentemente.</p>
                    </div>

                    <div id="recent-urls-loading" class="uk-text-small uk-text-muted">Carregando links...</div>
                    <div id="recent-urls-error" class="uk-alert-danger uk-text-small" uk-alert hidden></div>
                    <div class="uk-overflow-auto">
                        <table id="recent-urls-table" class="uk-table uk-table-divider uk-table-middle uk-table-small uk-text-small" hidden>
                            <thead>
                                <tr>
                                    <th>Short Code</th>
                                    <th>Long URL</th>
                                    <th class="uk-text-right">Cliques</th>
                                    <th>Criada em</th>
                                    <th class="uk-text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="recent-urls-table-body"></tbody>
                        </table>
                    </div>
                    <p id="recent-urls-empty" class="uk-text-small uk-text-muted" hidden>Nenhum link encurtado ainda.</p>
                </div>

            </div>

            <!-- TAB TODAS AS URLS -->
            <div id="tab-urls" class="dashboard-tab" hidden>
                <div class="uk-card uk-card-enterprise uk-card-body">
                    <div class="uk-flex uk-flex-middle uk-flex-between uk-flex-wrap uk-margin-bottom">
                        <div>
                            <h2 class="uk-h5 uk-text-bold uk-margin-remove">Minhas URLs</h2>
                            <p class="uk-text-small uk-text-muted uk-margin-remove">Links encurtados associados à sua conta.</p>
                        </div>
                        <form id="urls-search-form" class="uk-search uk-search-default uk-margin-small-top uk-margin-remove@s" onsubmit="searchUrls(event)">
                            <button class="uk-search-icon-flip" type="submit" uk-search-icon aria-label="Buscar"></button>
                            <input id="urls-search" class="uk-search-input" type="search" placeholder="Buscar por código ou URL" aria-label="Buscar URLs">
                        </form>
                    </div>

                    <div id="urls-loading" class="uk-text-small uk-text-muted" hidden>Carregando URLs...</div>
                    <div id="urls-error" class="uk-alert-danger uk-text-small" uk-alert hidden></div>
                    <div id="urls-empty" class="uk-alert-primary uk-text-small" uk-alert hidden>
                        <p>Nenhuma URL encontrada.</p>
                    </div>

                    <div class="uk-overflow-auto">
                        <table class="uk-table uk-table-divider uk-table-middle uk-table-small uk-text-small" id="urls-table" hidden>
                            <thead>
                                <tr>
                                    <th>Short Code</th>
                                    <th>URL de destino</th>
                                    <th class="uk-text-right">Cliques</th>
                                    <th>Criada em</th>
                                    <th class="uk-text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="urls-table-body"></tbody>
                        </table>
                    </div>

                    <div id="urls-pagination" class="uk-flex uk-flex-middle uk-flex-between uk-margin-top" hidden>
                        <span id="urls-page-status" class="uk-text-small uk-text-muted"></span>
                        <div>
                            <button id="urls-previous" class="uk-button uk-button-default uk-button-small" type="button" onclick="changeUrlsPage(-1)">Anterior</button>
                            <button id="urls-next" class="uk-button uk-button-default uk-button-small" type="button" onclick="changeUrlsPage(1)">Próxima</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB CONFIGURAÇÕES -->
            <!-- <div id="tab-settings" class="dashboard-tab" hidden>
                <div class="uk-card uk-card-enterprise uk-card-body">
                    <h2 class="uk-h5 uk-text-bold uk-margin-bottom">Configurações de Perfil e Domínio</h2>
                    <form>
                        <div class="uk-margin">
                            <label class="uk-form-label uk-text-small uk-text-muted">Nome do Operador</label>
                            <input class="uk-input uk-input-enterprise" type="text" value="Carlos Oliveira">
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label uk-text-small uk-text-muted">Domínio Padrão de Redirect</label>
                            <input class="uk-input uk-input-enterprise" type="text" value="l.empresa.internal" disabled>
                        </div>
                        <button type="button" class="uk-button uk-btn-enterprise" onclick="saveSettings()">Salvar Alterações</button>
                    </form>
                </div>
            </div> -->

        </div>
    </div>

    <!-- Modal da URL encurtada -->
    <div id="short-url-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-card-enterprise">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="uk-modal-title uk-h5 uk-text-bold">URL encurtada</h2>
            <p class="uk-text-small uk-text-muted">Use o link abaixo ou personalize o QR code com parâmetros UTM.</p>

            <div class="uk-margin">
                <label class="uk-form-label uk-text-small uk-text-muted" for="short-url-result">Novo link</label>
                <input id="short-url-result" class="uk-input uk-input-enterprise uk-margin-small-top" type="text" readonly>
            </div>

            <div class="uk-text-center uk-margin-medium">
                <img id="short-url-qrcode" width="150" height="150" alt="QR code da URL encurtada">
            </div>

            <div class="uk-grid-small" uk-grid>
                <div class="uk-width-1-1">
                    <label class="uk-form-label uk-text-small uk-text-muted" for="utm-source">utm_source</label>
                    <input id="utm-source" class="uk-input uk-input-enterprise uk-margin-small-top" type="text" placeholder="newsletter">
                </div>
                <div class="uk-width-1-2@s">
                    <label class="uk-form-label uk-text-small uk-text-muted" for="utm-medium">utm_medium</label>
                    <input id="utm-medium" class="uk-input uk-input-enterprise uk-margin-small-top" type="text" placeholder="email">
                </div>
                <div class="uk-width-1-2@s">
                    <label class="uk-form-label uk-text-small uk-text-muted" for="utm-campaign">utm_campaign</label>
                    <input id="utm-campaign" class="uk-input uk-input-enterprise uk-margin-small-top" type="text" placeholder="promocao">
                </div>
            </div>

            <div class="uk-text-right uk-margin-medium-top">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Fechar</button>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes da URL -->
    <div id="url-details-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-card-enterprise">
            <h2 class="uk-modal-title uk-h5 uk-text-bold">Detalhes do Link</h2>
            
            <div class="uk-margin-small-top">
                <p class="uk-text-xsmall uk-text-muted uk-margin-remove-bottom">Código Curto</p>
                <p id="modal-short-code" class="uk-text-bold uk-text-large uk-margin-remove-top"></p>
            </div>

            <div class="uk-margin-small">
                <p class="uk-text-xsmall uk-text-muted uk-margin-remove-bottom">URL de Destino Original</p>
                <p id="modal-long-url" class="uk-text-small uk-text-truncate uk-margin-remove-top uk-text-muted"></p>
            </div>

            <div class="uk-margin-medium-top">
                <h3 class="uk-h6 uk-text-bold">Distribuição por utm_source</h3>
                <div id="modal-utm-breakdown" class="uk-margin-small-top">
                    <!-- Barras de utm geradas via js -->
                </div>
            </div>

            <div class="uk-text-right uk-margin-medium-top">
                <button class="uk-button uk-button-default uk-modal-close" type="button">Fechar</button>
            </div>
        </div>
    </div>

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit-icons.min.js"></script>
    <script src="static/app.js"></script>
    <script type="text/plain">
        let topUrls = [];
        let recentUrls = [];

        function renderTopUrlsTable() {
            const tbody = document.getElementById('top-urls-table-body');
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
                const detailsButton = document.createElement('button');

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
                detailsButton.className = 'uk-button uk-button-default uk-button-small';
                detailsButton.type = 'button';
                detailsButton.textContent = 'Detalhes';
                detailsButton.addEventListener('click', () => showUrlDetails(item));
                actions.append(copyButton, detailsButton);
                tr.append(shortCode, longUrl, source, clicks, actions);
                tbody.appendChild(tr);
            });
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

        function showUrlDetails(item) {
            createdUrlItem = item;
            createdShortUrl = item.short_url || getShortUrl(item.short_code);
            document.getElementById('utm-source').value = '';
            document.getElementById('utm-medium').value = '';
            document.getElementById('utm-campaign').value = '';
            updateShortUrlQrCode();
            UIkit.modal('#short-url-modal').show();
        }

        function renderRecentUrlsTable() {
            const table = document.getElementById('recent-urls-table');
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
                const detailsButton = document.createElement('button');

                shortCode.textContent = item.short_code;
                longUrl.className = 'uk-text-truncate';
                longUrl.style.maxWidth = '360px';
                longUrl.title = item.long_url;
                longUrl.textContent = item.long_url;
                clicks.className = 'uk-text-right';
                clicks.textContent = Number(item.clicks_count || 0).toLocaleString('pt-BR');
                createdAt.textContent = formatUrlDate(item.created_at);
                detailsButton.className = 'uk-button uk-button-default uk-button-small';
                detailsButton.type = 'button';
                detailsButton.textContent = 'Detalhes';
                detailsButton.addEventListener('click', () => showUrlDetails(item));
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

        function openDetailsModal(urlId) {
            const urlItem = topUrls.find(u => u.id === urlId);
            if (!urlItem) return;

            document.getElementById('modal-short-code').textContent = urlItem.short_code;
            document.getElementById('modal-long-url').textContent = urlItem.long_url;

            const breakdownContainer = document.getElementById('modal-utm-breakdown');
            breakdownContainer.innerHTML = '';

            const total = urlItem.clicks_count || 1;
            const sources = urlItem.utm_breakdown || { [urlItem.utm_source || 'direct']: total };

            Object.entries(sources).forEach(([source, count]) => {
                const percentage = Math.round((count / total) * 100);
                const div = document.createElement('div');
                div.className = 'bar-utm-item';
                div.innerHTML = `
                    <div class="uk-flex uk-flex-between">
                        <span><b>${source}</b></span>
                        <span class="uk-text-muted">${count.toLocaleString('pt-BR')} cliques (${percentage}%)</span>
                    </div>
                    <div class="bar-utm-track">
                        <div class="bar-utm-fill" style="width: ${percentage}%;"></div>
                    </div>
                `;
                breakdownContainer.appendChild(div);
            });

            UIkit.modal('#url-details-modal').show();
        }

        let createdShortUrl = '';
        let createdUrlItem = null;

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

        const urlsPerPage = 20;
        let userUrls = [];
        let urlsCurrentPage = 1;
        let urlsLoaded = false;

        async function loadUserUrls(search = '') {
            const loading = document.getElementById('urls-loading');
            const error = document.getElementById('urls-error');
            const table = document.getElementById('urls-table');
            const empty = document.getElementById('urls-empty');
            const pagination = document.getElementById('urls-pagination');

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
                const detailsButton = document.createElement('button');

                shortCode.textContent = url.short_code;
                destination.textContent = url.long_url;
                destination.className = 'uk-text-truncate';
                destination.style.maxWidth = '360px';
                destination.title = url.long_url;
                clicks.textContent = Number(url.clicks_count || 0).toLocaleString('pt-BR');
                clicks.className = 'uk-text-right';
                createdAt.textContent = formatUrlDate(url.created_at);

                detailsButton.className = 'uk-button uk-button-default uk-button-small';
                detailsButton.type = 'button';
                detailsButton.textContent = 'Detalhes';
                detailsButton.addEventListener('click', () => showUrlDetails(url));
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

            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('uk-active'));
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

        // Inicializar renderização da tabela ao carregar
        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardUrls();
            ['utm-source', 'utm-medium', 'utm-campaign'].forEach(id => {
                document.getElementById(id).addEventListener('input', updateShortUrlQrCode);
            });
        });
    </script>
</body>
</html>
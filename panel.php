<?php
// verify if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR" class="uk-background-muted">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel — Shorty Enterprise</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/css/uikit.min.css" />
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1f2937;
            background-color: #f3f4f6;
        }
        .uk-card-enterprise {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            border-radius: 0.5rem;
        }
        .uk-input-enterprise, .uk-select-enterprise {
            background-color: #f9fafb !important;
            border-color: #d1d5db !important;
            border-radius: 0.375rem !important;
            color: #111827;
        }
        .uk-input-enterprise:focus, .uk-select-enterprise:focus {
            background-color: #ffffff !important;
            border-color: #6b7280 !important;
        }
        .uk-btn-enterprise {
            background-color: #111827;
            color: #ffffff;
            border-radius: 0.375rem;
            transition: background-color 0.2s ease;
        }
        .uk-btn-enterprise:hover {
            background-color: #374151;
            color: #ffffff;
        }
        .sidebar-enterprise {
            width: 250px;
            background-color: #ffffff;
            border-right: 1px class="uk-border-muted" #e5e7eb;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #e5e7eb;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: #4b5563;
            font-size: 0.875rem;
            font-weight: 500;
            border-left: 3px solid transparent;
            text-decoration: none;
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.uk-active {
            color: #111827;
            background-color: #f9fafb;
            border-left-color: #111827;
            text-decoration: none;
        }
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }
        @media (max-width: 959px) {
            .sidebar-enterprise {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
        }
        .bar-utm-item {
            font-size: 0.8125rem;
            margin-bottom: 0.5rem;
        }
        .bar-utm-track {
            background-color: #f3f4f6;
            border-radius: 9999px;
            height: 8px;
            overflow: hidden;
            margin-top: 0.25rem;
        }
        .bar-utm-fill {
            background-color: #111827;
            height: 100%;
            border-radius: 9999px;
        }
    </style>
</head>
<body>

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
                                    <th>Slug</th>
                                    <th class="uk-text-right">Cliques</th>
                                    <th class="uk-text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="top-urls-table-body">
                                <!-- Preenchido dinamicamente via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- TAB TODAS AS URLS -->
            <div id="tab-urls" class="dashboard-tab" hidden>
                <div class="uk-card uk-card-enterprise uk-card-body">
                    <h2 class="uk-h5 uk-text-bold uk-margin-bottom">Todas as URLs Registradas</h2>
                    <p class="uk-text-small uk-text-muted">Repositório completo de links encurtados com privilégio de acesso restrito.</p>
                    <div class="uk-alert-primary uk-text-small" uk-alert>
                        <p>Mostrando visão completa filtrável. Acesse o botão de <b>Detalhes</b> na linha de cada link para ver telemetria por UTM.</p>
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

    <script>
        // Mock das top 10 URLs populares
        let topUrls = [
            { id: 1, short_code: 'internal-q1', long_url: 'https://intra.empresa.com/docs/relatorio-financeiro-q1-2026.pdf', utm_source: 'linkedin', clicks_count: 1420, utm_breakdown: { linkedin: 900, newsletter: 320, slack: 200 } },
            { id: 2, short_code: 'sec-patch-9', long_url: 'https://security.empresa.internal/advisories/cve-2026-901.html', utm_source: 'slack', clicks_count: 980, utm_breakdown: { slack: 700, direct: 280 } },
            { id: 3, short_code: 'hr-onboard', long_url: 'https://rh.empresa.com/playbook/integracao-novos-colaboradores.html', utm_source: 'email', clicks_count: 850, utm_breakdown: { email: 600, internal_portal: 250 } },
            { id: 4, short_code: 'wiki-k8s', long_url: 'https://devops.empresa.internal/runbooks/kubernetes-cluster-migration', utm_source: 'github', clicks_count: 640, utm_breakdown: { github: 400, slack: 240 } },
            { id: 5, short_code: 'comms-allhands', long_url: 'https://meet.empresa.internal/room/all-hands-marco-2026', utm_source: 'calendar', clicks_count: 512, utm_breakdown: { calendar: 512 } },
            { id: 6, short_code: 'prod-roadmap', long_url: 'https://product.empresa.internal/roadmap/v3-enterprise-release', utm_source: 'jira', clicks_count: 430, utm_breakdown: { jira: 300, slack: 130 } },
            { id: 7, short_code: 'budget-2026', long_url: 'https://finance.empresa.internal/planning/orcamento-global-ti', utm_source: 'email', clicks_count: 395, utm_breakdown: { email: 395 } },
            { id: 8, short_code: 'design-sys', long_url: 'https://design.empresa.internal/tokens/grey-minimal-v3', utm_source: 'figma', clicks_count: 310, utm_breakdown: { figma: 250, slack: 60 } },
            { id: 9, short_code: 'compliance-lgpd', long_url: 'https://legal.empresa.com/compliance/privacy-policy-update-2026', utm_source: 'newsletter', clicks_count: 275, utm_breakdown: { newsletter: 275 } },
            { id: 10, short_code: 'vpn-client', long_url: 'https://it.empresa.internal/downloads/zero-trust-client-v4.pkg', utm_source: 'portal', clicks_count: 210, utm_breakdown: { portal: 210 } }
        ];

        function renderTopUrlsTable() {
            const tbody = document.getElementById('top-urls-table-body');
            tbody.innerHTML = '';
            
            topUrls.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><span class="uk-badge uk-text-xsmall" style="background:#e5e7eb; color:#111827;">${item.short_code}</span></td>
                    <td class="uk-text-truncate" style="max-width: 240px;" title="${item.long_url}">${item.long_url}</td>
                    <td><span class="uk-text-muted">${item.utm_source || '—'}</span></td>
                    <td class="uk-text-right uk-text-bold">${item.clicks_count.toLocaleString('pt-BR')}</td>
                    <td class="uk-text-center">
                        <button class="uk-button uk-button-default uk-button-small" onclick="openDetailsModal(${item.id})">Detalhes</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
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

        function handleShortenUrl(event) {
            event.preventDefault();
            const longUrl = document.getElementById('input-long-url').value;
            const utmSource = document.getElementById('input-utm-source').value.trim();

            const randomCode = 'link-' + Math.random().toString(36).substring(2, 7);
            const newItem = {
                id: Date.now(),
                short_code: randomCode,
                long_url: longUrl,
                utm_source: utmSource || 'direct',
                clicks_count: 1,
                utm_breakdown: { [utmSource || 'direct']: 1 }
            };

            topUrls.unshift(newItem);
            topUrls = topUrls.slice(0, 10);
            renderTopUrlsTable();

            document.getElementById('input-long-url').value = '';
            document.getElementById('input-utm-source').value = '';

            UIkit.notification({
                message: `<span uk-icon='icon: check'></span> URL encurtada com sucesso: <b>${randomCode}</b>`,
                status: 'primary',
                pos: 'top-center',
                timeout: 3000
            });
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
            renderTopUrlsTable();
        });
    </script>
</body>
</html>
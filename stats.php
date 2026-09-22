<?php

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
    <title>Estatísticas — Shorty</title>
    <link rel="icon" type="image/png" href="static/favicon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/css/uikit.min.css">
    <link rel="stylesheet" href="static/style.css">
</head>
<body class="panel-page">
    <main class="uk-container uk-container-large uk-padding-large">
        <div class="uk-flex uk-flex-middle uk-flex-between uk-flex-wrap uk-margin-medium-bottom">
            <div>
                <a href="panel.php" class="uk-link-muted uk-text-small">&larr; Voltar ao painel</a>
                <h1 id="stats-title" class="uk-h3 uk-text-bold uk-margin-small-top uk-margin-remove-bottom">Estatísticas do link</h1>
                <p id="stats-destination" class="uk-text-small uk-text-muted uk-margin-remove"></p>
            </div>
            <a id="stats-short-url" class="uk-button uk-button-default" href="#" target="_blank" rel="noopener">Abrir link curto</a>
        </div>

        <div id="stats-loading" class="uk-text-muted">Carregando estatísticas...</div>
        <div id="stats-error" class="uk-alert-danger" uk-alert hidden></div>

        <div id="stats-content" hidden>
            <div class="uk-grid-small uk-child-width-1-3@s uk-margin-medium-bottom" uk-grid>
                <div class="uk-card uk-card-enterprise uk-card-body">
                    <p class="uk-text-small uk-text-muted uk-margin-remove">Total de cliques</p>
                    <strong id="stats-total-clicks" class="uk-text-large">0</strong>
                </div>
                <div class="uk-card uk-card-enterprise uk-card-body">
                    <p class="uk-text-small uk-text-muted uk-margin-remove">UTM sources</p>
                    <strong id="stats-source-count" class="uk-text-large">0</strong>
                </div>
                <div class="uk-card uk-card-enterprise uk-card-body">
                    <p class="uk-text-small uk-text-muted uk-margin-remove">Campanhas</p>
                    <strong id="stats-campaign-count" class="uk-text-large">0</strong>
                </div>
            </div>

            <div class="uk-grid-small uk-child-width-1-2@m" uk-grid>
                <div class="uk-card uk-card-enterprise uk-card-body"><h2 class="uk-h5">Cliques por dia</h2><canvas id="daily-chart"></canvas></div>
                <div class="uk-card uk-card-enterprise uk-card-body"><h2 class="uk-h5">Por utm_source</h2><canvas id="source-chart"></canvas></div>
                <div class="uk-card uk-card-enterprise uk-card-body"><h2 class="uk-h5">Por utm_medium</h2><canvas id="medium-chart"></canvas></div>
                <div class="uk-card uk-card-enterprise uk-card-body"><h2 class="uk-h5">Por utm_campaign</h2><canvas id="campaign-chart"></canvas></div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit-icons.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script src="static/app.js"></script>
</body>
</html>
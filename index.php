<!DOCTYPE html>
<html lang="pt-BR" class="uk-background-muted">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Encurtador Privado</title>
    <!-- favicon -->
    <link rel="icon" type="image/png" href="static/favicon.png">
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/css/uikit.min.css" />
    <link rel="stylesheet" href="static/style.css" />
</head>

<body class="uk-flex uk-flex-middle uk-height-viewport uk-background-muted">

    <div class="uk-width-1-1">
        <div class="uk-container uk-container-xsmall">

            <!-- Branding Header -->
            <div class="uk-text-center uk-margin-medium-bottom">
                <span uk-icon="icon: link; ratio: 1.2" class="uk-text-secondary"></span>
                <h1 class="uk-h4 uk-margin-remove-top uk-margin-small-top uk-text-bold">Shorty</h1>
                <p class="uk-text-small uk-text-muted uk-margin-remove">Acesso restrito ao painel do encurtador privado</p>
            </div>

            <!-- Login Card -->
            <div class="uk-card uk-card-enterprise uk-card-body">
                <h2 class="uk-card-title uk-h5 uk-text-bold uk-margin-medium-bottom">Entrar na sua conta</h2>

                <form id="login-form" onsubmit="handleLogin(event)">
                    <!-- Email field -->
                    <div class="uk-margin">
                        <label class="uk-form-label uk-text-small uk-text-muted" for="email">E-mail</label>
                        <div class="uk-inline uk-width-1-1 uk-margin-small-top">
                            <span class="uk-form-icon uk-text-muted" uk-icon="icon: mail"></span>
                            <input class="uk-input uk-input-enterprise" id="email" name="email" type="email" placeholder="nome@dominio.com" required>
                        </div>
                    </div>

                    <!-- Password field -->
                    <div class="uk-margin">
                        <div class="uk-grid-collapse" uk-grid>
                            <div class="uk-width-expand">
                                <label class="uk-form-label uk-text-small uk-text-muted" for="password">Senha</label>
                            </div>
                            <div class="uk-width-auto">
                                <a class="uk-link-muted uk-text-small" href="#recovery-modal" uk-toggle>Esqueceu a senha?</a>
                            </div>
                        </div>
                        <div class="uk-inline uk-width-1-1 uk-margin-small-top">
                            <span class="uk-form-icon uk-text-muted" uk-icon="icon: lock"></span>
                            <input class="uk-input uk-input-enterprise" id="password" name="password" type="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="uk-margin-medium-top">
                        <button id="login-button" type="submit" class="uk-button uk-btn-enterprise uk-width-1-1">Entrar</button>
                    </div>
                </form>

                <p class="uk-text-center uk-text-small uk-text-muted uk-margin-medium-top uk-margin-remove-bottom">
                    Ainda não possui uma conta?
                    <a href="#signup-modal" uk-toggle>Cadastre-se</a>
                </p>
            </div>

            <!-- Footer info -->
            <div class="uk-text-center uk-margin-medium-top">
                <p class="uk-text-xsmall uk-text-muted">&copy; <span id="current-year"></span> Shorty. Todos os direitos reservados.</p>
            </div>

        </div>
    </div>

    <div id="signup-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-card-enterprise">
            <button class="uk-modal-close-default" type="button" uk-close></button>
            <h2 class="uk-modal-title uk-h5 uk-text-bold">Criar conta</h2>
            <p class="uk-text-small uk-text-muted">Informe seus dados e a chave de inscrição fornecida pelo administrador.</p>

            <form id="signup-form" onsubmit="handleSignup(event)">
                <div class="uk-margin">
                    <label class="uk-form-label uk-text-small uk-text-muted" for="signup-email">E-mail</label>
                    <input class="uk-input uk-input-enterprise uk-margin-small-top" id="signup-email" name="email" type="email" placeholder="nome@dominio.com" required>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label uk-text-small uk-text-muted" for="signup-password">Senha</label>
                    <input class="uk-input uk-input-enterprise uk-margin-small-top" id="signup-password" name="password" type="password" required>
                </div>

                <div class="uk-margin">
                    <label class="uk-form-label uk-text-small uk-text-muted" for="signup-key">Chave de inscrição</label>
                    <input class="uk-input uk-input-enterprise uk-margin-small-top" id="signup-key" name="signup_key" type="password" required>
                </div>

                <button id="signup-button" type="submit" class="uk-button uk-btn-enterprise uk-width-1-1">Cadastrar</button>
            </form>
        </div>
    </div>

    <div id="recovery-modal" uk-modal>
        <div class="uk-modal-dialog uk-modal-body uk-card-enterprise">
            <h2 class="uk-modal-title uk-h5 uk-text-bold">Recuperação de acesso</h2>
            <p class="uk-text-small uk-text-muted">Informe seu e-mail cadastrado para receber as instruções de redefinição de senha.</p>
            <form onsubmit="handleRecovery(event)">
                <div class="uk-margin">
                    <input class="uk-input uk-input-enterprise" type="email" placeholder="nome@dominio.com" required>
                </div>
                <div class="uk-text-right">
                    <button class="uk-button uk-button-default uk-modal-close uk-margin-small-right" type="button">Cancelar</button>
                    <button class="uk-button uk-btn-enterprise" type="submit">Enviar link</button>
                </div>
            </form>
        </div>
    </div>

    <!-- UIkit JS -->
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit-icons.min.js"></script>
    <script src="static/app.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="pt-BR" class="uk-background-muted">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Encurtador Privado</title>
    <!-- UIkit CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/css/uikit.min.css" />
    <!-- Custom minimalist overrides for enterprise grey/white theme -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1f2937;
        }
        .uk-card-enterprise {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-radius: 0.5rem;
        }
        .uk-input-enterprise {
            background-color: #f9fafb !important;
            border-color: #d1d5db !important;
            border-radius: 0.375rem !important;
            color: #111827;
        }
        .uk-input-enterprise:focus {
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
    </style>
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
    
    <script>
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
        // update current year in footer
        document.getElementById('current-year').textContent = new Date().getFullYear();
    </script>
</body>
</html>
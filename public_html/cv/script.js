/**
 * SCRIPT - CURRÍCULO PORTFÓLIO LEONARDO MACIEL
 * Controle de Tema Dinâmico, LocalStorage, Roteador de Acesso WhatsApp e Impressão
 */

document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('themeToggle');
    const printBtn = document.getElementById('printBtn');
    
    // 1. GERENCIAMENTO DE TEMA (LIGHT/DARK) NATIVO
    
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    let currentTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
    applyTheme(currentTheme);
    
    function applyTheme(theme) {
        document.documentElement.style.colorScheme = theme;
        document.body.classList.remove('light-theme', 'dark-theme');
        document.body.classList.add(`${theme}-theme`);
        
        const icon = themeToggleBtn.querySelector('i');
        if (theme === 'dark') {
            icon.className = 'fa-solid fa-sun';
            themeToggleBtn.title = 'Alternar para Tema Claro';
            themeToggleBtn.setAttribute('aria-label', 'Alternar para Tema Claro');
        } else {
            icon.className = 'fa-solid fa-moon';
            themeToggleBtn.title = 'Alternar para Tema Escuro';
            themeToggleBtn.setAttribute('aria-label', 'Alternar para Tema Escuro');
        }
        
        localStorage.setItem('theme', theme);
    }
    
    themeToggleBtn.addEventListener('click', () => {
        const nextTheme = document.documentElement.style.colorScheme === 'dark' ? 'light' : 'dark';
        applyTheme(nextTheme);
    });
    
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });

    // 2. GATILHO DE IMPRESSÃO (GERAÇÃO DE PDF EM A4)
    
    printBtn.addEventListener('click', () => {
        window.print();
    });

    // 3. CAPTURA DE ELEMENTOS DO HASH (GERADO DINAMICAMENTE PELO PHP)
    const cvHashElement = document.getElementById('cvHash');
    const printFooterLink = document.getElementById('printFooterLink');
    const resetAccessBtn = document.getElementById('resetAccessBtn');

    function logDeveloperEasterEgg(hash) {
        if (window.cvEasterEggLogged) return;
        window.cvEasterEggLogged = true;

        console.log(
            `%c👋 Olá, Dev curioso! %c👀 Vi que você abriu o console para inspecionar o código!\n\nComo sei que você gosta de atalhos e boas práticas de engenharia, aqui está o seu código de acesso exclusivo para liberar o currículo sem precisar preencher o WhatsApp:\n\n%c Código de Acesso: ${hash} %c\n\nDivirta-se analisando a arquitetura moderna deste CV feito com CSS Vanilla, Native Nesting, Cascade Layers e PHP dinâmico! 🚀`,
            "color: #00ff88; font-size: 14px; font-weight: bold;",
            "color: #9ca3af; font-size: 12px;",
            "background: #008f5d; color: #ffffff; padding: 4px 8px; border-radius: 6px; font-size: 13px; font-weight: bold; border: 1px solid #00ff88;",
            "color: #9ca3af; font-size: 12px;"
        );
    }

    // Exibe o Easter Egg no console usando o hash injetado pelo PHP
    if (typeof ROUTE_HASH !== 'undefined' && ROUTE_HASH !== '') {
        logDeveloperEasterEgg(ROUTE_HASH);
        if (cvHashElement) cvHashElement.textContent = ROUTE_HASH;
    }

    // 4. SISTEMA DE CONTROLE DE ACESSO POR WHATSAPP (OVERLAY & BLOQUEIO OTP)
    const accessOverlay = document.getElementById('accessOverlay');
    const accessForm = document.getElementById('accessForm');
    const accessMessage = document.getElementById('accessMessage');
    const submitAccessBtn = document.getElementById('submitAccessBtn');
    const phoneInput = document.getElementById('whatsappNumber');
    
    // Novas referências de passos OTP
    const stepPhone = document.getElementById('stepPhone');
    const stepCode = document.getElementById('stepCode');
    const backToPhoneBtn = document.getElementById('backToPhoneBtn');
    const goToCodeBtn = document.getElementById('goToCodeBtn');
    const otpInputs = document.querySelectorAll('.otp-input');
    const accessIcon = document.getElementById('accessIcon');

    // Máscara dinâmica de formulário para celular no Brasil (ex: (31) 99999-9999)
    if (phoneInput) {
        phoneInput.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            let match = value.match(/^(\d{0,2})(\d{0,5})(\d{0,4})$/);
            if (match) {
                e.target.value = !match[2] 
                    ? match[1] 
                    : '(' + match[1] + ') ' + match[2] + (match[3] ? '-' + match[3] : '');
            }
        });
    }

    function grantAccess(hash, phone) {
        // Mostra o botão "Novo Link" nos controles de interface
        if (resetAccessBtn) {
            resetAccessBtn.style.display = 'flex';
        }

        // Remove a tela de bloqueio
        if (accessOverlay) {
            accessOverlay.classList.add('hidden');
        }

        // Atualiza o link do rodapé de impressão para incluir hash e telefone
        if (printFooterLink) {
            // Calcula o host com o diretório base
            const hostPath = window.location.host + BASE_DIR;
            printFooterLink.innerHTML = `${hostPath}/${hash}/${phone}`;
        }
    }

    // Ação do botão "Novo Link" (Limpar sessão e redefinir)
    if (resetAccessBtn) {
        resetAccessBtn.addEventListener('click', () => {
            localStorage.removeItem('cv_access');
            // Redireciona para tirar parâmetros antigos do path (URL limpa)
            window.location.href = window.location.origin + BASE_DIR;
        });
    }

    // Decisão de Desbloqueio baseada no Servidor PHP ou cache do LocalStorage
    if (typeof ACCESS_GRANTED_FROM_SERVER !== 'undefined' && ACCESS_GRANTED_FROM_SERVER === true) {
        // Usuário acessou o link amigável index.php/HASH/TELEFONE
        localStorage.setItem('cv_access', JSON.stringify({
            hash: ROUTE_HASH,
            phone: ROUTE_PHONE,
            timestamp: Date.now()
        }));
        
        grantAccess(ROUTE_HASH, ROUTE_PHONE);
    } else {
        // Se o servidor não liberou a requisição atual, verifica se há sessão anterior no cache local
        const savedAccess = localStorage.getItem('cv_access');
        if (savedAccess) {
            try {
                const accessData = JSON.parse(savedAccess);
                if (accessData && accessData.hash && accessData.phone) {
                    // Se o hash na URL for diferente do hash da sessão salva, invalida a sessão local
                    if (typeof ROUTE_HASH !== 'undefined' && ROUTE_HASH !== '' && accessData.hash !== ROUTE_HASH) {
                        localStorage.removeItem('cv_access');
                    } else {
                        grantAccess(accessData.hash, accessData.phone);
                    }
                }
            } catch (e) {
                // Limpa registros corrompidos e mantém a tela bloqueada
                localStorage.removeItem('cv_access');
            }
        }
    }

    // Submissão do Formulário de Acesso por WhatsApp (Etapa 1)
    if (accessForm) {
        accessForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const rawPhone = phoneInput.value;
            const currentHash = (typeof ROUTE_HASH !== 'undefined' && ROUTE_HASH !== '') ? ROUTE_HASH : 'DEFAULT';
            
            // Estado visual de carregamento
            submitAccessBtn.disabled = true;
            const originalBtnContent = submitAccessBtn.innerHTML;
            submitAccessBtn.innerHTML = '<span>Verificando WhatsApp...</span> <i class="fa-solid fa-circle-notch fa-spin"></i>';
            
            // Oculta mensagens de feedback anteriores e limpa estilos inline
            accessMessage.className = 'access-message';
            accessMessage.style.display = '';

            // Dispara requisição assíncrona POST para o backend PHP
            fetch(BASE_DIR + '/process_access.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    hash: currentHash,
                    phone: rawPhone
                })
            })
            .then(response => {
                return response.text().then(text => {
                    let data = null;
                    try {
                        data = JSON.parse(text);
                    } catch (e) {
                        // Resposta não é um JSON válido (ex: vazio ou erro 500 em HTML)
                    }

                    if (!response.ok) {
                        const errorMsg = (data && data.message) 
                            ? data.message 
                            : `Erro no servidor de autenticação (Código ${response.status}).`;
                        throw new Error(errorMsg);
                    }

                    if (!data) {
                        throw new Error('Resposta inválida do servidor. Por favor, tente novamente mais tarde.');
                    }

                    return data;
                });
            })
            .then(data => {
                if (data.success) {
                    // Restaura botão original
                    submitAccessBtn.disabled = false;
                    submitAccessBtn.innerHTML = originalBtnContent;
                    
                    // Sucesso! Oculta formulário de telefone e mostra caixas de código
                    if (stepPhone) stepPhone.classList.add('hidden');
                    if (stepCode) stepCode.classList.remove('hidden');
                    
                    // Muda ícone para chave
                    if (accessIcon) accessIcon.innerHTML = '<i class="fa-solid fa-key"></i>';
                    
                    // Limpa mensagens anteriores
                    accessMessage.style.display = 'none';
                    accessMessage.className = 'access-message';
                    
                    // Foca no primeiro input de código
                    if (otpInputs[0]) otpInputs[0].focus();
                } else {
                    throw new Error(data.message || 'Falha ao processar o acesso.');
                }
            })
            .catch(error => {
                // Exibe erro na interface de forma integrada e reabilita o formulário
                accessMessage.textContent = error.message || 'Ocorreu um erro ao validar seu número. Verifique os dados e tente novamente.';
                accessMessage.classList.add('error');
                
                submitAccessBtn.disabled = false;
                submitAccessBtn.innerHTML = originalBtnContent;
            });
        });
    }

    // Botão Voltar (Etapa 2 -> Etapa 1)
    if (backToPhoneBtn) {
        backToPhoneBtn.addEventListener('click', () => {
            if (stepCode) stepCode.classList.add('hidden');
            if (stepPhone) stepPhone.classList.remove('hidden');
            
            // Reseta ícone para cadeado
            if (accessIcon) accessIcon.innerHTML = '<i class="fa-solid fa-lock"></i>';
            
            // Oculta e limpa mensagens de feedback
            accessMessage.className = 'access-message';
            accessMessage.style.display = 'none';
            accessMessage.textContent = '';
            
            // Limpa os campos de código
            otpInputs.forEach(inp => inp.value = '');
        });
    }

    // Botão "Já tenho um código" (Etapa 1 -> Etapa 2)
    if (goToCodeBtn) {
        goToCodeBtn.addEventListener('click', () => {
            if (stepPhone) stepPhone.classList.add('hidden');
            if (stepCode) stepCode.classList.remove('hidden');
            
            // Muda ícone para chave
            if (accessIcon) accessIcon.innerHTML = '<i class="fa-solid fa-key"></i>';
            
            // Oculta e limpa mensagens de feedback
            accessMessage.className = 'access-message';
            accessMessage.style.display = 'none';
            accessMessage.textContent = '';
            
            // Foca no primeiro input de código
            if (otpInputs[0]) otpInputs[0].focus();
        });
    }

    // Navegação Inteligente nos Inputs OTP
    otpInputs.forEach((input, index) => {
        // Ao digitar um caractere alfanumérico
        input.addEventListener('input', (e) => {
            // Aceita apenas letras e números
            let val = e.target.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            e.target.value = val;

            if (val.length === 1) {
                // Foca no próximo input se existir
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                } else {
                    // É o último input, executa a verificação automática
                    checkOtpCode();
                }
            }
        });

        // Eventos de teclado especiais (Backspace para retornar foco)
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace') {
                if (input.value.length === 0) {
                    // Se o campo já estiver vazio, foca no anterior e apaga
                    if (index > 0) {
                        otpInputs[index - 1].focus();
                        otpInputs[index - 1].value = '';
                        e.preventDefault();
                    }
                }
            }
        });

        // Suporte para colar o código de 6 dígitos de uma vez (Ctrl+V)
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const cleanText = pastedText.replace(/[^a-zA-Z0-9]/g, '').substring(0, 6).toUpperCase();
            
            if (cleanText.length > 0) {
                // Distribui os caracteres nos inputs
                for (let i = 0; i < cleanText.length; i++) {
                    if (otpInputs[i]) {
                        otpInputs[i].value = cleanText[i];
                    }
                }
                
                // Foca no último preenchido
                const targetFocusIdx = Math.min(cleanText.length - 1, otpInputs.length - 1);
                if (otpInputs[targetFocusIdx]) otpInputs[targetFocusIdx].focus();
                
                // Se completou 6 dígitos, dispara validação
                if (cleanText.length === 6) {
                    checkOtpCode();
                }
            }
        });
    });

    // Função de validação automática e liberação do currículo
    function checkOtpCode() {
        const enteredCode = Array.from(otpInputs).map(inp => inp.value.trim().toUpperCase()).join('');
        const currentHash = (typeof ROUTE_HASH !== 'undefined' && ROUTE_HASH !== '') ? ROUTE_HASH : 'DEFAULT';

        // Garante que o código está completo
        if (enteredCode.length !== 6) return;

        // Limpa mensagens anteriores
        accessMessage.className = 'access-message';
        accessMessage.style.display = 'none';

        // Compara com o hash aleatório salvo localmente no localStorage
        if (enteredCode === currentHash) {
            // Sucesso! Modifica ícone para sucesso verde
            if (accessIcon) accessIcon.innerHTML = '<i class="fa-solid fa-circle-check" style="color: #00ff88; filter: drop-shadow(0 0 8px rgba(0,255,136,0.3));"></i>';
            
            accessMessage.textContent = 'Código verificado com sucesso! Carregando currículo completo...';
            accessMessage.classList.add('success');
            
            // Desabilita os campos OTP
            otpInputs.forEach(inp => inp.disabled = true);
            if (backToPhoneBtn) backToPhoneBtn.style.display = 'none';

            // Salva credencial autorizada no LocalStorage para visitas futuras
            const accessData = {
                hash: enteredCode,
                phone: phoneInput.value,
                timestamp: Date.now()
            };
            localStorage.setItem('cv_access', JSON.stringify(accessData));

            // Suave desvanecimento e ocultação do overlay de bloqueio após 800ms
            setTimeout(() => {
                if (accessOverlay) {
                    accessOverlay.style.opacity = '0';
                    accessOverlay.style.transition = 'opacity 0.6s ease';
                    setTimeout(() => {
                        grantAccess(accessData.hash, accessData.phone);
                    }, 600);
                }
            }, 800);
        } else {
            // Erro! Código de acesso incorreto
            accessMessage.textContent = 'Código de verificação incorreto. Por favor, confira a mensagem no seu WhatsApp e digite novamente.';
            accessMessage.classList.add('error');
            
            // Efeito visual sutil de alerta (limpa e foca no primeiro)
            otpInputs.forEach(inp => inp.value = '');
            if (otpInputs[0]) otpInputs[0].focus();
        }
    }
});

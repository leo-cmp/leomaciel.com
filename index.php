<?php
/**
 * CURRÍCULO PORTFÓLIO LEONARDO MACIEL - CONTROLLER & VIEW
 */

require_once __DIR__ . '/config.php';

// Define o diretório base dinamicamente para suportar execução em subpastas (como /cv)
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');

$accessGranted = false;
$routeHash = '';
$routePhone = '';

// Obtém o PATH_INFO (ex: /5DV6TK ou /5DV6TK/5531996082166)
$pathInfo = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';

if (!empty($pathInfo)) {
    // Separa o hash e o telefone da rota
    $parts = explode('/', $pathInfo);
    if (count($parts) >= 1) {
        $routeHash = $parts[0];
    }
    if (count($parts) >= 2) {
        $routePhone = $parts[1];
        
        // Verifica se existe o registro correspondente no sessions.php
        if (file_exists(SESSIONS_FILE)) {
            $sessionsContent = file_get_contents(SESSIONS_FILE);
            $jsonContent = preg_replace('/^<\?php.*?\?>/s', '', $sessionsContent);
            $sessions = json_decode(trim($jsonContent), true);
            if (is_array($sessions)) {
                foreach ($sessions as $session) {
                    if ($session['hash'] === $routeHash && $session['phone'] === $routePhone) {
                        $accessGranted = true;
                        break;
                    }
                }
            }
        }
    }
}

// Se não houver hash na rota, gera um hash aleatório de 6 caracteres no PHP
if (empty($routeHash)) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $routeHash = '';
    for ($i = 0; $i < 6; $i++) {
        $routeHash .= $chars[rand(0, strlen($chars) - 1)];
    }
}

// Injetaremos a variável $accessGranted para que o JS possa desbloquear imediatamente a visualização
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Currículo Profissional de Leonardo Camargos Maciel da Purificação - Engenheiro de Software & Especialista PHP (CodeIgniter)">
    <title>Leonardo Maciel | Engenheiro de Software & Especialista PHP</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome para ícones premium -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- CSS Customizado -->
    <link rel="stylesheet" href="<?php echo $baseDir; ?>/styles.css?v=<?php echo filemtime(__DIR__ . '/styles.css'); ?>">
    
    <script>
        // Injeção de variáveis PHP seguras para o script Javascript do cliente
        const BASE_DIR = "<?php echo htmlspecialchars($baseDir, ENT_QUOTES, 'UTF-8'); ?>";
        const ACCESS_GRANTED_FROM_SERVER = <?php echo $accessGranted ? 'true' : 'false'; ?>;
        const ROUTE_HASH = "<?php echo htmlspecialchars($routeHash, ENT_QUOTES, 'UTF-8'); ?>";
        const ROUTE_PHONE = "<?php echo htmlspecialchars($routePhone, ENT_QUOTES, 'UTF-8'); ?>";
    </script>
</head>
<body>
    <!-- Controles de Interface (Ocultos na Impressão) -->
    <div class="interface-controls" id="interfaceControls">
        <button id="resetAccessBtn" class="btn-control" aria-label="Gerar acesso para outra empresa" title="Novo Link (Outra Empresa)" style="display: none;">
            <i class="fa-solid fa-user-plus"></i> <span>Novo Link</span>
        </button>
        <button id="themeToggle" class="btn-control" aria-label="Alternar tema claro/escuro" title="Alternar Tema">
            <i class="fa-solid fa-moon"></i>
        </button>
        <button id="printBtn" class="btn-control btn-primary" aria-label="Imprimir currículo em PDF" title="Salvar como PDF">
            <i class="fa-solid fa-file-pdf"></i> <span>Salvar PDF</span>
        </button>
    </div>

    <div class="resume-container">
        <!-- CABEÇALHO / HERO -->
        <header class="resume-header">
            <div class="header-main">
                <h1 class="name">Leonardo Camargos Maciel da Purificação</h1>
                <h2 class="title">Engenheiro de Software & Specialist PHP <span class="location-header">— Matozinhos / MG</span></h2>
                <p class="subtitle">15 anos de experiência prática transformando requisitos de negócio em sistemas robustos de produção.</p>
            </div>
            <div class="contact-grid">
                <div class="contact-item">
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:dev@leomaciel.com">dev@leomaciel.com</a>
                </div>
                <div class="contact-item">
                    <i class="fa-solid fa-phone"></i>
                    <a href="https://wa.me/5531996082166" target="_blank">+55 (31) 99608-2166</a>
                </div>
                <div class="contact-item">
                    <i class="fa-brands fa-github"></i>
                    <a href="https://github.com/leo-cmp" target="_blank">github.com/leo-cmp</a>
                </div>
                <div class="contact-item">
                    <i class="fa-brands fa-linkedin"></i>
                    <a href="https://linkedin.com/in/leo-cmp" target="_blank">linkedin.com/in/leo-cmp</a>
                </div>
            </div>
        </header>

        <!-- GRID DE DUAS COLUNAS PARA CONTEÚDO -->
        <div class="resume-content-layout">
            
            <!-- COLUNA ESQUERDA: PERFIL, EXPERIÊNCIA, EDUCAÇÃO -->
            <main class="column-main">
                
                <!-- PERFIL PROFISSIONAL -->
                <section class="section" id="perfil">
                    <h3 class="section-title"><i class="fa-solid fa-user-tie"></i> Resumo Profissional</h3>
                    <p class="text-block">
                        Desenvolvedor de software com <strong>mais de 15 anos de trajetória prática</strong> no desenvolvimento web, especializado no ecossistema <strong>PHP</strong> e framework <strong>CodeIgniter (2, 3 e 4)</strong>. Ao longo da minha carreira, atuei desde a concepção solo de sistemas complexos (como CRMs e portais de alta escala) até o trabalho integrado em equipes ágeis com fluxos de CI/CD modernos.
                    </p>
                    <p class="text-block">
                        Atualmente, estou <strong>cursando Engenharia de Software</strong> para solidificar e chancelar academicamente minha vasta vivência de mercado. Meu objetivo é unir a agilidade de entrega que desenvolvi na prática ao rigor teórico científico de engenharia, aprofundando-me em <strong>padrões de projeto (Design Patterns), arquitetura limpa (SOLID) e testes automatizados</strong>, elevando a qualidade e a governança de software nos times em que atuo.
                    </p>
                </section>

                <!-- EXPERIÊNCIA PROFISSIONAL -->
                <section class="section" id="experiencia">
                    <h3 class="section-title"><i class="fa-solid fa-briefcase"></i> Experiência Profissional</h3>
                    <div class="timeline">
                        <!-- BRASIL PLATAFORMAS / I3TECH -->
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <h4 class="role">Desenvolvedor PHP & DevOps</h4>
                                <span class="period">2021 — Presente</span>
                            </div>
                            <h5 class="company">i3Tech / Brasil Plataformas</h5>
                            <p class="job-description">
                                Inicialmente responsável pelo desenvolvimento <strong>solo</strong> de um CRM de Vendas de proteção veicular (com integrações ao SGA, Fipe, CEP, etc) construído sobre <strong>CodeIgniter 4</strong> e <strong>MySQL/MariaDB</strong>. Com o crescimento do projeto, integrei a equipe de desenvolvimento da <strong>i3Tech</strong> para implementar e dar manutenção à segunda versão da plataforma.
                            </p>
                            <ul class="job-bullets">
                                <li>Modelagem e desenvolvimento de regras de negócio complexas para funil de vendas, relatórios e gestão de clientes.</li>
                                <li>Desenho e implementação de infraestrutura local e em nuvem utilizando <strong>Docker</strong> e <strong>AWS</strong>.</li>
                                <li>Estruturação de pipeline de entrega contínua (CI/CD): empacotamento de imagens Docker automáticas via GitHub Actions, com deploy automático orquestrado por <strong>Watchtower</strong> e <strong>Portainer</strong> na nuvem AWS.</li>
                            </ul>
                            <div class="tag-container">
                                <span class="tag">PHP 8</span>
                                <span class="tag">CodeIgniter 4</span>
                                <span class="tag">MariaDB</span>
                                <span class="tag">Docker</span>
                                <span class="tag">AWS</span>
                                <span class="tag">CI/CD</span>
                                <span class="tag">Jira (Scrum)</span>
                            </div>
                        </div>

                        <!-- PROJETOS AUTÔNOMOS (FREELANCER / PJ) -->
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <h4 class="role">Desenvolvedor PHP Freelancer</h4>
                                <span class="period">2020 — 2021</span>
                            </div>
                            <h5 class="company">Projetos Autônomos (Consultoria & Desenvolvimento)</h5>
                            <p class="job-description">
                                Atuação independente pós-pandemia no desenvolvimento de sistemas sob medida e consultorias web para empresas e clientes finais.
                            </p>
                            <ul class="job-bullets">
                                <li>Concepção, arquitetura de banco de dados e desenvolvimento completo de painéis administrativos gerenciáveis com <strong>CodeIgniter 3/4</strong>.</li>
                                <li>Criação de APIs RESTful integradas a gateways de pagamento e plataformas de CRM.</li>
                                <li>Configuração de servidores Linux integrados com Docker para hospedagem ágil e robusta dos sistemas.</li>
                            </ul>
                            <div class="tag-container">
                                <span class="tag">PHP</span>
                                <span class="tag">CodeIgniter 3/4</span>
                                <span class="tag">MySQL</span>
                                <span class="tag">Docker</span>
                                <span class="tag">Tailwind CSS</span>
                            </div>
                        </div>

                        <!-- VELOX CONTACT CENTER -->
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <h4 class="role">Desenvolvedor Web Full Stack (PJ)</h4>
                                <span class="period">2019 — 2020</span>
                            </div>
                            <h5 class="company">Velox Contact Center</h5>
                            <p class="job-description">
                                Atuação estratégica como desenvolvedor prestador de serviços (PJ), responsável pelo desenvolvimento de sistemas internos focados em otimização de atendimento, com encerramento das atividades devido ao primeiro lockdown da pandemia.
                            </p>
                            <ul class="job-bullets">
                                <li>Concepção e desenvolvimento completo de um sistema de <strong>Base de Conhecimento interna</strong> sob medida para funcionários da Velox, reduzindo o tempo médio de atendimento (TMA) e otimizando a gestão de conhecimento corporativo.</li>
                            </ul>
                            <div class="tag-container">
                                <span class="tag">PHP</span>
                                <span class="tag">CodeIgniter 3</span>
                                <span class="tag">MySQL</span>
                                <span class="tag">jQuery</span>
                                <span class="tag">Linux</span>
                            </div>
                        </div>

                        <!-- RUMO.INFO (SUA AGÊNCIA) -->
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <h4 class="role">Sócio-Fundador & Desenvolvedor Full Stack</h4>
                                <span class="period">2017 — 2019</span>
                            </div>
                            <h5 class="company">Rumo.Info (Agência Própria)</h5>
                            <p class="job-description">
                                Atuação empreendedora à frente de agência própria de soluções digitais e comunicação visual, gerenciando projetos de ponta a ponta para clientes locais e regionais.
                            </p>
                            <ul class="job-bullets">
                                <li>Desenvolvimento e manutenção de sites institucionais dinâmicos e sistemas comerciais leves baseados em <strong>CodeIgniter 3</strong> e MySQL.</li>
                                <li>Projetos visuais multicanais: criação de identidades visuais de marcas, artes digitais e materiais publicitários físicos (impressos).</li>
                                <li>Levantamento de requisitos, prospecção e atendimento consultivo direto a clientes.</li>
                            </ul>
                            <div class="tag-container">
                                <span class="tag">PHP</span>
                                <span class="tag">CodeIgniter 3</span>
                                <span class="tag">MySQL</span>
                                <span class="tag">Design Gráfico</span>
                                <span class="tag">Empreendedorismo</span>
                            </div>
                        </div>

                        <!-- AGÊNCIA WEBDESIGN BH -->
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <h4 class="role">Desenvolvedor PHP (PJ)</h4>
                                <span class="period">2013 — 2017</span>
                            </div>
                            <h5 class="company">Agência Webdesign BH</h5>
                            <p class="job-description">
                                Atuação estratégica como desenvolvedor prestador de serviços (PJ), responsável pelo desenvolvimento e manutenção de dezenas de sites dinâmicos e sistemas gerenciáveis para clientes da agência.
                            </p>
                            <ul class="job-bullets">
                                <li>Desenvolvimento de portais institucionais dinâmicos e painéis gerenciáveis integrados ao <strong>CodeIgniter 2 e 3</strong>.</li>
                                <li>Criação de e-commerces, Landing Pages e integrações de pagamento para múltiplos segmentos de negócios.</li>
                            </ul>
                            <div class="tag-container">
                                <span class="tag">CodeIgniter 2/3</span>
                                <span class="tag">MySQL</span>
                                <span class="tag">CSS Bootstrap</span>
                                <span class="tag">SEO</span>
                            </div>
                        </div>

                        <!-- VIA EDUCAÇÃO PROFISSIONAL -->
                        <div class="timeline-item">
                            <div class="timeline-header">
                                <h4 class="role">Desenvolvedor PHP Junior/Pleno (CLT)</h4>
                                <span class="period">2011 — 2013</span>
                            </div>
                            <h5 class="company">Via Educação Profissional (Pedro Leopoldo)</h5>
                            <p class="job-description">
                                Responsável pela entrada e consolidação no mercado profissional de desenvolvimento de software em ambientes corporativos.
                            </p>
                            <ul class="job-bullets">
                                <li>Criação do site institucional da escola de cursos profissionais inteiramente do zero, utilizando <strong>PHP Puro (procedural)</strong> e MySQL.</li>
                                <li>Desenvolvimento sob medida de um <strong>painel administrativo proprietário</strong> em PHP para gerenciamento completo dos conteúdos e cursos expostos no site.</li>
                            </ul>
                            <div class="tag-container">
                                <span class="tag">PHP Puro</span>
                                <span class="tag">MySQL</span>
                                <span class="tag">HTML/CSS</span>
                                <span class="tag">Painel Gerenciável</span>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <!-- COLUNA DIREITA: HABILIDADES, EDUCAÇÃO, METODOLOGIAS -->
            <aside class="column-side">
                
                <!-- FORMAÇÃO ACADÊMICA -->
                <section class="section" id="educacao">
                    <h3 class="section-title"><i class="fa-solid fa-graduation-cap"></i> Educação</h3>
                    <div class="education-block">
                        <h4 class="degree">Bacharelado em Engenharia de Software</h4>
                        <p class="institution">Gran Faculdade (EAD)</p>
                        <p class="edu-period"><i class="fa-regular fa-calendar-days"></i> Junho de 2025 — Cursando</p>
                        <span class="badge badge-accent">Teoria de Engenharia em Consolidação</span>
                    </div>
                </section>

                <!-- HABILIDADES TÉCNICAS -->
                <section class="section" id="habilidades">
                    <h3 class="section-title"><i class="fa-solid fa-gears"></i> Competências Técnicas</h3>
                    
                    <div class="skills-category">
                        <h5>Back-end & Core</h5>
                        <ul class="skills-list">
                            <li>PHP <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></li>
                            <li>CodeIgniter 4 <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></li>
                            <li>REST APIs / Integrações <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></li>
                            <li>CodeIgniter 2 / 3 <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                        </ul>
                    </div>

                    <div class="skills-category">
                        <h5>Bancos de Dados</h5>
                        <ul class="skills-list">
                            <li>MySQL / MariaDB <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></li>
                            <li>PostgreSQL (Projetos Pontuais) <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                        </ul>
                    </div>

                    <div class="skills-category">
                        <h5>DevOps & Ambiente</h5>
                        <ul class="skills-list">
                            <li>Docker / Docker Compose <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                            <li>Git / GitHub / Bitbucket <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                            <li>Administração Servidores Linux <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                            <li>AWS (Cloud / Deploy) <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                        </ul>
                    </div>

                    <div class="skills-category">
                        <h5>Front-end</h5>
                        <ul class="skills-list">
                            <li>HTML5 / CSS3 / Vanilla JS <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></li>
                            <li>Bootstrap <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></li>
                            <li>Tailwind CSS <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                            <li>jQuery <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></span></li>
                            <li>HTMX <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i><i class="fa-regular fa-star"></i></span></li>
                            <li>Alpine.js / React / Vue / Angular <span class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i></span></li>
                        </ul>
                    </div>
                </section>

                <!-- PRÁTICAS E METODOLOGIAS -->
                <section class="section" id="praticas">
                    <h3 class="section-title"><i class="fa-solid fa-diagram-project"></i> Práticas & Processos</h3>
                    <div class="methodology-grid">
                        <div class="methodology-card">
                            <h6>Desenvolvimento Assistido por IA</h6>
                            <p>Uso avançado de assistentes de IA em linha de comando (<strong>Claude Code</strong>, <strong>Gemini CLI</strong>, <strong>Antigravity CLI</strong>, <strong>Codex CLI</strong>) integrados diretamente ao workflow de terminal Linux. Foco em aceleração de produtividade, geração rápida de testes, refatoração de código e alavancagem no domínio ágil de novas stacks front-end.</p>
                        </div>
                        <div class="methodology-card">
                            <h6>Metodologias Ágeis</h6>
                            <p>Vivência prática sólida em gestão de projetos via <strong>Scrum</strong> e <strong>Kanban</strong>, utilizando ativamente o <strong>Jira</strong> para gestão de backlogs, planejamento de sprints e acompanhamento diário.</p>
                        </div>
                        <div class="methodology-card">
                            <h6>Engenharia & Qualidade</h6>
                            <p>Em constante evolução acadêmica nas disciplinas de <strong>SOLID</strong>, <strong>Design Patterns (Padrões de Projeto)</strong>, arquiteturas limpas e <strong>testes automatizados</strong> para unir a sólida prática à melhor teoria.</p>
                        </div>
                        <div class="methodology-card">
                            <h6>Idiomas</h6>
                            <p><strong>Inglês Técnico Instrumental</strong>: Leitura e interpretação fluida de documentações técnicas, APIs, fóruns de programação e logs de erro.</p>
                        </div>
                    </div>
                </section>
            </aside>
        </div>

        <!-- RODAPÉ DA PÁGINA (Apenas Web) -->
        <footer class="resume-footer">
            <p>Este currículo foi desenvolvido com HTML5, CSS3 moderno e JS Vanilla. Pressione <strong>Ctrl + P</strong> ou clique no botão de PDF no topo para salvar a versão física A4.</p>
        </footer>

        <!-- RODAPÉ EXCLUSIVO PARA IMPRESSÃO -->
        <footer class="print-only-footer">
            <p>Para visualizar online acesse: <strong id="printFooterLink">leomaciel.com/cv/<span id="cvHash"><?php echo htmlspecialchars($routeHash, ENT_QUOTES, 'UTF-8'); ?></span></strong></p>
        </footer>
    </div>

    <!-- OVERLAY DE ACESSO RESTRITO VIA WHATSAPP (Bloqueia visualização web não autorizada) -->
    <div id="accessOverlay" class="access-overlay">
        <div class="access-card">
            <div class="access-icon" id="accessIcon">
                <i class="fa-solid fa-lock"></i>
            </div>
            
            <!-- ETAPA 1: INSERIR O WHATSAPP -->
            <div id="stepPhone" class="access-step">
                <h3 class="access-title">Acesso Restrito</h3>
                <p class="access-text">
                    Olá! Para acessar o meu currículo completo, informe seu número de WhatsApp. O código de acesso exclusivo será enviado instantaneamente para você.
                </p>
                <hr class="access-divider">
                <form id="accessForm" class="access-form">
                    <div class="input-group">
                        <label for="whatsappNumber">Seu WhatsApp</label>
                        <div class="input-wrapper">
                            <i class="fa-brands fa-whatsapp input-icon"></i>
                            <input type="tel" id="whatsappNumber" placeholder="(31) 99999-9999" required autocomplete="off">
                        </div>
                    </div>
                    <button type="submit" id="submitAccessBtn" class="btn-access">
                        <span>Obter código de acesso</span> <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    <button type="button" id="goToCodeBtn" class="btn-link">
                        Já tenho um código
                    </button>
                </form>
            </div>

            <!-- ETAPA 2: INSERIR O CÓDIGO OTP -->
            <div id="stepCode" class="access-step hidden">
                <h3 class="access-title">Digite o Código</h3>
                <p class="access-text">
                    Enviamos um código de acesso de 6 caracteres para o seu WhatsApp. Insira-o abaixo:
                </p>
                <hr class="access-divider">
                <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" pattern="[a-zA-Z0-9]" autocomplete="off" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="[a-zA-Z0-9]" autocomplete="off" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="[a-zA-Z0-9]" autocomplete="off" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="[a-zA-Z0-9]" autocomplete="off" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="[a-zA-Z0-9]" autocomplete="off" required>
                    <input type="text" class="otp-input" maxlength="1" pattern="[a-zA-Z0-9]" autocomplete="off" required>
                </div>
                <button id="backToPhoneBtn" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Voltar e alterar telefone
                </button>
            </div>

            <div id="accessMessage" class="access-message"></div>
        </div>
    </div>

    <!-- JS Customizado -->
    <script src="<?php echo $baseDir; ?>/script.js?v=<?php echo filemtime(__DIR__ . '/script.js'); ?>"></script>
</body>
</html>

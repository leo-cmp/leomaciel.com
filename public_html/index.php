<!DOCTYPE html>
<html lang="pt-BR" data-theme="dim">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leo Maciel | Desenvolvedor & Projetos</title>
    <meta name="description" content="Software Engineer & Especialista PHP. Conecte-se comigo e conheça meus projetos e parcerias.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v3 + DaisyUI v4 CDN (Tema Dim Nativo) -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.23/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome 6.5.2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .glass-panel {
            background: rgba(42, 48, 60, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .link-card {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .link-card:hover {
            transform: translateY(-2px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body class="min-h-screen bg-base-300 flex flex-col justify-between items-center text-base-content p-4 sm:p-6 selection:bg-primary selection:text-primary-content">

    <!-- Container Central -->
    <main class="w-full max-w-md my-auto py-8 flex flex-col items-center">
        
        <!-- Avatar / Header -->
        <header class="text-center mb-8 flex flex-col items-center">
            <div class="avatar mb-4">
                <div class="w-24 h-24 rounded-full ring-2 ring-primary/40 ring-offset-base-300 ring-offset-4 shadow-2xl overflow-hidden">
                    <img src="/avatar.jpg" alt="Leo Maciel" class="w-full h-full object-cover" />
                </div>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold font-heading tracking-tight text-base-content">Leo Maciel</h1>
            <p class="text-sm sm:text-base text-base-content/70 font-medium mt-1">Software Engineer & Especialista PHP</p>
            <div class="flex gap-2 mt-3">
                <span class="badge badge-sm badge-neutral gap-1.5 py-2.5 px-3">
                    <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                    Disponível para novos desafios
                </span>
            </div>
        </header>

        <!-- Seção de Links -->
        <section class="w-full flex flex-col gap-3.5" aria-label="Links Principais">
            
            <!-- Instagram -->
            <a href="https://instagram.com/leomaciel.lp" target="_blank" rel="noopener noreferrer" class="link-card glass-panel w-full p-4 rounded-2xl flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-xl group-hover:bg-pink-500 group-hover:text-white transition-colors duration-200">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-sm sm:text-base group-hover:text-primary transition-colors">Instagram</div>
                        <div class="text-xs text-base-content/50">@leomaciel.lp</div>
                    </div>
                </div>
                <div class="badge badge-sm badge-ghost font-medium text-xs gap-1 group-hover:badge-primary transition-colors">
                    Seguir <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>

            <!-- LinkedIn -->
            <a href="https://linkedin.com/in/leo-cmp" target="_blank" rel="noopener noreferrer" class="link-card glass-panel w-full p-4 rounded-2xl flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-xl group-hover:bg-blue-500 group-hover:text-white transition-colors duration-200">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-sm sm:text-base group-hover:text-primary transition-colors">LinkedIn</div>
                        <div class="text-xs text-base-content/50">in/leo-cmp</div>
                    </div>
                </div>
                <div class="badge badge-sm badge-ghost font-medium text-xs gap-1 group-hover:badge-primary transition-colors">
                    Conectar <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>

            <!-- GitHub -->
            <a href="https://github.com/leo-cmp" target="_blank" rel="noopener noreferrer" class="link-card glass-panel w-full p-4 rounded-2xl flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-base-content/10 text-base-content flex items-center justify-center text-xl group-hover:bg-base-content group-hover:text-base-300 transition-colors duration-200">
                        <i class="fa-brands fa-github"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-sm sm:text-base group-hover:text-primary transition-colors">GitHub</div>
                        <div class="text-xs text-base-content/50">Projetos & Código</div>
                    </div>
                </div>
                <div class="badge badge-sm badge-ghost font-medium text-xs gap-1 group-hover:badge-primary transition-colors">
                    Ver repos <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>

            <!-- Divisor: Parcerias -->
            <div class="divider text-xs font-semibold text-base-content/40 uppercase tracking-wider my-2">Parcerias & Projetos</div>

            <!-- Dizit -->
            <a href="https://dizit.app" target="_blank" rel="noopener noreferrer" class="link-card glass-panel w-full p-4 rounded-2xl flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-[#3a86ff]/10 text-[#3a86ff] flex items-center justify-center text-xl group-hover:bg-[#3a86ff] group-hover:text-white transition-colors duration-200">
                        <i class="fa-solid fa-laptop-code"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-sm sm:text-base group-hover:text-primary transition-colors">Dizit</div>
                        <div class="text-xs text-base-content/50">Sistemas para Empresas</div>
                    </div>
                </div>
                <div class="badge badge-sm badge-ghost font-medium text-xs gap-1 group-hover:badge-info transition-colors">
                    Conhecer <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>

            <!-- Power Criativa -->
            <a href="https://powercriativa.com.br" target="_blank" rel="noopener noreferrer" class="link-card glass-panel w-full p-4 rounded-2xl flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-red-500/10 text-red-400 flex items-center justify-center text-xl group-hover:bg-red-500 group-hover:text-white transition-colors duration-200">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-sm sm:text-base group-hover:text-primary transition-colors">Power Criativa</div>
                        <div class="text-xs text-base-content/50">Marketing & Estratégia Digital</div>
                    </div>
                </div>
                <div class="badge badge-sm badge-ghost font-medium text-xs gap-1 group-hover:badge-error transition-colors">
                    Conhecer <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>

            <!-- Divisor: Contato Direto -->
            <div class="divider text-xs font-semibold text-base-content/40 uppercase tracking-wider my-2">Contato Direto</div>

            <!-- WhatsApp -->
            <a href="https://wa.me/5531996082166" target="_blank" rel="noopener noreferrer" class="link-card glass-panel w-full p-4 rounded-2xl flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-xl group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-200">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-sm sm:text-base group-hover:text-primary transition-colors">WhatsApp</div>
                        <div class="text-xs text-base-content/50">+55 (31) 99608-2166</div>
                    </div>
                </div>
                <div class="badge badge-sm badge-ghost font-medium text-xs gap-1 group-hover:badge-success transition-colors">
                    Conversar <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>

            <!-- E-mail -->
            <a href="mailto:dev@leomaciel.com" class="link-card glass-panel w-full p-4 rounded-2xl flex items-center justify-between group">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-xl group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-200">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <div class="text-left">
                        <div class="font-semibold text-sm sm:text-base group-hover:text-primary transition-colors">E-mail</div>
                        <div class="text-xs text-base-content/50">dev@leomaciel.com</div>
                    </div>
                </div>
                <div class="badge badge-sm badge-ghost font-medium text-xs gap-1 group-hover:badge-info transition-colors">
                    Enviar <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                </div>
            </a>

        </section>

    </main>

    <!-- Footer -->
    <footer class="w-full max-w-md text-center py-6 text-xs text-base-content/50">
        <p>© 2026 Leo Maciel. Todos os direitos reservados.</p>
        <p class="mt-1">Desenvolvido com carinho &amp; alta performance.</p>
    </footer>

</body>
</html>

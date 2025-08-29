<?php
$errors = [];
$name = '';
$email = '';
$message = '';
$form_status = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['contact_form'])) {
    require_once '/var/www/.structure/library/base/utilities.php';
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));

    if ($name === '') {
        $errors[] = 'Name is required.';
    } else if (strlen($name) < 2 || strlen($name) > 128) {
        $errors[] = 'Name must be between 2 and 128 characters.';
    }
    if ($email === '' || !is_email($email)) {
        $errors[] = 'A valid email is required.';
    } else if (strlen($email) < 5 || strlen($email) > 384) {
        $errors[] = 'Email must be between 5 and 384 characters.';
    }
    if ($message === '') {
        $errors[] = 'Message cannot be empty.';
    } else if (strlen($message) < 32 || strlen($message) > 1024) {
        $errors[] = 'Message must be between 32 and 1024 characters.';
    }
    if (empty($errors)) {
        require_once '/var/www/.structure/library/memory/init.php';

        if (has_memory_cooldown("idealistic_ai_contact_form", 60 * 5)) {
            $errors[] = 'You are sending messages too quickly. Please wait a moment and try again.';
        } else {
            require_once '/var/www/.structure/library/email/init.php';
            $id = random_number();
            $services_email = services_self_email(
                $email,
                'Idealistic AI | Contact Form [ID: ' . $id . ']',
                "ID: " . $id . "\nName: " . $name . "\nEmail: " . $email . "\n\nMessage:\n" . $message
            );
            $form_status = $services_email === true;
        }
    }
}
?>
<!doctype html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>BigManage — Idealistic AI</title>

    <meta name="description"
          content="BigManage by Idealistic AI — manage companies, positions, reminders and access through natural chat across WhatsApp, Telegram, Discord and email.">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="https://www.idealistic.ai">

    <meta property="og:title" content="BigManage — Idealistic AI">
    <meta property="og:description"
          content="Control your company with natural chat — no menus, no friction. Try BigManage today.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.idealistic.ai">
    <meta property="og:image" content="https://www.idealistic.ai/.images/logoTransparent.png">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="BigManage — Idealistic AI">
    <meta name="twitter:description" content="Control your company with natural chat — no menus, no friction.">
    <meta name="twitter:image" content="https://www.idealistic.ai/.images/logoTransparent.png">

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "SoftwareApplication",
            "name": "BigManage",
            "operatingSystem": "Web",
            "url": "https://www.idealistic.ai",
            "description": "Manage companies, roles, reminders and access using natural chat across multiple platforms.",
            "publisher": {
                "@type": "Organization",
                "name": "Idealistic AI",
                "url": "https://www.idealistic.ai"
            }
        }
    </script>

    <link rel="apple-touch-icon" sizes="180x180" href="https://www.idealistic.ai/.images/apple-touch-icon.png">
    <link rel="icon" href="https://www.idealistic.ai/.images/favicon.ico" sizes="any">
    <link rel="mask-icon" href="https://www.idealistic.ai/.images/safari-pinned-tab.svg" color="#0d6efd">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link href="https://www.idealistic.ai/.design/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bg: #f7f9fc;
            --surface: #fff;
            --text: #0b2235;
            --muted: #6c757d;
            --accent: #0d6efd;
            --card-radius: 18px;
            --surface-border: rgba(11, 34, 53, 0.06);
        }

        html[data-theme='dark'] {
            --bg: #071023;
            --surface: #0b1220;
            --text: #e6eef8;
            --muted: #9fb0c8;
            --accent: #2ea6ff;
            --surface-border: rgba(255, 255, 255, 0.04);
        }

        html, body {
            height: 100%;
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial
        }

        .navbar-brand img {
            height: 36px;
            width: auto
        }

        html[data-theme='dark'] .navbar .navbar-brand .fw-bold {
            color: #ffffff !important;
        }

        .navbar {
            background: var(--surface)
        }

        .navbar .nav-link {
            color: var(--text) !important
        }

        .bg-surface {
            background: var(--surface) !important
        }

        .hero {
            min-height: 85vh;
            display: flex;
            align-items: center;
            padding: 6rem 0
        }

        .hero .lead {
            font-size: 1.05rem;
            color: var(--muted)
        }

        .hero-visual {
            border-radius: 20px;
            overflow: hidden;
            background: transparent
        }

        .hero-visual img {
            width: 100%;
            height: auto;
            display: block;
            background: transparent
        }

        section {
            padding: 4.5rem 0
        }

        .section-title {
            font-weight: 700;
            letter-spacing: -0.02em
        }

        .muted {
            color: var(--muted)
        }

        .feature-card {
            border-radius: var(--card-radius);
            transition: all .28s ease;
            height: 100%;
            display: flex;
            align-items: flex-start;
            background: var(--surface);
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.04)
        }

        html[data-theme='dark'] .feature-card {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6)
        }

        .feature-card:hover {
            transform: translateY(-6px)
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 22px;
            background: transparent !important;
            box-shadow: none !important;
        }

        .feature-card h3 {
            font-size: 1.03rem;
            margin-bottom: .35rem
        }

        .timeline {
            position: relative;
            padding-left: 1.75rem
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 14px;
            top: 8px;
            bottom: 8px;
            width: 2px;
            background: linear-gradient(180deg, var(--accent), rgba(13, 110, 253, 0.12));
            border-radius: 2px
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.6rem;
            padding-left: 2rem
        }

        .timeline-bullet {
            position: absolute;
            left: 0;
            top: 0;
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            background: var(--surface);
            border: 2px solid var(--accent);
            box-shadow: 0 6px 18px rgba(13, 110, 253, 0.06)
        }

        .contact-card {
            border-radius: 14px;
            background: var(--surface);
            box-shadow: 0 18px 40px rgba(11, 34, 53, 0.06)
        }

        html[data-theme='dark'] .contact-card {
            background: linear-gradient(180deg, var(--surface), rgba(255, 255, 255, 0.02));
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6)
        }

        .form-control {
            background: transparent;
            border: 1px solid rgba(11, 34, 53, 0.06);
            color: var(--text);
            min-height: 44px
        }

        html[data-theme='dark'] .form-control {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
            color: var(--text)
        }

        .form-control::placeholder {
            color: var(--muted);
            opacity: 1
        }

        textarea.form-control {
            min-height: 140px;
            resize: vertical;
            overflow: auto
        }

        footer {
            padding: 1.5rem 0;
            background: var(--surface);
            border-top: 1px solid var(--surface-border);
            box-shadow: none
        }

        .footer-links a {
            color: var(--muted);
            text-decoration: none
        }

        .footer-links a:hover {
            color: var(--text)
        }

        .reveal {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity .6s ease, transform .6s cubic-bezier(.2, .9, .3, 1)
        }

        .reveal.in-view {
            opacity: 1;
            transform: none
        }

        .reveal.from-top {
            transform: translateY(-12px)
        }

        .reveal.from-top.in-view {
            transform: none
        }

        @media (max-width: 991px) {
            .hero {
                min-height: 62vh;
                padding: 3.5rem 0
            }

            section {
                padding: 2.5rem 0
            }

            .feature-icon {
                width: 48px;
                height: 48px;
                font-size: 20px
            }

            .footer-links span {
                display: none
            }

            .timeline {
                padding-left: 1rem
            }

            .timeline::before {
                left: 8px
            }

            .timeline-bullet {
                left: 4px
            }

            .contact-card {
                padding: 1rem
            }

            .form-control {
                min-height: 40px
            }
        }

        @media (max-width: 575px) {
            .hero {
                min-height: 52vh
            }

            .hero-visual {
                display: none
            }

            .footer-links {
                gap: .75rem
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#home">
            <img src="https://www.idealistic.ai/.images/logoCircular.png" alt="BigManage logo">
            <span class="fw-bold">BigManage</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#usecases">How it works</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact Us</a></li>
                <li class="nav-item ms-2 d-none d-lg-inline"><a class="btn btn-outline-primary btn-sm" href="#contact">Request
                        Demo<span class="visually-hidden"> for BigManage</span></a></li>

                <li class="nav-item ms-2">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" id="languageDropdown"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-translate"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item" href="?language=english" rel="nofollow">🇬🇧 English</a></li>
                            <li><a class="dropdown-item" href="?language=greek" rel="nofollow">🇬🇷 Greek</a></li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item ms-3 d-flex align-items-center">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="themeToggle" aria-label="Toggle dark mode">
                        <label class="form-check-label ms-2 d-none d-lg-inline" for="themeToggle">Dark</label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<header id="home" class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-5 section-title reveal">Control your company with natural chat — no menus, no
                    friction.</h1>
                <p class="lead mt-3 reveal">BigManage lets teams control their company using natural chat — without
                    menus or friction. Manage positions, reminders, access, clients and workflows across WhatsApp,
                    Telegram, Discord, email and more.</p>

                <div class="d-flex gap-3 mt-4 flex-wrap reveal">
                    <a href="#contact" class="btn btn-primary btn-lg">Request Demo<span class="visually-hidden"> for BigManage</span></a>
                    <a href="#features" class="btn btn-outline-secondary btn-lg">Explore Features</a>
                </div>

                <ul class="list-unstyled mt-4 d-flex gap-4 flex-wrap muted reveal">
                    <li><strong>Multi-platform</strong> — works across chat & email</li>
                    <li><strong>Conversational</strong> — natural language commands</li>
                    <li><strong>Secure</strong> — encrypted & feature-rich</li>
                </ul>
            </div>

            <div class="col-lg-5 offset-lg-1 d-none d-lg-block">
                <div class="hero-visual p-2">
                    <img id="heroMockup" src="https://www.idealistic.ai/.images/logoTransparent.png"
                         alt="BigManage mockup (transparent)">
                </div>
            </div>
        </div>
    </div>
</header>

<main>
    <section id="features">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title reveal">Powerful features designed for teams</h2>
                    <p class="muted reveal">Everything you need to manage companies, employees, positions, access and
                        scheduling through natural chat prompts — directly from platforms you already use.</p>
                </div>
            </div>

            <div class="row g-3 align-items-stretch">
                <div class="col-md-6">
                    <div class="p-3 contact-card feature-card h-100 d-flex reveal">
                        <div class="feature-icon text-primary"><i class="bi bi-building" aria-hidden="true"></i></div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="mb-1">Company management</h3>
                            <p class="muted mb-0">Create or switch companies by name or ID (e.g. “Create a company named
                                HorizonTech”, “Switch to company AlphaCorp”). The system sets up basic structure as you
                                provide initial data.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 contact-card feature-card h-100 d-flex reveal">
                        <div class="feature-icon text-success"><i class="bi bi-people-fill" aria-hidden="true"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="mb-1">Employees & onboarding</h3>
                            <p class="muted mb-0">Add or invite employees using an email address; accept join requests.
                                BigManage finds or creates employee records from the provided email.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 contact-card feature-card h-100 d-flex reveal">
                        <div class="feature-icon text-warning"><i class="bi bi-person-badge" aria-hidden="true"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="mb-1">Positions & hierarchy</h3>
                            <p class="muted mb-0">Create, rename, delete or assign positions; manage reporting levels
                                and set relative hierarchy (above / below / equal).</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 contact-card feature-card h-100 d-flex reveal">
                        <div class="feature-icon text-info"><i class="bi bi-diagram-3" aria-hidden="true"></i></div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="mb-1">Departments & organization</h3>
                            <p class="muted mb-0">Group positions into departments, rename or delete departments, and
                                attach or detach positions to reflect company reorganizations.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 contact-card feature-card h-100 d-flex reveal">
                        <div class="feature-icon text-danger"><i class="bi bi-lock-fill" aria-hidden="true"></i></div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="mb-1">Access & time-based controls</h3>
                            <p class="muted mb-0">Manage general access and create time-bound permissions for companies,
                                positions or employees. Use natural language to set start/end times and expirations.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 contact-card feature-card h-100 d-flex reveal">
                        <div class="feature-icon text-secondary"><i class="bi bi-calendar-check" aria-hidden="true"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="mb-1">Reminders & scheduling</h3>
                            <p class="muted mb-0">Set reminders for companies, positions or individuals: one-off,
                                recurring or scheduled. Example: “Create a reminder called ‘Monthly Report’ to start in
                                60 seconds, repeat every 3600 seconds”.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row mt-5">
                <div class="col-lg-10 mx-auto">
                    <h2 class="section-title">About BigManage</h2>

                    <p class="muted">BigManage is immediately accessible via Instagram, Meta Messenger, WhatsApp,
                        Discord, Telegram and email — so you can start using the service from tools you already use,
                        without learning a new interface. Because BigManage operates through chat prompts rather than
                        menu-driven screens, most people understand how to use it in under an hour. You learn by doing:
                        send a message, see the result, and adjust — that hands-on loop makes training fast and
                        intuitive.</p>

                    <p class="muted">Think of BigManage as a helpful colleague: your messages are parsed, routed and
                        handled by the right parts of the system. Short conversational inputs can trigger complex
                        actions because the platform breaks down what you write and executes each step — just like
                        talking to a person who understands your intent. Whether you’re an individual or a company, we
                        make support personal. We publish our emails and phone numbers, respond promptly, and will
                        arrange calls or meetings when needed. Reach out anytime — if we’re unavailable we'll return
                        your message quickly to understand the issue and help find the best solution.</p>
                </div>
            </div>

        </div>
    </section>

    <section id="usecases">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="section-title reveal">How it works — in plain chat</h2>
                    <p class="muted reveal">Type or speak natural instructions — BigManage extracts intent, identifies
                        the target (company/position/person) and executes or confirms actions.</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 mx-auto bg-surface p-3 rounded-3 shadow-sm reveal">
                    <div class="timeline">

                        <div class="timeline-item">
                            <div class="timeline-bullet"><i class="bi bi-building" aria-hidden="true"></i></div>
                            <div class="ps-3">
                                <h3>Create companies</h3>
                                <p class="muted">Example: “Create a company named HorizonTech.” BigManage sets up the
                                    initial company structure automatically.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-bullet"><i class="bi bi-person-plus-fill" aria-hidden="true"></i></div>
                            <div class="ps-3">
                                <h3>Add employees & assign positions</h3>
                                <p class="muted">Example: “Add a new employee with email john.doe@example.com” —
                                    BigManage will find or create the employee and handle invites or assignments.</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-bullet"><i class="bi bi-person-bounding-box" aria-hidden="true"></i>
                            </div>
                            <div class="ps-3">
                                <h3>Create and manage positions</h3>
                                <p class="muted">Create, rename or delete positions and set who reports to whom using
                                    natural language. Example: “Create a Marketing Manager position.”</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-bullet"><i class="bi bi-lock-fill" aria-hidden="true"></i></div>
                            <div class="ps-3">
                                <h3>Access & time access</h3>
                                <p class="muted">Use commands to grant temporary access or set company/position time
                                    windows, e.g. “Set the company’s time access from 08:00 to 18:00.”</p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-bullet"><i class="bi bi-calendar-check" aria-hidden="true"></i></div>
                            <div class="ps-3">
                                <h3>Reminders</h3>
                                <p class="muted">Set reminders by conversation. Example: “Create a reminder called
                                    ‘Monthly Report’ to start in 60 seconds, repeat every 3600 seconds.”</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-6">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title reveal">Contact Us</h2>
                    <p class="muted reveal">Want a demo, pricing details or to integrate BigManage with your stack? Drop
                        a message and our team will get back to you.</p>

                    <ul class="list-unstyled mt-4 muted reveal">
                        <li><i class="bi bi-envelope-fill me-2"></i>contact@idealistic.ai</li>
                        <li><i class="bi bi-globe2 me-2"></i>https://www.idealistic.ai</li>
                        <li><i class="bi bi-geo-alt-fill me-2"></i>Europe, Athens</li>
                    </ul>
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <?php
                    if (!empty($errors)) {
                        echo '<div class="alert alert-danger">';
                        echo '<strong>There was a problem with your submission:</strong><ul class="mb-0">';
                        foreach ($errors as $e) {
                            echo '<li>' . htmlspecialchars($e) . '</li>';
                        }
                        echo '</ul></div>';
                    } else if ($form_status === true) {
                        echo '<div class="alert alert-success">Thanks — your message was received. We will contact you as soon as possible.</div>';
                    } else if ($form_status === false) {
                        echo '<div class="alert alert-warning">Your message failed to be received. Please try again later.</div>';
                    }
                    ?>

                    <form class="p-3 contact-card reveal" method="post" action="#contact" novalidate>
                        <input type="hidden" name="contact_form" value="1">

                        <div style="display:none;position:absolute;left:-9999px;">
                            <label>Website</label>
                            <input name="website" type="text" tabindex="-1" autocomplete="off"/>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input class="form-control" id="contact-name" name="name"
                                   minlength="2" maxlength="128"
                                   value="<?php echo htmlspecialchars($name); ?>" placeholder="Your name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Work Email</label>
                            <input type="email" class="form-control" id="contact-email" name="email"
                                   min="5" max="384"
                                   value="<?php echo htmlspecialchars($email); ?>" placeholder="name@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">How can we help?</label>
                            <textarea class="form-control" id="contact-message" name="message" data-autoresize rows="4"
                                      minlength="32" maxlength="1024"
                                      placeholder="Tell us briefly about your use-case..."><?php echo htmlspecialchars($message); ?></textarea>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary btn-lg" type="submit">Request Demo</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</main>

<footer>
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3 py-2">
            <img src="https://www.idealistic.ai/.images/logoCircular.png" alt="logo" height="32">
            <small class="muted">© 2025 Idealistic AI — BigManage</small>
        </div>

        <div class="py-2 footer-links d-flex align-items-center gap-3 flex-wrap">
            <a class="d-flex align-items-center gap-2"
               href="https://www.idealistic.ai/bigmanage/documentation/?language=<?php echo urlencode($_GET['language'] ?? ''); ?>"
               target="_blank" rel="noopener"><i class="bi bi-file-earmark-text"></i><span class="d-none d-md-inline">Documentation</span></a>
            <a class="d-flex align-items-center gap-2" href="https://www.idealistic.ai/instagram" target="_blank"
               rel="noopener"><i class="bi bi-instagram"></i><span class="d-none d-md-inline">Instagram</span></a>
            <a class="d-flex align-items-center gap-2" href="https://www.idealistic.ai/messenger" target="_blank"
               rel="noopener"><i class="bi bi-messenger"></i><span class="d-none d-md-inline">Messenger</span></a>
            <a class="d-flex align-items-center gap-2" href="https://www.idealistic.ai/whatsapp" target="_blank"
               rel="noopener"><i class="bi bi-whatsapp"></i><span class="d-none d-md-inline">WhatsApp</span></a>
            <a class="d-flex align-items-center gap-2" href="https://www.idealistic.ai/discord" target="_blank"
               rel="noopener"><i class="bi bi-discord"></i><span class="d-none d-md-inline">Discord</span></a>
            <a class="d-flex align-items-center gap-2" href="https://www.idealistic.ai/telegram" target="_blank"
               rel="noopener"><i class="bi bi-telegram"></i><span class="d-none d-md-inline">Telegram</span></a>
        </div>
    </div>
</footer>

<script>
    (function () {
        const root = document.documentElement;
        const toggle = document.getElementById('themeToggle');
        const stored = localStorage.getItem('site-theme');
        const heroImg = document.getElementById('heroMockup');
        const lightMock = 'https://www.idealistic.ai/.images/logoTransparent.png';
        const darkMock = 'https://www.idealistic.ai/.images/backgroundLogoTransparent.png';

        function applyTheme(theme) {
            root.setAttribute('data-theme', theme);
            if (toggle) toggle.checked = (theme === 'dark');
            if (heroImg) {
                heroImg.src = (theme === 'dark') ? darkMock : lightMock;
                heroImg.style.opacity = 0;
                setTimeout(() => heroImg.style.transition = 'opacity .4s ease', 10);
                setTimeout(() => heroImg.style.opacity = 1, 30);
            }
            document.querySelectorAll('.feature-icon').forEach(ic => {
                ic.style.background = (theme === 'dark') ? 'transparent' : 'transparent';
            });
        }

        if (stored) {
            applyTheme(stored);
        } else {
            applyTheme('light');
        }

        if (toggle) {
            toggle.addEventListener('change', function () {
                const newTheme = this.checked ? 'dark' : 'light';
                applyTheme(newTheme);
                localStorage.setItem('site-theme', newTheme);

                revealObserver && revealObserver.disconnect();
                initReveal();
            });
        }

        document.querySelectorAll('textarea[data-autoresize]').forEach(el => {
            el.style.height = Math.max(el.clientHeight, el.scrollHeight) + 'px';
            el.addEventListener('input', function () {
                if (this.scrollHeight > this.clientHeight) {
                    this.style.height = this.scrollHeight + 'px';
                }
            });
        });

        function updateFormColors() {
            const text = getComputedStyle(document.documentElement).getPropertyValue('--text');
            const muted = getComputedStyle(document.documentElement).getPropertyValue('--muted');
            document.querySelectorAll('.form-control').forEach(el => {
                el.style.color = text;
            });
            document.querySelectorAll('.muted').forEach(el => {
                el.style.color = muted;
            });
        }

        updateFormColors();
        if (toggle) toggle.addEventListener('change', updateFormColors);

        let revealObserver;

        function initReveal() {
            const reveals = document.querySelectorAll('.reveal');
            const options = {root: null, rootMargin: '0px', threshold: 0.12};

            revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const el = entry.target;
                    const elCenter = entry.boundingClientRect.top + entry.boundingClientRect.height / 2;
                    const viewportCenter = window.innerHeight / 2;
                    if (entry.isIntersecting) {
                        if (elCenter > viewportCenter) {
                            el.classList.remove('from-top');
                            el.classList.add('from-bottom');
                        } else {
                            el.classList.remove('from-bottom');
                            el.classList.add('from-top');
                        }
                        el.classList.add('in-view');
                    } else {
                        el.classList.remove('in-view');
                    }
                });
            }, options);

            reveals.forEach(r => {
                r.classList.remove('in-view', 'from-top', 'from-bottom');
                revealObserver.observe(r);
            });
        }

        initReveal();

        function adjustTimeline() {
            const tl = document.querySelector('.timeline');
            if (!tl) return;
            if (window.innerWidth <= 991) {
                tl.style.paddingLeft = '1rem';
                tl.querySelectorAll('.timeline-bullet').forEach(b => b.style.left = '4px');
            } else {
                tl.style.paddingLeft = '';
                tl.querySelectorAll('.timeline-bullet').forEach(b => b.style.left = '0');
            }
        }

        window.addEventListener('resize', adjustTimeline);
        adjustTimeline();

        const mediaReduce = window.matchMedia('(prefers-reduced-motion: reduce)');
        if (mediaReduce && mediaReduce.matches) {
            document.querySelectorAll('.reveal').forEach(el => {
                el.style.transition = 'none';
                el.classList.add('in-view');
            });
        }
    })();
</script>

<script src="https://www.idealistic.ai/.scripts/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use JustSteveKing\Resume\Builders\ResumeBuilder;
use JustSteveKing\Resume\DataObjects\Basics;
use JustSteveKing\Resume\DataObjects\Certificate;
use JustSteveKing\Resume\DataObjects\Education;
use JustSteveKing\Resume\DataObjects\Language;
use JustSteveKing\Resume\DataObjects\Location;
use JustSteveKing\Resume\DataObjects\Profile;
use JustSteveKing\Resume\DataObjects\Project;
use JustSteveKing\Resume\DataObjects\Reference;
use JustSteveKing\Resume\DataObjects\Skill;
use JustSteveKing\Resume\DataObjects\Work;
use JustSteveKing\Resume\Enums\EducationLevel;
use JustSteveKing\Resume\Enums\Network;
use JustSteveKing\Resume\Enums\SkillLevel;
use JustSteveKing\Resume\Exporters\MarkdownExporter;
use JustSteveKing\Resume\Exporters\YamlExporter;
use JustSteveKing\Resume\ValueObjects\Email;
use JustSteveKing\Resume\ValueObjects\Url;

$resume = (new ResumeBuilder)
    ->basics(new Basics(
        name: 'Nathan Rzepecki',
        label: 'Full-Stack Software Engineer | PHP & Laravel Expert',
        email: new Email('nathan@lionslair.net.au'),
        // phone: '+61 412 850 501',
        url: new Url('https://nathanrzepecki.me'),
        summary: 'Full-stack software engineer with extensive expertise in Laravel and Vue.js, specialising in building scalable web applications and API integrations. Proven track record developing healthcare platforms, payment systems, and enterprise solutions with a focus on security, performance, and maintainability. DevOps proficient with Docker, CI/CD pipelines, and cloud infrastructure (AWS). Strong advocate for privacy and data ownership — personally maintains a fully local, self-hosted home automation system using Home Assistant. Based in Western Australia; successfully working remote since 2018.',
        location: new Location(
            postalCode: '6000',
            city: 'Perth',
            countryCode: 'AU',
            region: 'Western Australia',
        ),
        profiles: [
            new Profile(
                network: Network::GitHub,
                username: 'lionslair',
                url: new Url('https://github.com/lionslair/'),
            ),
            new Profile(
                network: Network::LinkedIn,
                username: 'nathanrzepecki',
                url: new Url('https://www.linkedin.com/in/nathanrzepecki/'),
            ),
        ],
    ))

    // --- Work History ---

    ->addWork(new Work(
        name: 'DCODE GROUP - Custom Software Development',
        position: 'Software Development Lead',
        location: 'Perth, Western Australia (Remote)',
        url: new Url('https://dcodegroup.com.au'),
        startDate: '2020-10-01',
        summary: 'Leading full-stack development of SaaS and enterprise web platforms for clients across healthcare, finance, and e-commerce verticals.',
        highlights: [
            'Architected multi-tenant SaaS applications using event-driven and microservices patterns with Laravel',
            'Integrated the full AWS ecosystem (S3, Lambda, RDS, SES, Route53, CloudFront, WAF, Textract, Backup) for scalable cloud deployments',
            'Built end-to-end Stripe payment integrations including checkout, payment elements, webhooks, and PCI compliance',
            'Implemented Xero API integrations for automated accounting workflows in business management systems',
            'Developed serverless PDF generation using Browsershot/Puppeteer on AWS Lambda',
            'Established GitHub Actions CI/CD pipelines with PHPStan, Laravel Pint, and automated deployment on self-hosted runners',
            'Implemented Typesense search, MailChimp, Slack, and Mailgun webhook integrations',
            'Built full-stack features across Laravel (Nova, Horizon, Telescope, Pulse, Sanctum, Livewire, Inertia.js) and Vue.js (2.x & 3.x)',
            'Mentored developers and led code reviews to enforce coding standards and TDD practices',
        ],
    ))

    ->addWork(new Work(
        name: 'The Crowd Co',
        position: 'Lead Web Developer',
        location: 'Perth, Western Australia',
        startDate: '2018-04-01',
        endDate: '2020-10-01',
        summary: 'Led web development and project management for multiple SaaS web applications, overseeing technical decisions, team delivery, and quality assurance for a distributed engineering team.',
        highlights: [
            'Directly led a distributed engineering team of seven developers across Vietnam, Hungary, and France',
            'Owned all web-related project management including task creation and assignment in JIRA',
            'Conducted pull request reviews and QA processes across the engineering team',
            'Made all technology choices and drove system design for new and existing platforms',
            'Developed multiple web applications with the Laravel PHP Framework',
            'Built frontend interfaces using TailwindCSS, Vue.js, and Vuex for state management',
        ],
    ))

    ->addWork(new Work(
        name: 'Itomic | Web Specialists',
        position: 'Senior Developer',
        location: 'Perth, Western Australia',
        startDate: '2016-11-01',
        endDate: '2018-03-01',
        summary: 'Senior developer building Laravel and Drupal web applications for clients across various industries.',
        highlights: [
            'Developed complex web applications using the Laravel PHP Framework',
            'Built and maintained Drupal CMS websites for diverse client requirements',
            'Performed Linux server administration and maintenance',
        ],
    ))

    ->addWork(new Work(
        name: 'Harmonic New Media',
        position: 'Senior Drupal Developer',
        startDate: '2015-05-01',
        endDate: '2016-10-01',
        summary: 'Senior developer building e-commerce and informational web applications within the Drupal PHP framework.',
        highlights: [
            'Programmed web applications and e-commerce sites using the Drupal PHP framework',
            'Performed systems administration and maintenance for Linux-based servers',
            'Delivered projects ranging from simple informational sites to complex e-commerce platforms',
        ],
    ))

    ->addWork(new Work(
        name: 'Spring Web Solutions',
        position: 'Senior Analyst Programmer',
        startDate: '2011-05-01',
        endDate: '2015-05-01',
        summary: 'Senior developer building and managing websites using Drupal and Magento, with full server administration responsibilities.',
        highlights: [
            'Built and managed websites using the Drupal framework for diverse client needs',
            'Developed and deployed retail e-commerce websites using the Magento platform',
            'Administered standalone and cloud-based Linux servers',
        ],
    ))

    ->addWork(new Work(
        name: 'Indepth Interactive',
        position: 'Senior Analyst Programmer',
        startDate: '2008-01-01',
        endDate: '2011-05-01',
        summary: 'Senior software engineer developing training systems, subscription platforms, CMS solutions, and payment integrations.',
        highlights: [
            'Built online training/induction systems and subscription services with credit card gateway integrations',
            'Integrated multiple SMS interfaces and third-party API delivery systems',
            'Developed CMS platforms for various client applications',
            'Administered Linux servers across multiple projects',
        ],
    ))

    ->addWork(new Work(
        name: 'Biante Model Cars',
        position: 'Website Manager',
        startDate: '2004-01-01',
        endDate: '2007-01-01',
        summary: 'Managed and developed the e-commerce website for an Australian die-cast model car retailer.',
        highlights: [
            'Managed and maintained the company e-commerce website and product catalogue',
        ],
    ))

    // --- Education & Certifications ---

    ->addEducation(new Education(
        institution: 'Acquia',
        area: 'Drupal 7',
        studyType: EducationLevel::Other,
    ))

    ->addCertificate(new Certificate(
        name: 'Acquia Certified Developer - Drupal 7',
        date: '2017-06-01',
        issuer: 'Acquia',
    ))

    // --- Skills ---

    ->addSkill(new Skill(
        name: 'Backend Development',
        level: SkillLevel::Expert,
        keywords: ['PHP 7.2–8.5', 'Laravel 5.x–13.x', 'CakePHP', 'Symfony', 'Python', 'Node.js', 'Bash'],
    ))

    ->addSkill(new Skill(
        name: 'Laravel Ecosystem',
        level: SkillLevel::Expert,
        keywords: ['Nova', 'Horizon', 'Telescope', 'Pulse', 'Sanctum', 'Passport', 'Fortify', 'Cashier', 'Scout', 'Reverb', 'Jetstream', 'Livewire', 'Inertia.js'],
    ))

    ->addSkill(new Skill(
        name: 'Frontend Development',
        level: SkillLevel::Advanced,
        keywords: ['Vue.js 2.x & 3.x', 'TypeScript', 'Vuex', 'Alpine.js', 'TailwindCSS', 'Vuetify', 'Chart.js', 'Vite', 'Bootstrap', 'JavaScript', 'jQuery', 'CSS3'],
    ))

    ->addSkill(new Skill(
        name: 'Cloud & Infrastructure',
        level: SkillLevel::Advanced,
        keywords: ['AWS S3', 'AWS Lambda', 'AWS RDS', 'AWS SES', 'Route53', 'CloudFront', 'WAF', 'Textract', 'Laravel Forge', 'Docker', 'Docker Compose', 'Ansible', 'Terraform'],
    ))

    ->addSkill(new Skill(
        name: 'DevOps & CI/CD',
        level: SkillLevel::Advanced,
        keywords: ['GitHub Actions', 'Self-hosted runners', 'PHPStan', 'Laravel Pint', 'Rector', 'Automated deployment pipelines'],
    ))

    ->addSkill(new Skill(
        name: 'Database & Caching',
        level: SkillLevel::Expert,
        keywords: ['MySQL', 'MariaDB', 'SQL Server', 'Redis', 'Typesense', 'Valkey', 'Memcached', 'Elasticsearch', 'Solr', 'Queue systems'],
    ))

    ->addSkill(new Skill(
        name: 'Testing',
        level: SkillLevel::Advanced,
        keywords: ['Pest PHP', 'PHPUnit', 'Laravel Dusk', 'Playwright', 'TDD', 'Feature testing', 'Unit testing'],
    ))

    ->addSkill(new Skill(
        name: 'Payment Processing',
        level: SkillLevel::Advanced,
        keywords: ['Stripe', 'Stripe Terminal', 'eWay', 'PayPal', 'PCI compliance', 'Checkout', 'Payment elements', 'Webhooks'],
    ))

    ->addSkill(new Skill(
        name: 'Integrations',
        level: SkillLevel::Advanced,
        keywords: ['Xero API', 'MYOB API', 'Typesense', 'Algolia', 'MailChimp', 'Slack', 'Mailgun', 'GitHub API', 'GitHub webhooks', 'SOAP/XML web services', 'Geotab', 'MessageMedia SMS', 'Vonage'],
    ))

    ->addSkill(new Skill(
        name: 'Document Processing',
        level: SkillLevel::Advanced,
        keywords: ['DomPDF', 'FPDF', 'Spatie PDF', 'Excel import/export', 'Intervention Image', 'Spatie Media Library', 'Browsershot', 'Puppeteer'],
    ))

    ->addSkill(new Skill(
        name: 'CMS & E-commerce',
        level: SkillLevel::Expert,
        keywords: ['Drupal 6/7', 'Magento', 'WordPress (Bedrock)'],
    ))

    ->addSkill(new Skill(
        name: 'AI & Real-Time Systems',
        level: SkillLevel::Intermediate,
        keywords: ['OpenAI API', 'LLM-assisted features', 'Laravel Reverb', 'Pusher', 'Laravel Echo', 'WebSockets'],
    ))

    ->addSkill(new Skill(
        name: 'Monitoring & Observability',
        level: SkillLevel::Advanced,
        keywords: ['Bugsnag', 'Spatie Laravel Health', 'Laravel Pulse', 'Laravel Nightwatch', 'OhDear uptime monitoring'],
    ))

    ->addSkill(new Skill(
        name: 'Systems & Networking',
        level: SkillLevel::Advanced,
        keywords: ['Linux', 'Ubuntu', 'Debian', 'Nginx', 'Apache', 'SSH', 'DNS', 'SMTP', 'Firewalls'],
    ))

    // --- Projects ---

    ->addProject(new Project(
        name: 'The Trusted Trolley',
        startDate: '2013-03-01',
        endDate: '2015-05-01',
        description: 'Health food information website helping consumers identify products free from harmful additives. Built with Drupal 7, featuring Solr-powered search and a subscription-based catalogue.',
        highlights: [
            'Drupal 7 repository of food products and additives',
            'Solr integration for fast and accurate product search',
            'Subscription membership system for catalogue access',
        ],
        url: new Url('http://www.thetrustedtrolley.com.au'),
    ))

    ->addProject(new Project(
        name: 'Short Street Gallery',
        startDate: '2013-04-01',
        endDate: '2015-05-01',
        description: 'Full business management system for a premier Indigenous art gallery in Broome, WA. Built on Drupal 6 with complete back-office management including artworks, clients, artists, financials, exhibitions, reporting, and marketing.',
        highlights: [
            'Complex Drupal 6 CMS tailored to gallery business processes',
            'Full data migration from legacy FileMaker system',
            'Financial management, exhibition tracking, and marketing modules',
        ],
        url: new Url('http://shortstgallery.com.au'),
    ))

    ->addProject(new Project(
        name: 'Association of Independent Schools of Western Australia',
        startDate: '2015-01-01',
        endDate: '2016-10-01',
        description: 'Western Australian branch of the national association for independent schools. Built on Drupal 7 with a teacher professional development platform, a school job portal, and a capital grants application system handling sensitive student and school data.',
        highlights: [
            'Drupal 7 platform with role-based candidate and school portals',
            'Third-party email delivery integration for guaranteed notification tracking',
            'Personal Development Plan (PDP) system for teachers with searchable job matching and approval workflows',
            'Job portal automating applications and notifications between schools and candidates',
            'Capital Grants application system handling sensitive student and school data for funding submissions',
        ],
        url: new Url('https://www.ais.wa.edu.au'),
    ))

    ->addProject(new Project(
        name: 'Flooringlab',
        startDate: '2020-10-01',
        description: 'Multi-tenant B2B trade platform for the flooring industry, built on Laravel with Vue 3 and Livewire. Long-running lead engineering role spanning order and inventory management, warehouse operations, subscription billing, and accounting integration across a growing multi-tenant SaaS product.',
        highlights: [
            'Multi-tenant Laravel architecture (stancl/tenancy) serving isolated B2B storefronts with per-tenant domains and branding',
            'Warehouse and inventory management covering stock transfers, purchase orders, and cross-warehouse stock allocation',
            'Order and credit note lifecycle integrated with Xero for automated account code syncing and payment reconciliation',
            'Subscription billing and account-level pricing plans via Laravel Cashier, with PDF generation for orders and invoices',
            'Ownership of CI/CD: GitHub Actions workflows, Docker-based deployments, PHPStan static analysis, and Pest test suites',
        ],
        url: new Url('https://b2b.theflooringlab.com.au'),
    ))

    ->addProject(new Project(
        name: 'EPH',
        startDate: '2020-10-01',
        description: 'Operations platform for Eastern Plant Hire, a civil construction plant hire company, migrating a legacy CakePHP system to Laravel with Inertia and Vue 3. Covers equipment fleet dispatch, job and site scheduling, subcontractor management, and a mobile driver docket workflow feeding billing and payroll.',
        highlights: [
            'Incremental migration of a legacy CakePHP 2 application to Laravel, run side-by-side in production',
            'Job and site scheduling system coordinating equipment dispatch, subcontractor drivers, and material deliveries',
            'Mobile driver docket workflow with attached imagery, GPS/vehicle location tracking, and reconciliation into billing',
            'Quoting and rate management with revisioned rentals, rate groups, and client-specific pricing',
            'AWS Elastic Beanstalk deployment pipeline with GitHub Actions, alongside SMS notifications and Algolia search',
        ],
        url: new Url('https://phs-vic.ephgroup.com.au'),
    ))

    ->addProject(new Project(
        name: 'DcodePay',
        startDate: '2025-04-01',
        description: 'Stripe-backed payments API powering in-app and card-present payments for a mobile app, built on Laravel 12 with a team-based multi-tenant model. Handles payment intents, setup intents for saved payment methods, Stripe Terminal connection tokens, refunds, and fee calculation.',
        highlights: [
            'Team-scoped API for mobile clients covering login, payments, setup intents, and Stripe Terminal connection tokens',
            'Stripe integration for payment and setup intent lifecycles, webhook event handling, and refunds',
            'Service and handling fee calculation applied across payments per team',
            'Payment CSV export and API filtering for reporting and reconciliation',
            'Auto-generated API documentation (Scribe) and feature-tested endpoints backing the mobile app',
        ],
        url: new Url('https://dcodepay.com.au'),
    ))

    ->addProject(new Project(
        name: 'Kanopi',
        startDate: '2020-11-01',
        description: "DCODE Group's internal agency operations platform, built on Laravel with Vue and Inertia. Unifies client and project management, a support helpdesk, GitHub activity tracking, invoicing, and team collaboration into the company's primary day-to-day internal tool, actively developed and extended since 2020.",
        highlights: [
            'Client and project management covering contacts, project stages/milestones, and quoting and invoicing synced with Xero',
            'Support helpdesk with email-to-ticket inbox parsing, severity/status workflows, and automatic ticket creation from Bugsnag errors and Dependabot alerts',
            'GitHub integration tracking repositories, pull requests, reviews, and releases across the organisation via webhooks',
            'Timesheet and leave management with per-user and per-team reporting summaries',
            'Real-time internal chat and notifications built on Laravel Reverb/Pusher',
            'Passkey (WebAuthn) authentication alongside traditional login, via Spatie Laravel Passkeys',
            'Full-text search across clients, projects, and tickets using Typesense/Laravel Scout, with AI-assisted features via OpenAI',
            'Internal wiki and knowledge base for team documentation',
        ],
        url: new Url('https://kanopi.live'),
    ))

    ->addProject(new Project(
        name: 'Aid To Church / That Catholic Shop',
        startDate: '2020-11-01',
        description: 'Dual-brand Laravel platform combining Aid to Church in Need (ACN), a Catholic charity, with its e-commerce arm That Catholic Shop (TCS), served from two domains within a single codebase. Covers donation campaigns, mass offerings and dedications, parish management, and a full product catalog and checkout with Stripe and PayPal.',
        highlights: [
            'Domain-based multi-site routing serving both the ACN charity site and the TCS storefront from one codebase',
            'Donation and campaign management including mass offerings, dedications, gift processing, and parish/diocese relationships',
            'E-commerce catalog and checkout with products, variants, cart, coupons, and order fulfilment',
            'Payment processing via Stripe and PayPal, including failed payment tracking and reconciliation',
            'Marketing tooling covering campaigns, referrals, surveys, and form entries',
            'Xero accounting integration, Typesense search, and CSV/Excel export for reporting',
            'CMS features including blog, page builder with revisions, menus, popups, redirects, and SEO management',
        ],
        url: new Url('https://aidtochurch.org/'),
    ))
    ->addProject(new Project(
        name: 'Dynamic Trade Solutions',
        startDate: '2020-11-01',
        description: 'Field service and job management platform for a trades/electrical services business, coordinating project and task scheduling, timesheets, fleet, and materials across regional teams. Includes supplier payable integrations and a custom reporting engine for task planning, revenue, and time-on-site/travel analytics.',
        highlights: [
            'Task and job scheduling/dispatch across regional teams and project types, with templated task and quote workflows',
            'Timesheet and leave management feeding payroll pay items, alongside vehicle fleet tracking and service requests',
            'Quoting and client project management from quote acceptance through to task completion and invoicing',
            'SOAP-based payable integrations with major suppliers for materials procurement and GL account reconciliation',
            'Custom reporting engine generating task planning, revenue, and time-on-site/travel summaries via scheduled Horizon jobs',
            'Dynamic form templates for field data capture, with attachments, signatures, and notifications',
        ],
        url: new Url('https://app.dynamicts.com.au'),
    ))

    ->addProject(new Project(
        name: 'Best Rated Transport',
        startDate: '2020-10-01',
        description: 'National quote-comparison marketplace connecting customers with a network of vetted transport and removalist companies, built on Laravel. Longest-running project at DCODE Group, spanning the public tender/bidding system, transport-partner portal, and admin tooling since 2020.',
        highlights: [
            'Tender/bidding marketplace matching customer quote requests against a network of transport company partners, with automated email notifications for quotes, tender submissions, and expiring tenders',
            'Stripe Connect onboarding and payouts for transport partners, with commission tracking and GST-compliant invoicing',
            'AWS Pinpoint SMS delivery with opt-out handling, processed via Spatie webhook-client for delivery and inbound events',
            'Laravel Horizon and Telescope for queue processing and observability, with CI/CD migrated to self-hosted GitHub Actions runners and PHPStan/Larastan static analysis',
            'Short-link generation for tenders, activity logging across the quote/tender lifecycle, and automated post-job review request emails',
        ],
        url: new Url('https://bestratedtransport.com.au'),
    ))

    // --- Languages ---

    ->addLanguage(new Language(
        language: 'English',
        fluency: 'Native',
    ))

    // --- Recommendations ---

    ->addReference(new Reference(
        name: 'Tung Do, Team Leader at DCODE GROUP',
        reference: "I had the pleasure of working closely with Nathan and can confidently say that he is one of the most capable Team Leaders I have worked with.\n\nWhat sets him apart is his ability to lead by example. He consistently demonstrates professionalism, accountability, and a strong commitment to both project success and team growth. He takes responsibility not only for delivering results but also for supporting every member of the team, creating an environment where people feel valued, challenged, and motivated to grow.\n\nFrom a technical perspective, he possesses deep expertise in Laravel, PHP, Vue.js, Inertia.js, AWS, Cloud Infrastructure, CI/CD, and DevOps practices. He continuously embraces new technologies and quickly turns them into practical solutions that improve product quality, team productivity, and operational efficiency.\n\nHe also has a strong understanding of the entire software development lifecycle, from requirements gathering and solution design to development, testing, deployment, and post-release operations. His ability to bridge software engineering and DevOps enables teams to deliver reliable, scalable, and maintainable solutions with confidence.\n\nEqually important, he is always open to feedback and actively encourages constructive discussions. His commitment to building a healthy, collaborative, and growth-oriented culture makes him the kind of leader that engineers enjoy working with and learning from.\n\nI would highly recommend Nathan to any organization looking for a strong technical leader who combines excellent engineering expertise with outstanding leadership qualities.",
    ))

    ->addReference(new Reference(
        name: 'Attila Dobosi, Web Developer at DCODE GROUP',
        reference: "Nathan is hard-working and diligent with a lot of experience. I was amazed how quickly he could learned new technologies and implement to projects we were working on. He has not only good programming- but has good code and project managing skills. I could easily work with his code because he strives for clean code. He is also very good mapping people's competence in projects therefore everyone could easily finish and learn from the task they were assigned for. I can highly recommend him.",
    ))

    ->build();

// --- Output ---

if (! is_dir(__DIR__.'/output')) {
    mkdir(__DIR__.'/output', 0755, true);
}

// JSON
$json = json_encode($resume->jsonSerialize(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
file_put_contents(__DIR__.'/output/resume.json', $json);
echo "✓ output/resume.json\n";

// YAML
$yaml = (new YamlExporter)->export($resume);
file_put_contents(__DIR__.'/output/resume.yaml', $yaml);
echo "✓ output/resume.yaml\n";

// Markdown
$markdown = (new MarkdownExporter)->export($resume);
file_put_contents(__DIR__.'/output/resume.md', $markdown);
echo "✓ output/resume.md\n";

// Summary
$summary = $resume->getSummary();
echo "\n--- Resume Summary ---\n";
foreach ($summary as $key => $value) {
    $label = str_pad(str_replace('_', ' ', $key), 22);
    echo "  {$label}: ".(is_bool($value) ? ($value ? 'yes' : 'no') : $value)."\n";
}

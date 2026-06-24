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
                network: Network::Bitbucket,
                username: 'lionslair',
                url: new Url('https://bitbucket.org/lionslair/'),
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
        summary: 'Led web development and project management for multiple SaaS web applications, overseeing technical decisions, team delivery, and quality assurance.',
        highlights: [
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
        keywords: ['Nova', 'Horizon', 'Telescope', 'Pulse', 'Sanctum', 'Passport', 'Jetstream', 'Livewire', 'Inertia.js'],
    ))

    ->addSkill(new Skill(
        name: 'Frontend Development',
        level: SkillLevel::Advanced,
        keywords: ['Vue.js 2.x & 3.x', 'Vuex', 'Alpine.js', 'TailwindCSS', 'Vite', 'Bootstrap', 'JavaScript', 'jQuery', 'CSS3'],
    ))

    ->addSkill(new Skill(
        name: 'Cloud & Infrastructure',
        level: SkillLevel::Advanced,
        keywords: ['AWS S3', 'AWS Lambda', 'AWS RDS', 'AWS SES', 'Route53', 'CloudFront', 'WAF', 'Textract', 'Docker', 'Docker Compose', 'Ansible', 'Terraform'],
    ))

    ->addSkill(new Skill(
        name: 'DevOps & CI/CD',
        level: SkillLevel::Advanced,
        keywords: ['GitHub Actions', 'Self-hosted runners', 'PHPStan', 'Laravel Pint', 'Rector', 'Automated deployment pipelines'],
    ))

    ->addSkill(new Skill(
        name: 'Database & Caching',
        level: SkillLevel::Expert,
        keywords: ['MySQL', 'MariaDB', 'Redis', 'Memcached', 'Elasticsearch', 'Solr', 'Queue systems'],
    ))

    ->addSkill(new Skill(
        name: 'Testing',
        level: SkillLevel::Advanced,
        keywords: ['Pest PHP', 'PHPUnit', 'Laravel Dusk', 'TDD', 'Feature testing', 'Unit testing'],
    ))

    ->addSkill(new Skill(
        name: 'Payment Processing',
        level: SkillLevel::Advanced,
        keywords: ['Stripe', 'eWay', 'PCI compliance', 'Checkout', 'Payment elements', 'Webhooks'],
    ))

    ->addSkill(new Skill(
        name: 'Integrations',
        level: SkillLevel::Advanced,
        keywords: ['Xero API', 'Typesense', 'MailChimp', 'Slack', 'Mailgun', 'GitHub webhooks'],
    ))

    ->addSkill(new Skill(
        name: 'Document Processing',
        level: SkillLevel::Advanced,
        keywords: ['DomPDF', 'FPDF', 'Spatie PDF', 'Excel import/export', 'Intervention Image', 'Spatie Media Library', 'Browsershot', 'Puppeteer'],
    ))

    ->addSkill(new Skill(
        name: 'CMS & E-commerce',
        level: SkillLevel::Expert,
        keywords: ['Drupal 6/7', 'Magento'],
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
        description: 'Full business management system for a premier Indigenous art gallery in Broome, WA. Built on Drupal 6 with complete back-office management including artworks, clients, artists, financials, exhibitions, reporting, and marketing.',
        highlights: [
            'Complex Drupal 6 CMS tailored to gallery business processes',
            'Full data migration from legacy FileMaker system',
            'Financial management, exhibition tracking, and marketing modules',
        ],
        url: new Url('http://shortstgallery.com.au'),
    ))

    ->addProject(new Project(
        name: 'Teacher Recruitment International',
        startDate: '2014-01-01',
        description: 'Teacher placement platform connecting candidates with international schools worldwide. Built on Drupal 7 with customised email notifications via third-party delivery service, approval workflows, and job matching.',
        highlights: [
            'Drupal 7 platform with role-based candidate and school portals',
            'Third-party email delivery integration for guaranteed tracking',
            'Customised notification workflows with human-approval gates',
        ],
        url: new Url('http://www.triaust.com'),
    ))

    // --- Languages ---

    ->addLanguage(new Language(
        language: 'English',
        fluency: 'Native',
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

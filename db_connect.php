<?php
/**
 * db_connect.php
 * Handles database connection using PHP PDO with exceptions enabled.
 * Default credentials match XAMPP localhost installation.
 */

$host = 'localhost';
$db   = 'ai_solutions';
$user = 'root';
$pass = ''; // XAMPP default is empty
$charset = 'utf8mb4';

// Gemini API Key for chatbot generative mode. Leave empty to use local database-aware mode.
if (!defined('GEMINI_API_KEY')) {
    define('GEMINI_API_KEY', ''); 
}

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Bootstrapping tables silently
    try {
        // 1. upcoming_events table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `upcoming_events` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `day` VARCHAR(10) NOT NULL,
            `month` VARCHAR(50) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `location` VARCHAR(255) NOT NULL,
            `time` VARCHAR(100) NOT NULL,
            `description` TEXT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // Seed upcoming_events if empty
        $count = $pdo->query("SELECT COUNT(*) FROM `upcoming_events`")->fetchColumn();
        if ($count == 0) {
            $stmt = $pdo->prepare("INSERT INTO `upcoming_events` (`day`, `month`, `title`, `location`, `time`, `description`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute(['25', 'June 2026', 'Sunderland AI Summit 2026', 'Sunderland Software Centre', '10:00 AM BST', 'Join our engineering team for an in-depth roundtable on enterprise automation. Learn how local businesses are adopting LLM-powered virtual assistants to reduce overheads and drive efficiency.']);
            $stmt->execute(['12', 'July 2026', 'Process Automation Webinar', 'Online (Zoom)', '3:00 PM BST', 'A free, interactive demonstration of our custom Robotic Process Automation (RPA) workflows. Watch how we connect databases and automate legacy documents in real time.']);
            $stmt->execute(['05', 'Aug 2026', 'Global Hackathon 2026', 'Newcastle Helix', '09:00 AM BST', 'Showcase your development speed. Team up with developers to prototype AI applications over a 48-hour sprint. Mentoring and server credits provided by AI-Solutions.']);
        }

        // 2. gallery_events table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `gallery_events` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `subtitle` VARCHAR(255) NOT NULL,
            `image_path` VARCHAR(255) NOT NULL,
            `description` TEXT DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // Migration check to add description column if it doesn't exist on older tables
        try {
            $pdo->query("SELECT `description` FROM `gallery_events` LIMIT 1");
        } catch (\PDOException $migEx) {
            $pdo->exec("ALTER TABLE `gallery_events` ADD COLUMN `description` TEXT DEFAULT NULL;");
        }

        // Seed gallery_events if empty
        $count = $pdo->query("SELECT COUNT(*) FROM `gallery_events`")->fetchColumn();
        if ($count == 0) {
            $stmt = $pdo->prepare("INSERT INTO `gallery_events` (`title`, `subtitle`, `image_path`, `description`) VALUES (?, ?, ?, ?)");
            $stmt->execute(['AI Expo London 2025', 'Exhibition & Keynote Panel', 'images/expo_london.png', 'Our team presented our core AI virtual assistant prototypes to over 5,000 technology leaders. We detailed our natural language models and scalable cloud infrastructure.']);
            $stmt->execute(['Sunderland Startup Week', 'Pitch & Networking Event', 'images/startup_week.png', 'We hosted an executive roundtable with local founders, demonstrating how Sunderland-based businesses can deploy automation to cut overhead costs by up to 40%.']);
            $stmt->execute(['NHS NLP Pilot Kickoff', 'Technical Launch Seminar', 'images/nhs_nlp.png', 'We presented our natural language extraction software developed for local hospitals, streamlining the categorization of millions of patient files.']);
            $stmt->execute(['Fintech Automation Summit', 'Executive Roundtable Session', 'images/fintech_summit.png', 'An exclusive event highlighting safe and scalable portfolio rebalancing algorithms and machine learning frameworks for modern banking sectors.']);
            $stmt->execute(['AI Prototyping Workshop', 'Hands-on Developer Meetup', 'images/ai_workshop.png', 'A hands-on coding workshop where we helped over 100 regional developers deploy, test, and host interactive custom AI software modules.']);
            $stmt->execute(['Enterprise Data Seminar', 'Technical Training Forum', 'images/data_seminar.png', 'A detailed training session explaining our recurrent neural network stock replenishing database systems and optimized global logistic streams.']);
        } else {
            // Update default descriptions if currently empty
            $stmtUpdate = $pdo->prepare("UPDATE `gallery_events` SET `description` = ? WHERE `title` = ? AND (`description` IS NULL OR `description` = '')");
            $stmtUpdate->execute(['Our team presented our core AI virtual assistant prototypes to over 5,000 technology leaders. We detailed our natural language models and scalable cloud infrastructure.', 'AI Expo London 2025']);
            $stmtUpdate->execute(['We hosted an executive roundtable with local founders, demonstrating how Sunderland-based businesses can deploy automation to cut overhead costs by up to 40%.', 'Sunderland Startup Week']);
            $stmtUpdate->execute(['We presented our natural language extraction software developed for local hospitals, streamlining the categorization of millions of patient files.', 'NHS NLP Pilot Kickoff']);
            $stmtUpdate->execute(['An exclusive event highlighting safe and scalable portfolio rebalancing algorithms and machine learning frameworks for modern banking sectors.', 'Fintech Automation Summit']);
            $stmtUpdate->execute(['A hands-on coding workshop where we helped over 100 regional developers deploy, test, and host interactive custom AI software modules.', 'AI Prototyping Workshop']);
            $stmtUpdate->execute(['A detailed training session explaining our recurrent neural network stock replenishing database systems and optimized global logistic streams.', 'Enterprise Data Seminar']);
        }

        // 3. testimonials table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `testimonials` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `stars` INT NOT NULL,
            `text` TEXT NOT NULL,
            `author_name` VARCHAR(100) NOT NULL,
            `author_initials` VARCHAR(10) NOT NULL,
            `author_role` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // Seed testimonials if empty
        $count = $pdo->query("SELECT COUNT(*) FROM `testimonials`")->fetchColumn();
        if ($count == 0) {
            $stmt = $pdo->prepare("INSERT INTO `testimonials` (`stars`, `text`, `author_name`, `author_initials`, `author_role`) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([5, 'The AI Virtual Assistant developed by AI-Solutions has completely transformed our customer relations. We saw an immediate reduction in response times and ticket backlogs. Their team was incredibly helpful throughout the transition!', 'Sarah Jenkins', 'SH', 'Director of Operations, Apex Wealth']);
            $stmt->execute([5, 'Their Rapid Prototyping service allowed us to demonstrate our new concept to investors in record time. We secured the funding we needed thanks to the functional and high-performance MVP they delivered in just three weeks.', 'Marcus Davies', 'MD', 'Founder & CEO, TechVelo']);
            $stmt->execute([4, 'Process automation was a major hurdle for our administrative teams. AI-Solutions built a customized RPA tool that runs seamlessly in the background, freeing up our hours and eliminating manual transcription errors.', 'Emily Lawson', 'EL', 'Lead Operations Specialist, CareTrust']);
            $stmt->execute([5, 'The advanced predictive analytics engine built by AI-Solutions has given us deep visibility into our logistics. We\'ve optimized our supply runs and significantly decreased holding costs. An outstanding and affordable service.', 'Alok Kumar', 'AK', 'Global Logistics Manager, Velo Retail']);
            $stmt->execute([5, 'It is rare to find a software vendor that combines technical mastery with such a high degree of transparency. The team at AI-Solutions delivered our automation system exactly as specified, under budget, and on schedule.', 'Claire Richardson', 'CR', 'Head of Digital, Innovate UK']);
        }

        // 4. deal_requests table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `deal_requests` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `project_name` VARCHAR(255) NOT NULL,
            `client_name` VARCHAR(255) NOT NULL,
            `client_email` VARCHAR(255) NOT NULL,
            `client_phone` VARCHAR(50) DEFAULT NULL,
            `company` VARCHAR(255) DEFAULT NULL,
            `message` TEXT DEFAULT NULL,
            `requested_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 5. inquiries table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `inquiries` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `phone` VARCHAR(20) DEFAULT NULL,
            `company` VARCHAR(100) DEFAULT NULL,
            `country` VARCHAR(100) DEFAULT NULL,
            `job_title` VARCHAR(100) DEFAULT NULL,
            `job_details` TEXT DEFAULT NULL,
            `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // 6. admin_users table
        $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `password_hash` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

        // Seed Default Admin User if empty
        $adminCount = $pdo->query("SELECT COUNT(*) FROM `admin_users` WHERE `username` = 'admin'")->fetchColumn();
        if ($adminCount == 0) {
            $pdo->exec("INSERT INTO `admin_users` (`username`, `password_hash`) VALUES ('admin', '$2y$12$E0GtFTUl5ob5Xo16l7ZIvO0b4MVcwdCOUVQJ0vuUCRRnuE1Jj8CXy')");
        }

    } catch (\PDOException $e) {
        // Fail silently on setup errors
    }

} catch (\PDOException $e) {
    // Set $pdo to null to allow pages to fall back to static mockup data instead of crashing the site
    $pdo = null;
}
?>

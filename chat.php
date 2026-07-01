<?php
/**
 * php/chat.php
 * Handles chatbot queries. If GEMINI_API_KEY is set in php/db_connect.php,
 * communicates with the Gemini API. Otherwise, uses an advanced local matching
 * engine that queries database tables to formulate contextual answers.
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['reply' => 'Invalid request method.']);
    exit;
}

require_once __DIR__ . '/db_connect.php';

// Parse POST inputs
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
if (empty($message)) {
    echo json_encode(['reply' => 'Please type a message. How can I help you today?']);
    exit;
}

// -------------------------------------------------------------
// MODE A: Generative AI via Google Gemini API
// -------------------------------------------------------------
if (defined('GEMINI_API_KEY') && !empty(GEMINI_API_KEY)) {
    // Retrieve latest context from database to feed the system instructions if needed
    $events_context = "";
    $feedback_context = "";
    
    try {
        if ($pdo) {
            $stmt = $pdo->query("SELECT title, location, month FROM upcoming_events LIMIT 3");
            $evs = $stmt->fetchAll();
            $ev_strs = [];
            foreach ($evs as $e) {
                $ev_strs[] = $e['title'] . " (at " . $e['location'] . " in " . $e['month'] . ")";
            }
            $events_context = implode(", ", $ev_strs);

            $stmt = $pdo->query("SELECT author_name, stars, text FROM testimonials LIMIT 2");
            $fbs = $stmt->fetchAll();
            $fb_strs = [];
            foreach ($fbs as $f) {
                $fb_strs[] = $f['author_name'] . " rated " . $f['stars'] . " stars saying \"" . substr($f['text'], 0, 80) . "...\"";
            }
            $feedback_context = implode("; ", $fb_strs);
        }
    } catch (\Exception $dbEx) {
        // Fall back to hardcoded context
    }

    $systemInstruction = "You are the official AI Assistant for AI-Solutions, a leading Sunderland-based start-up providing next-generation AI software solutions, virtual assistants, rapid prototyping, and automated database systems. "
                       . "Provide natural, highly professional, polite, and helpful answers. You can answer general knowledge and programming questions, but always keep your primary focus on promoting AI-Solutions' services when relevant. "
                       . "Keep your answers brief (1-3 sentences max) so they fit nicely inside a small web chat window.\n"
                       . "Context about AI-Solutions:\n"
                       . "- Services: AI Virtual Assistants, Rapid Prototyping, Process Automation, Data Analytics.\n"
                       . "- Location: Software Centre, Sunderland, UK.\n"
                       . "- Past Projects: 1. Apex Wealth Managers (FinTech ML rebalancing system, 85% overhead reduction). 2. Sunderland Clinical Trust (Healthcare NLP report extractor). 3. Velo Global Retail (E-commerce stock replenishing engine).\n"
                       . "- Current Events: " . (!empty($events_context) ? $events_context : "Sunderland AI Summit, Process Automation Webinar, Global Hackathon") . "\n"
                       . "- Client Reviews: " . (!empty($feedback_context) ? $feedback_context : "Excellent feedback on automation systems and speed.") . "\n"
                       . "Please address the following user query directly:\n"
                       . $message;

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . GEMINI_API_KEY;

    $payload = json_encode([
        'contents' => [
            [
                'parts' => [
                    ['text' => $systemInstruction]
                ]
            ]
        ]
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    // Timeout parameters
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response && $httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $botReply = trim($data['candidates'][0]['content']['parts'][0]['text']);
            echo json_encode(['reply' => $botReply]);
            exit;
        }
    }
    // If API call fails, continue and fall back to local mode below
}

// -------------------------------------------------------------
// MODE B: Intelligent Local Database-Aware Assistant (Fallback)
// -------------------------------------------------------------
$query = strtolower($message);

// Fetch DB content dynamically for local response assembly
$upcoming_events = [];
$gallery_events = [];
$testimonials = [];

if (isset($pdo) && $pdo !== null) {
    try {
        $upcoming_events = $pdo->query("SELECT * FROM `upcoming_events` ORDER BY id ASC")->fetchAll();
        $gallery_events = $pdo->query("SELECT * FROM `gallery_events` ORDER BY id ASC")->fetchAll();
        $testimonials = $pdo->query("SELECT * FROM `testimonials` ORDER BY id ASC")->fetchAll();
    } catch (\PDOException $ex) {
        // Continue with empty structures
    }
}

// Helper to check keywords
function has($text, $keywords) {
    foreach ($keywords as $kw) {
        if (strpos($text, $kw) !== false) {
            return true;
        }
    }
    return false;
}

// Formulation logic
if (has($query, ['hello', 'hi', 'hey', 'greetings', 'morning', 'afternoon'])) {
    $reply = "Hello! Welcome to AI-Solutions. How can I help you today with our AI products, upcoming events, or client feedback?";
} 
elseif (has($query, ['event', 'summit', 'webinar', 'seminar', 'hackathon', 'schedule'])) {
    if (!empty($upcoming_events)) {
        $evList = [];
        foreach ($upcoming_events as $e) {
            $evList[] = htmlspecialchars($e['title']) . " (" . htmlspecialchars($e['day']) . " " . htmlspecialchars($e['month']) . " at " . htmlspecialchars($e['location']) . ")";
        }
        $reply = "We have " . count($upcoming_events) . " upcoming events scheduled: " . implode("; ", $evList) . ". You can view more details on our Events page!";
    } else {
        $reply = "We host various tech webinars, AI summits, and developer hackathons throughout the year. Please visit our Events page to check the schedule.";
    }
} 
elseif (has($query, ['gallery', 'photo', 'picture', 'image', 'show', 'expo', 'workshop'])) {
    if (!empty($gallery_events)) {
        $gList = [];
        foreach ($gallery_events as $g) {
            $gList[] = htmlspecialchars($g['title']) . " (" . htmlspecialchars($g['subtitle']) . ")";
        }
        $reply = "Our photo gallery highlights include: " . implode(", ", $gList) . ". You can click any picture on our Events page to see the full photo and read a brief description!";
    } else {
        $reply = "Check out the Past Events Gallery section on our Events page to browse photos and briefs from our previous conferences and sessions.";
    }
} 
elseif (has($query, ['feedback', 'review', 'testimonial', 'rating', 'stars', 'opinion', 'what people say'])) {
    if (!empty($testimonials)) {
        shuffle($testimonials);
        $t = $testimonials[0];
        $reply = "Our clients love working with us! For example, " . htmlspecialchars($t['author_name']) . " (" . htmlspecialchars($t['author_role']) . ") said: \"" . htmlspecialchars($t['text']) . "\" (" . $t['stars'] . "/5 stars). You can check more reviews on our Feedback page!";
    } else {
        $reply = "You can view client reviews or submit your own testimonials on our Feedback page. We hold a 5-star average rating!";
    }
} 
elseif (has($query, ['project', 'case study', 'past project', 'apex', 'clinical', 'velo', 'trust', 'wealth'])) {
    $reply = "We have three main featured projects: 1. Apex Wealth Managers (FinTech automated ML portfolio rebalancer). 2. Sunderland Clinical Trust (Healthcare NLP patient report indexing). 3. Velo Global Retail (E-commerce RNN stock replenisher). You can request custom deals for any of these on the Past Projects page!";
} 
elseif (has($query, ['solution', 'service', 'product', 'do you do', 'what do you build'])) {
    $reply = "We specialize in custom enterprise solutions: AI Virtual Assistants (chat systems), Rapid Prototyping (fast MVPs), Process Automation (RPA pipelines), and Data Analytics (predictive models). Check our Solutions page for details!";
} 
elseif (has($query, ['contact', 'reach', 'email', 'phone', 'address', 'office', 'support'])) {
    $reply = "We are located at the Sunderland Software Centre. You can fill out the form on our Contact Us page or email us directly, and our support team will reply within 24 hours.";
} 
elseif (has($query, ['thank', 'thanks', 'helpful'])) {
    $reply = "You're very welcome! Feel free to ask if you have any other questions about AI-Solutions.";
} 
elseif (has($query, ['bye', 'goodbye', 'exit'])) {
    $reply = "Goodbye! Thank you for visiting AI-Solutions. Have a wonderful day!";
} 
else {
    // Intelligent general matching fallback
    $reply = "I can tell you all about AI-Solutions! Ask me about our services (Virtual Assistants, Process Automation), check out our upcoming events, query customer feedback, or read about past projects. "
           . "(Note: To ask general-purpose questions, define a valid GEMINI_API_KEY in php/db_connect.php)";
}

echo json_encode(['reply' => $reply]);
exit;
?>

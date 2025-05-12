<?php
include 'database.php';
include 'header.php';


function extractKeywords($message) {
    // Remove common words like "price," "cost," etc.
    $commonWords = ['price', 'cost', 'available', 'stock'];
    $words = explode(' ', strtolower($message));
    $keywords = array_diff($words, $commonWords);
    return implode(' ', $keywords);
}

function getProductInfo($query) {
    global $pdo;
    $keywords = extractKeywords($query);
    $keywords = '%' . strtolower($keywords) . '%';
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE LOWER(name) LIKE ?");
        $stmt->execute([$keywords]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debugging: Log the query and results
        error_log("Query executed: SELECT * FROM products WHERE LOWER(name) LIKE '$keywords'");
        error_log("Results: " . print_r($results, true));

        return $results;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

function generateResponse($message) {
    $message = strtolower($message);

    if (strpos($message, 'hi') !== false || strpos($message, 'hello') !== false) {
        return "I am Groot! 🌱 (Hello! How can I help you with your grocery shopping today?)";
    }

    if (strpos($message, 'price') !== false || strpos($message, 'cost') !== false) {
        $products = getProductInfo($message);
        if (!empty($products)) {
            $response = "I am Groot! (Here's what I found:)\n";
            foreach ($products as $product) {
                $response .= "- {$product['name']}: ₹{$product['price']} ({$product['description']})\n";
            }
            return $response;
        } else {
            return "I am Groot! 🌱 (I couldn't find any products matching your query.)";
        }
    }

    if (strpos($message, 'available') !== false || strpos($message, 'stock') !== false) {
        $products = getProductInfo($message);
        if (!empty($products)) {
            $response = "I am Groot! (Here's the availability:)\n";
            foreach ($products as $product) {
                $status = $product['stock'] > 0 ? 'In Stock' : 'Out of Stock';
                $response .= "- {$product['name']}: {$status} ({$product['stock']} units, {$product['description']})\n";
            }
            return $response;
        } else {
            return "I am Groot! 🌱 (I couldn't find any products matching your query.)";
        }
    }

    return "I am Groot? (I'm not sure about that. Try asking about product prices or availability!)";
}

$messages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);
    $botResponse = generateResponse($userMessage);
    $messages[] = ['type' => 'user', 'text' => $userMessage];
    $messages[] = ['type' => 'bot', 'text' => $botResponse];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Groot - Grocery Chatbot</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/main.css">
    <style>

        .groot-container {
            padding-top: 100px;
            animation: fadeIn 1s ease-in-out;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        .groot-chat-container {
            flex: 1;
            background: white url('images/groot.png') no-repeat center center;
            background-size: cover;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            margin: 10px;
            display: flex;
            flex-direction: column;
        }

        .groot-chat-header {
            background: rgba(76, 175, 80, 0.9);
            color: white;
            padding: 20px;
            text-align: center;
        }

        .groot-chat-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .groot-chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.8);
        }

        .groot-message {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }

        .groot-message.bot {
            flex-direction: row;
        }

        .groot-message.user {
            flex-direction: row-reverse;
        }

        .groot-message-content {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            margin: 0 10px;
        }

        .groot-bot .groot-message-content {
            background: #e9ecef;
        }

        .groot-user .groot-message-content {
            background: #4CAF50;
            color: white;
        }

        .groot-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .groot-bot .groot-avatar {
            background: #4CAF50;
            color: white;
        }

        .groot-user .groot-avatar {
            background: #2196F3;
            color: white;
        }

        .groot-chat-input {
            padding: 20px;
            border-top: 1px solid #ddd;
            display: flex;
            background: rgba(255, 255, 255, 0.8);
        }

        .groot-chat-input input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            margin-right: 10px;
        }

        .groot-chat-input button {
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 20px;
            cursor: pointer;
        }

        .groot-chat-input button:hover {
            background: #45a049;
        }
    </style>
</head>
<body style="background-color:rgb(199, 223, 206);">
    <div class="groot-container">
        <div class="groot-chat-container">
            <div class="groot-chat-header">
                <h1>🌱 Groot - Your Grocery Assistant</h1>
            </div>
            <div class="groot-chat-messages" id="chat-messages">
                <div class="groot-message bot">
                    <div class="groot-avatar">G</div>
                    <div class="groot-message-content">
                        I am Groot! 🌱 (Hello! I'm your grocery shopping assistant. How can I help you today?)
                    </div>
                </div>
                <?php if (!empty($messages)): ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="groot-message <?php echo $message['type']; ?>">
                            <div class="groot-avatar"><?php echo $message['type'] === 'bot' ? 'G' : 'U'; ?></div>
                            <div class="groot-message-content"><?php echo htmlspecialchars($message['text']); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="groot-chat-input">
                <form method="POST" action="groot.php">
                    <input type="text" name="message" id="user-input" placeholder="Type your message..." required>
                    <button type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>s
</body>
</html>
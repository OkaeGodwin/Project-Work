<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "study_planner";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS tasks (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(50) NOT NULL,
    task TEXT NOT NULL,
    due_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    echo "Error creating table: " . $conn->error;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = htmlspecialchars($_POST['subject']);
    $task = htmlspecialchars($_POST['task']);
    $due_date = $_POST['due_date'];
    
    $stmt = $conn->prepare("INSERT INTO tasks (subject, task, due_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $subject, $task, $due_date);
    
    if ($stmt->execute()) {
        $success_message = "Task added successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fetch existing tasks
$sql = "SELECT * FROM tasks ORDER BY due_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Study Planner</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .planner-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }
        .form-section {
            flex: 1;
            min-width: 300px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .tasks-section {
            flex: 1;
            min-width: 300px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border 0.3s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #6a11cb;
        }
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        button {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .task-list {
            margin-top: 20px;
        }
        .task-item {
            background: #f8f9fa;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            border-left: 5px solid #6a11cb;
            transition: transform 0.2s;
        }
        .task-item:hover {
            transform: translateX(5px);
        }
        .task-subject {
            font-weight: 700;
            color: #6a11cb;
            margin-bottom: 5px;
        }
        .task-due {
            font-size: 0.9rem;
            color: #666;
            margin-top: 8px;
        }
        .task-due span {
            font-weight: 600;
        }
        .no-tasks {
            text-align: center;
            color: #666;
            padding: 20px;
        }
        @media (max-width: 768px) {
            .planner-container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Study Planner(1)</h1>
            <p>Organize your study tasks and never miss a deadline</p>
        </header>
        
        <div class="planner-container">
            <section class="form-section">
                <h2>Add New Task</h2>
                <?php if (isset($success_message)): ?>
                    <div class="message success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="message error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" placeholder="e.g.Programing with PHP" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="task">Task Description</label>
                        <textarea id="task" name="task" placeholder="Describe the study task..." required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="due_date">Due Date</label>
                        <input type="date" id="due_date" name="due_date" required>
                    </div>
                    
                    <button type="submit">Add To Planner</button>
                </form>
            </section>
            
            <section class="tasks-section">
                <h2>Your Study Tasks</h2>
                
                <div class="task-list">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="task-item">
                                <div class="task-subject"><?php echo htmlspecialchars($row['subject']); ?></div>
                                <div class="task-desc"><?php echo htmlspecialchars($row['task']); ?></div>
                                <div class="task-due">Due: <span><?php echo date('F j, Y', strtotime($row['due_date'])); ?></span></div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-tasks">No tasks added yet. Start by adding your first study task!</div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
    
    <script>
        // Set default due date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('due_date').valueAsDate = tomorrow;
    </script>
</body>
</html>
<?php
$conn->close();
?>
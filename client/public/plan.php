<?php
require '../../vendor/autoload.php';
require 'database.php';

use MongoDB\BSON\ObjectId;

session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$client = getMongoClient();

// Select the database and collection
$database = $client->selectDatabase('m7');
$collection = $database->selectCollection('plans');
$collection_users = $database->selectCollection('users');

$users = $collection_users->find([], ['projection' => ['username' => 1]]);
$usernames = [];
foreach ($users as $user) {
    if (isset($user['username'])) {
        $usernames[] = $user['username'];
    }
}

// Handle form submission for adding/editing/deleting plans
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $_POST['action'] ?? $input['action'];;
    $userId = $_SESSION['username'];
    $e_type = $_POST['e_type'] ?? null;
    $time = $_POST['time'] ?? null;
    $weekday = $_POST['weekday'] ?? null;
    $planId = $_POST['plan_id'] ?? null;
    
    if ($action == 'add') {
        // Add new plan
        $collection->insertOne([
            'username' => $userId,
            'time' => $time,
            'exercise_type' => $e_type,
            'weekday' => $weekday,
        ]);
        header('Location: plan.php');
        exit;
    } elseif ($action == 'edit') {
        // edit plan
        $collection->insertOne([
            'username' => $userId,
            'time' => $time,
            'exercise_type' => $e_type,
            'weekday' => $weekday,
        ]);
        header('Location: plan.php');
        exit;
    } elseif ($action == 'share') {
        // Share plan with another user
        $sharedWith = $input['shared_with'];
        
        // Delete existing plans of the target user
        $collection->deleteMany(['username' => $sharedWith]);
        
        // Fetch all plans of the current user
        $userPlans = $collection->find(['username' => $userId]);
        
        // Copy each plan to the target user
        foreach ($userPlans as $plan) {
            $collection->insertOne([
                'username' => $sharedWith,
                'time' => $plan['time'],
                'exercise_type' => $plan['exercise_type'],
                'weekday' => $plan['weekday'],
            ]);
        }
        
        echo 'success';
        exit;
    } elseif ($action == 'clear-all') {
        // Clear all plans for the user
        $collection->deleteMany(['username' => $userId]);
        $plans = $collection->find(['username' => $_SESSION['username']]);
        echo 'success';
        exit;
    }
}

// Fetch user's plans
$plans = $collection->find(['username' => $_SESSION['username']]);
// echo json_encode(iterator_to_array($plans));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitHub - Your Workout Plan</title>
    <link rel="stylesheet" href="../../common/assets/stylesheets/plan.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="background">
        <div class="overlay">
            <header>
                <img src="../../common/assets/pics/logo.png" alt="FitHub Logo" class="logo">
                <div class="welcome-message">
                    <?php
                        $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
                        echo "Hi $username, Build Your Plan!";
                    ?>
                </div>
                <a href="logout.php"><img src="../../common/assets/pics/logout-icon.jpg" alt="Logout" class="logout-text"></a>
            </header>
            
            <script>
                // Pass the usernames to JavaScript
                const usernames = <?php echo json_encode($usernames); ?>;
            </script>

            <div class="content-box">
                <table class="plan-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Sunday</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody id="plan-table-body">
                        <tr>
                            <th>Morning</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Afternoon</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Evening</th>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="action-buttons">
                    <button class="add-btn">Add</button>
                    <button class="edit-btn">Edit</button>
                    <button class="share-btn">Share</button>
                    <button class="clear-btn">Clear All</button>
                </div>
            </div>
            
            <div class="dialog-box" id="dialog-box">
                <h2>Add/Edit Workout Plan</h2>
                <form id="workout-form" method="POST" action="plan.php">
                    <div class="form-group">
                        <label for="time">Time:</label>
                        <select id="time" name="time" required>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Evening">Evening</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="e_type">Type:</label>
                        <select name="e_type">
                            <option value="Cardio">Cardio</option>
                            <option value="Biceps">Biceps</option>
                            <option value="Triceps">Triceps</option>
                            <option value="Back">Back</option>
                            <option value="Chest">Chest</option>
                            <option value="Shoulders">Shoulders</option>
                            <option value="Legs">Legs</option>
                            <option value="Core">Core</option>
                            <option value="Push Day">Push Day</option>
                            <option value="Pull Day">Pull Day</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="weekday">Day:</label>
                        <select id="weekday" name="weekday" required>
                            <option value="Sunday">Sunday</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                        </select>
                    </div>
                    <button type="submit" class="submit-btn" name='action' value="add">Save Plan</button>
                    <button type="submit" class="e-submit-btn" name='action' value="edit">Edit Plan</button>
                    <button type="button" class="close-btn">Close</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Populate the table with existing plans
        const plans = <?php echo json_encode(iterator_to_array($plans)); ?>;
        const timeRowMap = {
            'Morning': 0,
            'Afternoon': 1,
            'Evening': 2
        };
        const dayColumnMap = {
            'Sunday': 1,
            'Monday': 2,
            'Tuesday': 3,
            'Wednesday': 4,
            'Thursday': 5,
            'Friday': 6,
            'Saturday': 7
        };

        plans.forEach(plan => {
            const rowIndex = timeRowMap[plan.time];
            const colIndex = dayColumnMap[plan.weekday];
            const tableBody = document.getElementById('plan-table-body');
            const row = tableBody.rows[rowIndex];
            const cell = row.cells[colIndex];
            cell.innerHTML = `
                <div class="gray-box">
                    ${plan.exercise_type}
                    <br />
                </div>
            `;
        });

       document.getElementsByClassName('add-btn')[0].addEventListener('click', function() {
            document.getElementById('dialog-box').style.display = 'block';
            document.getElementById('workout-form').reset();
            document.getElementsByClassName('submit-btn')[0].style.display = 'block';
            document.getElementsByClassName('e-submit-btn')[0].style.display = 'none';
        });
        document.getElementsByClassName('close-btn')[0].addEventListener('click', function() {
            document.getElementById('dialog-box').style.display = 'none';
        });

        document.querySelectorAll('.share-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Create the dropdown box
                const dropdownBox = document.createElement('div');
                dropdownBox.id = 'dropdown-box';
                dropdownBox.style.display = 'block';
                dropdownBox.style.position = 'absolute';
                dropdownBox.style.backgroundColor = '#fff';
                dropdownBox.style.border = '1px solid #ccc';
                dropdownBox.style.padding = '10px';

                // Create the dropdown select element
                const select = document.createElement('select');
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "";
                    select.appendChild(option);
                usernames.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user;
                    option.textContent = user;
                    select.appendChild(option);
                });

                // Append the select element to the dropdown box
                dropdownBox.appendChild(select);

                // Append the dropdown box to the body
                document.body.appendChild(dropdownBox);

                // Position the dropdown box near the clicked button
                const rect = button.getBoundingClientRect();
                dropdownBox.style.top = `${rect.bottom + window.scrollY}px`;
                dropdownBox.style.left = `${rect.left + window.scrollX}px`;

                // Add functionality to handle the selected username
                select.addEventListener('change', function() {
                    alert(`Selected user: ${select.value}`);
                    // Add your share functionality here
                    const selectedUser = select.value;
                    const time = button.dataset.time; // Assuming data-time attribute is set on the button
                    const weekday = button.dataset.weekday; // Assuming data-weekday attribute is set on the button
                    const exercise = button.dataset.exercise; // Assuming data-exercise attribute is set on the button
                    
                    if (confirm('Are you sure you want to share with this person?')) {
                        fetch('plan.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                action: 'share',
                                shared_with: selectedUser
                            }),
                        })
                        .then(response => response.text())
                        .then(data => {
                            if (data === 'success') {
                                alert("Successfully shared plans!");
                            } else {
                                alert('Failed to share plans');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }

                });
            });
        });

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                
                const dialogBox = document.getElementById('dialog-box');
                dialogBox.style.display = 'block';

                document.getElementsByClassName('submit-btn')[0].style.display = 'none';
                document.getElementsByClassName('e-submit-btn')[0].style.display = 'block';

                // Change the button text to "Edit Plan"
                const editButton = dialogBox.querySelector('.e-submit-btn');
                // saveButton.textContent = 'Edit Plan';
            });
        });

        // Clear All button functionality
        document.getElementsByClassName('clear-btn')[0].addEventListener('click', function() {
            const userId = '<?php echo $_SESSION['username'] ?>'; // Get the user ID from the session

            // Send AJAX request to clear all plans for the user
            if (confirm('Are you sure you want to delete all plans?')) {
                fetch('plan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=clear-all`,
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        // Clear the table only if the server response is successful
                        const tableBody = document.getElementById('plan-table-body');
                        for (let row of tableBody.rows) {
                            for (let cellIndex = 1; cellIndex < row.cells.length; cellIndex++) { // Start from 1 to skip the Time column
                                row.cells[cellIndex].innerHTML = '';
                            }
                        }
                        alert("Successfully deleted all plans!");
                    } else {
                        console.error('Failed to clear plans');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
        
    </script>
</body>
</html>
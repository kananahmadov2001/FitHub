<?php
session_start();
if (!isset($_SESSION['workouts'])) {
    $_SESSION['workouts'] = [];
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    if ($_POST['action'] === 'updateProgress') {
        $type = $_POST['type'];
        $duration = floatval($_POST['duration']);
        $points = 0;

        switch ($type) {
            case 'cardio':
                $points = 1;
                break;
            case 'upper-body':
                $points = 2;
                break;
            case 'legs':
                $points = 3;
                break;
        }

        $points *= $duration;
        $_SESSION['workouts'][] = [
            'type' => $type,
            'duration' => $duration,
            'points' => $points
        ];

        $totalHours = 0;
        $totalPoints = 0;
        foreach ($_SESSION['workouts'] as $workout) {
            $totalHours += $workout['duration'];
            $totalPoints += $workout['points'];
        }

        echo json_encode(['totalHours' => $totalHours, 'totalPoints' => $totalPoints]);
        exit;
    } elseif ($_POST['action'] === 'resetProgress') {
        $_SESSION['workouts'] = [];
        echo json_encode(['totalHours' => 0, 'totalPoints' => 0]);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>FitHub - Challenge</title>
      <link rel="stylesheet" href="../../common/assets/stylesheets/challenge.css">
      <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
  </head>

  <body>
      <div class="background">
          <div class="overlay">
              <header>
                <img src="../../common/assets/pics/logo.png" alt="FitHub Logo" class="logo">
                <a href="logout.php"><img src="../../common/assets/pics/logout-icon.jpg" alt="Logout" class="icon"></a>
              </header>

              <h1 class="challenge-title">Hello <?php echo $username; ?>, Here is your weekly challenge!</h1>

              <div class="challenge-rules">
                  <ul>
                      <li>Choose your workout type: Cardio, Upper Body, or Legs.</li>
                      <li>Enter the duration of your workout in hours.</li>
                      <li>Points are calculated based on the type and duration of the workout:
                          <ul>
                              <li>Cardio: 1 point per hour</li>
                              <li>Upper Body: 2 points per hour</li>
                              <li>Legs: 3 points per hour</li>
                          </ul>
                      </li>
                      <li>Accumulate points to reach the finish line at 60 points!</li>
                      <li>You can reset your progress at any time.</li>
                  </ul>
              </div>

              <form id="workout-form">
                  <label for="type">Workout Type:</label>
                  <select id="type" name="type" required>
                      <option value="">Select</option>
                      <option value="cardio">Cardio</option>
                      <option value="upper-body">Upper Body</option>
                      <option value="legs">Legs</option>
                  </select>

                  <label for="duration">Duration (hours):</label>
                  <input type="number" id="duration" name="duration" min="0.5" step="0.5" required>

                  <button type="submit" class="submit-btn">Submit</button>
                  <button type="button" id="reset-btn" class="reset-btn">Reset</button>
              </form>

              <div class="progress-container">
                  <div class="start">Start</div>
                  <div class="progress-bar">
                      <div class="progress-indicator" id="progress-indicator"></div>
                  </div>
                  <div class="finish">Finish</div>
                  <p class="total-hours">Total Hours: <span id="total-hours">0</span> hours</p>
              </div>

              <div class="congratulations-message" id="congratulations-message">
                  Congratulations, <span id="congratulations-username"><?php echo $username; ?></span>! You've completed the challenge!
              </div>
          </div>
      </div>

      <script>
          document.addEventListener('DOMContentLoaded', function () {
              const form = document.getElementById('workout-form');
              const progressIndicator = document.getElementById('progress-indicator');
              const totalHoursElem = document.getElementById('total-hours');
              const congratulationsMessage = document.getElementById('congratulations-message');
              const resetBtn = document.getElementById('reset-btn');

              form.addEventListener('submit', function (event) {
                  event.preventDefault();

                  const formData = new FormData(form);
                  formData.append('action', 'updateProgress');

                  fetch('challenge.php', {
                      method: 'POST',
                      body: formData
                  })
                  .then(response => {
                      if (!response.ok) {
                          throw new Error(`HTTP error! status: ${response.status}`);
                      }
                      return response.json();
                  })
                  .then(data => {
                      console.log(data);
                      if (data && data.totalHours !== undefined && data.totalPoints !== undefined) {
                          totalHoursElem.textContent = data.totalHours;
                          const progressPercentage = (data.totalPoints / 60) * 100;
                          progressIndicator.style.width = `${progressPercentage}%`;

                          if (data.totalPoints >= 60) {
                              congratulationsMessage.style.display = 'block';
                              startConfetti();
                          } else {
                              congratulationsMessage.style.display = 'none';
                          }
                      } else {
                          console.error('Unexpected response:', data);
                      }
                  })
                  .catch(error => console.error('Error:', error));
              });

              resetBtn.addEventListener('click', function () {
                  fetch('challenge.php', {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/x-www-form-urlencoded',
                      },
                      body: new URLSearchParams({ action: 'resetProgress' })
                  })
                  .then(response => {
                      if (!response.ok) {
                          throw new Error(`HTTP error! status: ${response.status}`);
                      }
                      return response.json();
                  })
                  .then(data => {
                      console.log(data);
                      totalHoursElem.textContent = data.totalHours;
                      progressIndicator.style.width = '0%';
                      congratulationsMessage.style.display = 'none';
                      stopConfetti();
                  })
                  .catch(error => console.error('Error:', error));
              });

              function startConfetti() {
                  const confettiContainer = document.createElement('div');
                  confettiContainer.className = 'confetti-container';
                  document.body.appendChild(confettiContainer);

                  for (let i = 0; i < 100; i++) {
                      const confetti = document.createElement('div');
                      confetti.className = 'confetti';
                      confetti.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`;
                      confetti.style.left = `${Math.random() * 100}vw`;
                      confettiContainer.appendChild(confetti);
                  }

                  setTimeout(() => {
                      stopConfetti();
                  }, 5000);
              }

              function stopConfetti() {
                  const confettiContainer = document.querySelector('.confetti-container');
                  if (confettiContainer) {
                      confettiContainer.remove();
                  }
              }
          });
      </script>
  </body>
</html>

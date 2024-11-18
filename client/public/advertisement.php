<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitHub Advertisement</title>
    <link rel="stylesheet" href="../../common/assets/stylesheets/advertisement.css">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
</head>
<body>
    <div class="background">
        <header>
            <img src="../../common/assets/pics/logo.png" alt="FitHub Logo" class="logo">
            <a href="logout.php" class="logout-text"><img src="../../common/assets/pics/logout-icon.jpg" alt="Logout" class="icon"></a>
        </header>

        <div class="welcome-message">
            <?php
            session_start();
            $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
            echo "Hi $username, Here are the Workout Plans for Each Muscle Group!";
            ?>
        </div>
        
        <div class="flashcard-container">
          <img id="prev-flashcard" class="flashcard-shadow" src="" alt="Previous Flashcard">
          <button class="arrow-left" onclick="previousFlashcard()">&#8249;</button>
          <img id="flashcard-image" src="../../common/assets/pics/flashcard_biceps.png" alt="Flashcard Image">
          <button class="arrow-right" onclick="nextFlashcard()">&#8250;</button>
          <img id="next-flashcard" class="flashcard-shadow" src="" alt="Next Flashcard">
        </div>
        
        <div class="cta-container">
            <div class="cta-message">
                <p>Build your Plan and See the Challenge</p>
            </div>
            <div class="cta-buttons">
                <button onclick="buildPlan()">Build Your Plan</button>
                <button onclick="seeChallenge()">Challenge</button>
            </div>
        </div>
    </div>
    
    <script>
      let currentFlashcardIndex = 0;
      const flashcardImages = [
          "../../common/assets/flashcards/flashcard_bicep.png",
          "../../common/assets/flashcards/flashcard_tricep.png",
          "../../common/assets/flashcards/flashcard_back.png",
          "../../common/assets/flashcards/flashcard_shoulder.png",
          "../../common/assets/flashcards/flashcard_legs.png",
          "../../common/assets/flashcards/flashcard_splits.png",
          "../../common/assets/flashcards/flashcard_push.png",
          "../../common/assets/flashcards/flashcard_pull.png",
          "../../common/assets/flashcards/flashcard_leg.png"
      ];

      function updateFlashcard() {
        const flashcardImageElement = document.getElementById('flashcard-image');
        const prevFlashcardElement = document.getElementById('prev-flashcard');
        const nextFlashcardElement = document.getElementById('next-flashcard');

        flashcardImageElement.src = flashcardImages[currentFlashcardIndex];

        const prevIndex = (currentFlashcardIndex - 1 + flashcardImages.length) % flashcardImages.length;
        const nextIndex = (currentFlashcardIndex + 1) % flashcardImages.length;

        prevFlashcardElement.src = flashcardImages[prevIndex];
        nextFlashcardElement.src = flashcardImages[nextIndex];
      }

      function nextFlashcard() {
          currentFlashcardIndex = (currentFlashcardIndex + 1) % flashcardImages.length;
          updateFlashcard();
      }

      function previousFlashcard() {
          currentFlashcardIndex = (currentFlashcardIndex - 1 + flashcardImages.length) % flashcardImages.length;
          updateFlashcard();
      }

      function buildPlan() {
          <?php if(isset($_SESSION['username']) && $_SESSION['username'] !== 'Guest'): ?>
              window.location.href = 'plan.php';
          <?php else: ?>
              alert("You must log in or register first to access 'Build Your Plan' or 'Challenge'.");
              window.location.href = 'login.php';
          <?php endif; ?>
      }

      function seeChallenge() {
          <?php if(isset($_SESSION['username']) && $_SESSION['username'] !== 'Guest'): ?>
              window.location.href = 'challenge.php';
          <?php else: ?>
              alert("You must log in or register first to access 'Build Your Plan' or 'Challenge'.");
              window.location.href = 'login.php';
          <?php endif; ?>
      }

      document.addEventListener('DOMContentLoaded', updateFlashcard);
    </script>

</body>
</html>

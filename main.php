<?php
// Define the base title for the page
$pageTitle = "Event Scheduler";

// Get the current tab from the URL, default to 'home'
$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'home';

// Define allowed tabs to prevent unauthorized file inclusions
$allowedTabs = ['home', 'add', 'update', 'delete'];

// Validate the tab parameter
if (!in_array($currentTab, $allowedTabs)) {
  $currentTab = 'home';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle; ?> - <?php echo ucfirst($currentTab); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen">
  <div class="flex">
    <!-- Sidebar -->
    <div class="w-64 bg-blue-800 text-white min-h-screen p-4">
      <h1 class="text-2xl font-bold mb-8 pb-4 border-b border-blue-600"><?php echo $pageTitle; ?></h1>
      <nav>
        <ul class="space-y-2">
          <?php
          // Generate navigation links
          foreach ($allowedTabs as $tab) {
            $activeClass = ($tab === $currentTab) ? 'bg-blue-900' : 'hover:bg-blue-700';
            echo '<li>';
            echo '<a href="?tab=' . $tab . '" class="block px-4 py-2 rounded ' . $activeClass . '">';
            echo ucfirst($tab);
            echo '</a>';
            echo '</li>';
          }
          ?>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8">
      <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-blue-800 mb-6 pb-2 border-b">
          <?php echo ucfirst($currentTab); ?> Page
        </h2>

        <?php
        // Include the correct file based on the current tab
        include $currentTab . '.php';
        ?>
      </div>
    </div>
  </div>
</body>

</html>
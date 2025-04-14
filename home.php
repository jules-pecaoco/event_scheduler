<?php
// Get all events from the file using the function from your original code
function getEvents() {
    $eventsFile = 'events/events.txt';
    $events = [];

    if (file_exists($eventsFile)) {
        $lines = file($eventsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        for ($i = 0; $i < count($lines); $i += 4) {
            if (isset($lines[$i]) && isset($lines[$i + 1]) && isset($lines[$i + 2])) {
                $eventName = str_replace("Event Name: ", "", $lines[$i]);
                $eventDetails = str_replace("Details: ", "", $lines[$i + 1]);
                $eventDateTime = str_replace("Date and Time: ", "", $lines[$i + 2]);
                $events[] = [
                    'name' => $eventName,
                    'details' => $eventDetails,
                    'dateTime' => $eventDateTime,
                ];
            }
        }
    }
    return $events;
}

$events = getEvents();
?>

<div class="home-content">
    <h3 class="text-lg font-medium text-gray-700 mb-4">Welcome to Event Scheduler</h3>

    <div class="bg-blue-50 p-4 rounded-lg mb-6">
        <p class="text-blue-700">Your upcoming events at a glance</p>
    </div>

    <div class="events-container">
        <h4 class="text-md font-semibold text-gray-800 mb-3">Latest Events</h4>
        <?php if (empty($events)): ?>
            <p class="text-gray-500">No events found.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php
                function formatDate($dateTime) {
                    return date('F j, Y, g:i A', strtotime($dateTime));
                };
                ?>
                <?php foreach (array_slice($events, 0, 4) as $event): ?>
                    <div class="bg-gray-50 rounded-md shadow-sm p-4 border-y-2 border-r-2 border-l-4 border-blue-500">
                        <h5 class="text-lg font-semibold text-blue-700"><?= htmlspecialchars($event['name']) ?></h5>
                        <p class="text-gray-600 mb-2"><?= htmlspecialchars($event['details']) ?></p>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars(formatDate($event['dateTime'])) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($events) > 4): ?>
                <div class="mt-4 text-right">
                    <span class="text-blue-600 text-sm">+ <?= count($events) - 4 ?> more events</span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<?php
// Function to get all events
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
                    'index' => $i / 4  // Store the index for identification
                ];
            }
        }
    }
    return $events;
}

// Function to update an event
function updateEvent($index, $newName, $newDetails, $newDateTime) {
    $eventsFile = 'events/events.txt';

    if (file_exists($eventsFile)) {
        $lines = file($eventsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        // Calculate the starting line for this event
        $startLine = $index * 4;

        // Check if the index is valid
        if (isset($lines[$startLine]) && isset($lines[$startLine + 1]) && isset($lines[$startLine + 2])) {
            $lines[$startLine] = "Event Name: " . $newName;
            $lines[$startLine + 1] = "Details: " . $newDetails;
            $lines[$startLine + 2] = "Date and Time: " . $newDateTime;

            // Write the updated content back to the file
            file_put_contents($eventsFile, implode("\n", $lines));
            return true;
        }
    }
    return false;
}


// Get all events
$events = getEvents();

// Process update form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle update event
    if (isset($_POST['update_event']) && isset($_POST['event_index'])) {
        $index = intval($_POST['event_index']);
        $newName = htmlspecialchars($_POST['event_name']);
        $newDetails = htmlspecialchars($_POST['event_details']);
        $newDateTime = htmlspecialchars($_POST['event_datetime']);

        if (empty($newName) || empty($newDetails) || empty($newDateTime)) {
            $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Please fill in all fields.</div>';
        } else {
            if (updateEvent($index, $newName, $newDetails, $newDateTime)) {
                $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">Event updated successfully!</div>';
                // Refresh the events list
                $events = getEvents();
            } else {
                $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Failed to update event.</div>';
            }
        }
    }
}
?>

<div class="update-content">
    <?php echo $message; ?>

    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Update Events</h3>

        <?php if (empty($events)): ?>
            <p class="text-gray-500">No events found to update.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($events as $event): ?>
                    <div class="bg-white rounded-md shadow-sm p-4 border border-gray-200">
                        <h4 class="text-md font-semibold text-blue-700 mb-2"><?= htmlspecialchars($event['name']) ?></h4>

                        <form method="post" action="?tab=update" class="space-y-3">
                            <input type="hidden" name="event_index" value="<?= $event['index'] ?>">

                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Event Name:</label>
                                <input type="text" name="event_name" value="<?= htmlspecialchars($event['name']) ?>" class="shadow-sm border border-gray-300 rounded-md w-full py-1 px-2 text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Event Details:</label>
                                <textarea name="event_details" rows="2" class="shadow-sm border border-gray-300 rounded-md w-full py-1 px-2 text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"><?= htmlspecialchars($event['details']) ?></textarea>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-1">Date and Time:</label>
                                <input type="datetime-local" name="event_datetime" value="<?= htmlspecialchars(str_replace(' ', 'T', $event['dateTime'])) ?>" class="shadow-sm border border-gray-300 rounded-md w-full py-1 px-2 text-gray-700 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="submit" name="update_event" class="bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-1 px-3 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-1">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
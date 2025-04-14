<?php
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
                    'index' => $i / 4  //Index for easy deletion/identification
                ];
            }
        }
    }
    return $events;
}

function deleteEvent($index) {
    $eventsFile = 'events/events.txt';

    if (file_exists($eventsFile)) {
        $lines = file($eventsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $startLine = $index * 4;
        //index is multiplied by 4 because each event takes 4 lines in the file
        // Check if the lines exist before trying to delete them
        // This prevents undefined index errors
        // and ensures we only delete valid events
        // Also check if the index is within the bounds of the lines array

        if (isset($lines[$startLine]) && isset($lines[$startLine + 1]) && isset($lines[$startLine + 2]) && isset($lines[$startLine + 3])) {
            array_splice($lines, $startLine, 4);
            file_put_contents($eventsFile, implode("\n", $lines));
            return true;
        }
    }
    return false;
}

function deleteAllEvents() {
    $eventsFile = 'events/events.txt';

    if (file_exists($eventsFile)) {
        file_put_contents($eventsFile, '');
        return true;
    }
    return false;
}

$events = getEvents();

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // DELETE SINGLE EVENT
    if (isset($_POST['delete_event']) && isset($_POST['event_index'])) {
        $index = intval($_POST['event_index']);

        if (deleteEvent($index)) {
            $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">Event deleted successfully!</div>';
            $events = getEvents();
        } else {
            $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Failed to delete event.</div>';
        }
    }

    // DELETE ALL EVENTS
    if (isset($_POST['delete_all_events'])) {
        if (deleteAllEvents()) {
            $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">All events deleted successfully!</div>';
            $events = getEvents();
        } else {
            $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Failed to delete all events.</div>';
        }
    }
}
?>

<div class="delete-content">
    <?php echo $message; ?>

    <div class="bg-gray-50 rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-700">Delete Events</h3>

            <?php if (!empty($events)): ?>
                <form method="post" action="?tab=delete" onsubmit="return confirm('Are you sure you want to delete ALL events? This cannot be undone!');">
                    <button type="submit" name="delete_all_events" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1 px-3 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                        Delete All Events
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (empty($events)): ?>
            <div class="bg-gray-100 rounded-lg p-4 text-center">
                <p class="text-gray-500">No events found to delete.</p>
            </div>
        <?php else: ?>
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900">Event Name</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date & Time</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <?php
                        function formatDate($dateTime) {
                            return date('F j, Y, g:i A', strtotime($dateTime));
                        };
                        ?>
                        <?php foreach ($events as $event): ?>
                            <tr>
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($event['name']) ?>
                                    <p class="text-xs text-gray-500 mt-1 truncate max-w-xs"><?= htmlspecialchars($event['details']) ?></p>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <?= htmlspecialchars(formatDate($event['dateTime'])) ?>
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm">
                                    <form method="post" action="?tab=delete" class="inline">
                                        <input type="hidden" name="event_index" value="<?= $event['index'] ?>">
                                        <button type="submit" name="delete_event" onclick="return confirm('Are you sure you want to delete this event?');" class="text-red-600 hover:text-red-900 font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
// Create the events directory if it doesn't exist
$eventsDirectory = 'events';
if (!is_dir($eventsDirectory)) {
    mkdir($eventsDirectory);
}

// Define the path to the events.txt file
$eventsFile = $eventsDirectory . '/events.txt';

/**
 * Function to save event details to the events.txt file.
 */
function saveEventDetails($eventName, $eventDetails, $eventDateTime)
{
    global $eventsFile;

    // Sanitize the input data
    $eventName = htmlspecialchars($eventName);
    $eventDetails = htmlspecialchars($eventDetails);
    $eventDateTime = htmlspecialchars($eventDateTime);

    // Prepare the event data string
    $eventData = "Event Name: " . $eventName . "\n";
    $eventData .= "Details: " . $eventDetails . "\n";
    $eventData .= "Date and Time: " . $eventDateTime . "\n";
    $eventData .= "--------------------\n";

    // Add to the file
    file_put_contents($eventsFile, $eventData, FILE_APPEND | LOCK_EX);
}

// Process the form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_event'])) {
    if (isset($_POST['event_name']) && isset($_POST['event_details']) && isset($_POST['event_datetime'])) {
        $eventName = $_POST['event_name'];
        $eventDetails = $_POST['event_details'];
        $eventDateTime = $_POST['event_datetime'];

        if (empty($eventName) || empty($eventDetails) || empty($eventDateTime)) {
            $message = '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">Please fill in all fields.</div>';
        } else {
            saveEventDetails($eventName, $eventDetails, $eventDateTime);
            $message = '<div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">Event saved successfully!</div>';
        }
    } else {
        $message = '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4">Missing parameters.</div>';
    }
}
?>

<div class="add-content">
    <?php echo $message; ?>
    
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-700 mb-4">Add New Event</h3>
        
        <form method="post" action="?tab=add" class="space-y-4">
            <div>
                <label for="event_name" class="block text-gray-700 text-sm font-medium mb-2">Event Name:</label>
                <input type="text" name="event_name" id="event_name" class="shadow-sm border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="event_details" class="block text-gray-700 text-sm font-medium mb-2">Event Details:</label>
                <textarea name="event_details" id="event_details" rows="4" class="shadow-sm border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            
            <div>
                <label for="event_datetime" class="block text-gray-700 text-sm font-medium mb-2">Date and Time:</label>
                <input type="datetime-local" name="event_datetime" id="event_datetime" class="shadow-sm border border-gray-300 rounded-md w-full py-2 px-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="pt-2">
                <button type="submit" name="submit_event" class="bg-green-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Add Event
                </button>
            </div>
        </form>
    </div>
</div>
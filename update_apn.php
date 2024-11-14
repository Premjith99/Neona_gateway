<?php
// Check if the script is accessed via POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apn = $_POST['apn'] ?? '';

    // Ensure the APN is provided
    if (empty($apn)) {
        echo "APN cannot be empty.";
        exit(1);
    }

    // Clear and create a new rnet1 file with the APN provided
    $rnet1_content = "connect \"/usr/sbin/chat -v -f /etc/chatscripts/gp -T $apn\"\n" .
                     "/dev/ttyAMA0\n" .
                     "115200\n" .
                     "noipdefault\n" .
                     "usepeerdns\n" .
                     "defaultroute\n" .
                     "persist\n" .
                     "noauth\n" .
                     "nocrtscts\n" .
                     "local\n" .
                     "debug\n";

                     // Write the new content to /etc/ppp/peers/rnet1, clearing any previous content
    file_put_contents('/etc/ppp/peers/rnet1', $rnet1_content);

    // Set appropriate permissions
    chmod('/etc/ppp/peers/rnet1', 0644);

    // Execute pon rnet1 to start the PPP connection
    shell_exec('sudo /usr/bin/pon rnet1');

    // Optional: Start the ppp0 monitor script after PPP connection is established
    shell_exec('sudo /home/pi/Lora_gateway/ppp0_monitor.sh &');

    // Provide feedback to the user
    echo "APN has been set, and the PPP connection has started.";
} else {
    echo "Invalid access method. Please use the form to submit the APN.";
}
?>

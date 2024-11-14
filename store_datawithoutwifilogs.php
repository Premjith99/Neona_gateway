<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define the log file path
    $log_file = '/home/pi/logs/store_datawithoutwifi.log';

    // Function to log messages
    function log_message($message, $log_file) {
        $timestamp = date("Y-m-d H:i:s");
        file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND);
    }

    // Start logging
    log_message("Form submission started.", $log_file);

    // Get form values
    $server_address = $_POST["serverAddress"];
    $serv_port_up = $_POST["portUp"];
    $serv_port_down = $_POST["portDown"];
    $apn = $_POST['apn'] ?? '';

    // Ensure the APN is provided
    if (empty($apn)) {
        log_message("APN cannot be empty.", $log_file);
        echo "APN cannot be empty.";
        exit(1);
    }

    log_message("APN provided: $apn", $log_file);

    // Define the content of the JSON file
    $json_content = '{
        "gateway_conf": {
            "gateway_ID": "0016c001f103f589",
            "server_address": "' . $server_address . '",
            "serv_port_up": ' . $serv_port_up . ',
            "serv_port_down": ' . $serv_port_down . '
        }
    }';

    // Write the content to the file
    $file_path = '/home/pi/Documents/sx1302_hal/packet_forwarder/global_conf.json.sx1250.IN865';
    if (file_put_contents($file_path, $json_content) === false) {
        log_message("Failed to write to $file_path.", $log_file);
        echo "Failed to write to $file_path.";
        exit(1);
    }

    log_message("Successfully wrote JSON content to $file_path.", $log_file);

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
 if (file_put_contents('/etc/ppp/peers/rnet1', $rnet1_content) === false) {
    log_message("Failed to write to /etc/ppp/peers/rnet1.", $log_file);
    echo "Failed to write to /etc/ppp/peers/rnet1.";
    exit(1);
}

log_message("Successfully wrote APN configuration to /etc/ppp/peers/rnet1.", $log_file);

// Set appropriate permissions
chmod('/etc/ppp/peers/rnet1', 0644);
log_message("Permissions set on /etc/ppp/peers/rnet1.", $log_file);

// Execute pon rnet1 to start the PPP connection
shell_exec('sudo /usr/bin/pon rnet1');
log_message("PPP connection initiated with pon rnet1.", $log_file);

log_message("Redirecting to success.html.", $log_file);
header('Location: success.html');
log_message("ppp0 monitor script started.", $log_file);



// Optional: Start the ppp0 monitor script after PPP connection is established
shell_exec('sudo /home/pi/Lora_gateway/ppp0_monitor.sh &');
exit();
} else {
echo "Invalid access method. Please use the form to submit the data.";
exit(1);
}
?>


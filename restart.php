<?php
// Function to restart the GSM module using the Python script
function restart_gsm_module() {
    // Path to the Python script
    $pythonScript = '/home/pi/scripts/restart_gsm.py';
    
    // Execute the Python script
    $output = shell_exec('python2 ' . escapeshellarg($pythonScript) . ' 2>&1');
    
    return "GSM module is restarting...";
}

// Restart the GSM module and return the response to the user
echo restart_gsm_module();
// Return response to the user
echo "GSM is restarting ...";// Sleep for 20 seconds before rebooting the system
sleep(20);

// Reboot the Raspberry Pi after the sleep period
shell_exec('sudo reboot');

echo "Rebooting Raspberry Pi...";


?>









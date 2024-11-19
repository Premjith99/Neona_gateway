<?php
// Function to restart the GSM module using the Python script
function restart_gsm_module() {
    // Path to the Python script
    $pythonScript = '/home/pi/Lora_gateway/restart_gsm.py ';
    
    // Execute the Python script
    $output = shell_exec('sudo  /usr/bin/python3 '.escapeshellarg($pythonScript).' 2>&1');
    
    return "GSM module is restarting...";
   

}

// Restart the GSM module and return the response to the user
echo restart_gsm_module();

?>














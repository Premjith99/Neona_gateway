if (!empty($wifi_ssid) && !empty($wifi_password)) {
    $network_config_content1 = "auto eth0\n";
    $network_config_content1 .= "iface eth0 inet dhcp\n\n";
    $network_config_content1 .= "allow-hotplug wlan0\n";
    $network_config_content1 .= "iface wlan0 inet dhcp\n";
    $network_config_content1 .= "    wpa-ssid \"$wifi_ssid\"\n";  // Added quotes for safety
    $network_config_content1 .= "    wpa-psk \"$wifi_password\"\n"; // Added quotes for safety

    $network_config_content2 = "ctrl_interface=DIR=/var/run/wpa_supplicant GROUP=netdev\n";
    $network_config_content2 .= "update_config=1\n";
    $network_config_content2 .= "network={\n";
    $network_config_content2 .= "    ssid=\"$wifi_ssid\"\n";       // Changed quotes for correct syntax
    $network_config_content2 .= "    psk=\"$wifi_password\"\n";      // Changed quotes for correct syntax
    $network_config_content2 .= "    key_mgmt=WPA-PSK\n";
    $network_config_content2 .= "}\n";
} else {
    // Use static IP configuration if WiFi SSID and Password are not provided
    $network_config_content1 = "auto eth0\n";
    $network_config_content1 .= "iface eth0 inet dhcp\n\n";
    $network_config_content1 .= "auto wlan0\n";
    $network_config_content1 .= "iface wlan0 inet static\n";
    $network_config_content1 .= "    address 192.168.5.1\n";
    $network_config_content1 .= "    netmask 255.255.255.0\n";
    $network_config_content1 .= "    gateway 192.168.5.254\n";
}

// Write network configuration to file
$network_config_path1 = '/etc/network/interfaces';
$network_config_path2 = '/etc/wpa_supplicant/wpa_supplicant.conf';
file_put_contents($network_config_path1, $network_config_content1);
file_put_contents($network_config_path2, $network_config_content2);

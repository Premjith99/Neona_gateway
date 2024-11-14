<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $server_address = $_POST["serverAddress"];
    $serv_port_up = $_POST["portUp"];
    $serv_port_down = $_POST["portDown"];
    $apn = $_POST['apn'] ?? '';
    
    // Ensure the APN is provided
    if (empty($apn)) {
        echo "APN cannot be empty.";
        exit(1);
    }

    $json_content = '{
       "SX130x_conf": {
        "com_type": "SPI",
        "com_path": "/dev/spidev0.0",
        "lorawan_public": true,
        "clksrc": 0,
        "antenna_gain": 0, /* antenna gain, in dBi */
        "full_duplex": false,
        "fine_timestamp": {
            "enable": false,
            "mode": "all_sf" /* high_capacity or all_sf */
        },
        "sx1261_conf": {
            "spi_path": "/dev/spidev0.1",
            "rssi_offset": 0, /* dB */
            "spectral_scan": {
                "enable": false,
                "freq_start": 867100000,
                "nb_chan": 8,
                "nb_scan": 2000,
                "pace_s": 10
            },
            "lbt": {
                "enable": false,
                "rssi_target": -70, /* dBm */
                "channels":[ /* 16 channels maximum */
                    { "freq_hz": 867100000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 867300000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 867500000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 867700000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 867900000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 868100000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 868300000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 868500000, "bandwidth": 125000, "scan_time_us": 128,  "transmit_time_ms": 400 },
                    { "freq_hz": 869525000, "bandwidth": 125000, "scan_time_us": 5000, "transmit_time_ms": 4000 },
                    { "freq_hz": 868300000, "bandwidth": 250000, "scan_time_us": 128,  "transmit_time_ms": 400 }
                ]
            }
        },
        "radio_0": {
            "enable": true,
            "type": "SX1250",
            "freq": 865200000,
            "rssi_offset": -166,
            "rssi_tcomp": {"coeff_a": 0, "coeff_b": 0, "coeff_c": 20.41, "coeff_d": 2162.56, "coeff_e": 0},
            "tx_enable": true,
            "tx_freq_min": 865000000,
            "tx_freq_max": 867000000
        },
        "radio_1": {
            "enable": true,
            "type": "SX1250",
            "freq": 866385000,
            "rssi_offset": -166,
            "rssi_tcomp": {"coeff_a": 0, "coeff_b": 0, "coeff_c": 20.41, "coeff_d": 2162.56, "coeff_e": 0},
            "tx_enable": false
        },
        "chan_multiSF_All": {"spreading_factor_enable": [ 5, 6, 7, 8, 9, 10, 11, 12 ]},
        "chan_multiSF_0": {"enable": true, "radio": 0, "if": -137500},
        "chan_multiSF_1": {"enable": true, "radio": 0, "if": -202500},
        "chan_multiSF_2": {"enable": true, "radio": 1, "if": -400000},
        "chan_multiSF_3": {"enable": true, "radio": 0, "if": 32500},
        "chan_multiSF_4": {"enable": true, "radio": 1, "if": -200000},
        "chan_multiSF_5": {"enable": true, "radio": 1, "if":  0},
        "chan_multiSF_6": {"enable": true, "radio": 1, "if":  200000},
        "chan_multiSF_7": {"enable": true, "radio": 1, "if":  400000},
        "chan_Lora_std":  {"false": true, "radio": 1, "if": -200000, "bandwidth": 250000, "spread_factor": 7,
                           "implicit_hdr": false, "implicit_payload_length": 17, "implicit_crc_en": false, "implicit_coderate": 1},
        "chan_FSK":       {"false": true, "radio": 1, "if":  300000, "bandwidth": 125000, "datarate": 50000},
		"tx_gain_lut":[
                {"rf_power": -6, "pa_gain": 0, "pwr_idx": 8},
                {"rf_power": -3, "pa_gain": 0, "pwr_idx": 10},
                {"rf_power": 0, "pa_gain": 0, "pwr_idx": 12},
                {"rf_power": 3, "pa_gain": 1, "pwr_idx": 8},
                {"rf_power": 6, "pa_gain": 1, "pwr_idx": 10},
                {"rf_power": 10, "pa_gain": 1, "pwr_idx": 12},
                {"rf_power": 11, "pa_gain": 1, "pwr_idx": 13},
                {"rf_power": 12, "pa_gain": 2, "pwr_idx": 12},
                {"rf_power": 13, "pa_gain": 2, "pwr_idx": 15},
                {"rf_power": 14, "pa_gain": 2, "pwr_idx": 10},
                {"rf_power": 16, "pa_gain": 2, "pwr_idx": 11},
                {"rf_power": 20, "pa_gain": 3, "pwr_idx": 9},
                {"rf_power": 23, "pa_gain": 3, "pwr_idx": 10},
                {"rf_power": 25, "pa_gain": 3, "pwr_idx": 11},
                {"rf_power": 26, "pa_gain": 1, "pwr_idx": 12},
                {"rf_power": 27, "pa_gain": 1, "pwr_idx": 14}
            ]
    },


        "gateway_conf": {
            "gateway_ID": "0016c001f103f589",
            /* change with default server address/ports */
            "server_address": "' . $server_address . '",
            "serv_port_up": ' . $serv_port_up . ',
            "serv_port_down": ' . $serv_port_down . ',
            /* adjust the following parameters for your network */
            "keepalive_interval": 10,
            "stat_interval": 30,
            "push_timeout_ms": 100,
            /* forward only valid packets */
            "forward_crc_valid": true,
            "forward_crc_error": false,
            "forward_crc_disabled": false,
            /* GPS configuration */
           /* "gps_tty_path": "/dev/tty0",*/
          "gps_tty_path": "",
            /* GPS reference coordinates */
            "ref_latitude": 0.0,
            "ref_longitude": 0.0,
            "ref_altitude": 0,
            /* Beaconing parameters */
            "beacon_period": 0,
            "beacon_freq_hz": 869525000,
            "beacon_datarate": 9,
            "beacon_bw_hz": 125000,
            "beacon_power": 14,
            "beacon_infodesc": 0
    
        },
    
        "debug_conf": {
            "ref_payload":[
                {"id": "0xCAFE1234"},
                {"id": "0xCAFE2345"}
            ],
            "log_file": "loragw_hal.log"
        }
    }';
    

    // Write the content to the file
    $file_path = '/home/pi/Documents/sx1302_hal/packet_forwarder/global_conf.json.sx1250.IN865';
    file_put_contents($file_path, $json_content);

    // Clear and create a new rnet1 file with the APN provided
    $rnet1_content = "connect \"/usr/sbin/chat -v -f /etc/chatscripts/gprs -T $apn\"\n" .
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
   chmod('/etc/ppp/peers/rnet1', 0644); // Read/Write for owner, Read for others
   chown('/etc/ppp/peers/rnet1', 'www-data'); // Change ownership to www-data
   // Set executable permission for the user, group and others if necessary
   chmod('/etc/ppp/peers/rnet1', 0755); // Make it executable (if needed)
   echo "<h1>Connected successfully</h1>";
    exit();
} else {
    echo "Invalid access method. Please use the form to submit the data.";
    exit(1);
}
?>
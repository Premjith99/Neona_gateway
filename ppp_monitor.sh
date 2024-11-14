#!/bin/bash

# Log file to store actions
LOGFILE="/var/log/ppp0_monitor.log"

echo "*** PPP monitor started at $(date)" >> $LOGFILE

# Function to set ppp0 as the default route
set_ppp0_as_default() {
    # Delete default route for eth0 if it exists
    if ip route | grep -q "default.*eth0"; then
        sudo ip route del default dev eth0
        echo "Default route via eth0 removed at $(date)" >> $LOGFILE
    fi

    # Add default route via ppp0
    if ! ip route | grep -q "default.*ppp0"; then
        sudo ip route add default dev ppp0
        echo "Default route via ppp0 added at $(date)" >> $LOGFILE
    fi
}

# Function to start the LoRa packet forwarder
start_lora_pkt_fwd() {
    if ! pidof lora_pkt_fwd > /dev/null; then
        echo "Starting lora_pkt_fwd at $(date)" >> $LOGFILE
        cd /home/pi/Documents/sx1302_hal/packet_forwarder || { echo "Failed to change directory to packet_forwarder at $(date)" >> $LOGFILE; exit 1; }

        # Wait for 10 seconds to ensure network is ready
        sleep 10
        
        ./lora_pkt_fwd -c global_conf.json.sx1250.IN865 >> /var/log/packet_forwarder.log 2>&1 &
        echo "lora_pkt_fwd started at $(date)" >> $LOGFILE
    else
        echo "lora_pkt_fwd is already running at $(date)" >> $LOGFILE
    fi
}

# Function to stop the LoRa packet forwarder
stop_lora_pkt_fwd() {
    if pidof lora_pkt_fwd > /dev/null; then
        echo "Stopping lora_pkt_fwd at $(date)" >> $LOGFILE
        pkill -f "lora_pkt_fwd"
        echo "lora_pkt_fwd stopped at $(date)" >> $LOGFILE
    else
        echo "lora_pkt_fwd is not running at $(date)" >> $LOGFILE
    fi
}

# Flag to indicate if ppp0 has been set as the default route
PPP0_DEFAULT_SET=false

# Infinite loop to monitor ppp0
while true; do
    if ip link show ppp0 | grep -q "UP"; then
        echo "ppp0 is available at $(date)" >> $LOGFILE
        
        if [ "$PPP0_DEFAULT_SET" = false ]; then
            set_ppp0_as_default
            PPP0_DEFAULT_SET=true

            # Start LoRa packet forwarder
            start_lora_pkt_fwd
        fi
    else
        echo "ppp0 is not available at $(date)" >> $LOGFILE
        PPP0_DEFAULT_SET=false

        # Stop LoRa packet forwarder
        stop_lora_pkt_fwd
    fi
    
    # Wait for 10 seconds before checking again
    sleep 10
done

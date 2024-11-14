sudo /usr/bin/pon rnet1 &

while true; do
    # Check if PPP connection is established by checking for the ppp0 interface with an IP address
    if ip addr show ppp0 | grep -q "inet "; then
        echo "PPP connection is established."

        # Check if the ppp0 monitor script is running
        if ! pgrep -f "ppp0_monitor.sh" > /dev/null; then
            echo "ppp0_monitor.sh not running. Starting the monitor script..."
            sudo /home/pi/Lora_gateway/ppp0_monitor.sh &
        fi
    else
        echo "PPP connection not established, attempting to connect..."
        sudo /usr/bin/pon rnet1

        # Wait a few seconds to allow the PPP connection to establish
        sleep 10

        # Check again if the PPP connection is still not established
        if ! ip addr show ppp0 | grep -q "inet "; then
            echo "PPP connection failed, restarting GSM module..."
            sudo /usr/bin/python2 /home/pi/scripts/restart_gsm.py
        fi
    fi

    # Sleep for 1 minute before checking again
    sleep 60
done

exit 0


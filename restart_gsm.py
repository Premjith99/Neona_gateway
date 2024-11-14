import RPi.GPIO as GPIO
import time

RESET_PIN = 21

GPIO.setwarnings(False)  # Suppress warnings
GPIO.setmode(GPIO.BCM)   # Set BCM numbering
GPIO.setup(RESET_PIN, GPIO.OUT, initial=GPIO.HIGH)  # Set GPIO21 as an output with initial HIGH

# Set pin low for 2 seconds
GPIO.output(RESET_PIN, GPIO.LOW)
time.sleep(2)

# Set pin back to high and keep it high
GPIO.output(RESET_PIN, GPIO.HIGH)

# Do NOT call GPIO.cleanup() to keep the pin in the HIGH state

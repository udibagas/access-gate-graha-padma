#!/usr/bin/env python3
from evdev import InputDevice, categorize, ecodes, KeyEvent
import requests
import time
# from RPi import GPIO

DEVICE = '/dev/input/event3'
API_URL = 'http://localhost:8000/api'
PIN_RELAY = 36
CARD_NUMBER_LENGTH = 10


def check_card(card_number):
    payload = {'card_number': card_number}
    try:
        r = requests.post(API_URL + '/accessLog', json=payload, timeout=3)
        print(r.text)
        if r.status_code == 200:
            open_gate()
    except Exception as e:
        print(e)


def open_gate():
    print('GATE OPENED')
    # GPIO.output(PIN_RELAY, 1)
    # time.sleep(1)
    # GPIO.output(PIN_RELAY, 0)


if __name__ == "__main__":
    # GPIO.setmode(GPIO.BOARD)
    # GPIO.setwarnings(False)
    # GPIO.setup(PIN_RELAY, GPIO.OUT)

    device = InputDevice(DEVICE)
    number = ''
    allowed_keys = ['KEY_0', 'KEY_1', 'KEY_2',
                    'KEY_3', 'KEY_4', 'KEY_5', 'KEY_6', 'KEY_7', 'KEY_8', 'KEY_9']

    for event in device.read_loop():
        if event.type == ecodes.EV_KEY and event.value == 1:
            key = KeyEvent(event)
            if key.keycode in allowed_keys:
                number += str(allowed_keys.index(key.keycode))

            if len(number) == CARD_NUMBER_LENGTH or key.keycode == 'KEY_ENTER':
                check_card(number)
                number = ''

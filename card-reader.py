#!/usr/bin/env python3
from evdev import InputDevice, categorize, ecodes, KeyEvent
import requests

API_URL = 'http://localhost:8000/api'

device = InputDevice('/dev/input/event3')
number = ''
allowed_keys = ['KEY_0', 'KEY_1', 'KEY_2',
                'KEY_3', 'KEY_4', 'KEY_5', 'KEY_6', 'KEY_7', 'KEY_8', 'KEY_9']


def check_card(card_number):
    payload = {'card_number': card_number}
    try:
        r = requests.post(API_URL + '/accessLog', json=payload, timeout=3)
        print(r.status_code)
        if r.status_code == 200:
            open_gate()
    except Exception as e:
        print(e)


def open_gate():
    print('gate opened')


for event in device.read_loop():
    if event.type == ecodes.EV_KEY and event.value == 1:
        key = KeyEvent(event)
        if key.keycode in allowed_keys:
            number += str(allowed_keys.index(key.keycode))

        if len(number) == 10 or key.keycode == 'KEY_ENTER':
            check_card(number)
            number = ''

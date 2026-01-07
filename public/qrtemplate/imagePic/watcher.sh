#!/bin/bash
while inotifywait -e close_write runner.sh; do
    # ./runner.sh
    ./runner.sh "Pizza Palace" "https://pizzapalace.com/menu" "pizzapalace_qr.png"
done

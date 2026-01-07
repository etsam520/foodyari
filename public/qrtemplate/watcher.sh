#!/bin/bash
while inotifywait -e close_write overlay_config.conf; do
    ./transform_images.sh
done



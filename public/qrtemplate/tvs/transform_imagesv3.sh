#!/bin/bash
# overlay_images.sh
# Usage: ./overlay_images.sh

# Input files
BACKGROUND="template.jpg"
OVERLAY1="hotel-khusi.png"
OVERLAY2="overlay1.png"
OVERLAY3="logo.png"

# Output file
OUTPUT="output.png"

# Run ffmpeg
ffmpeg -i "$BACKGROUND" \
-i "$OVERLAY1" -i "$OVERLAY2" -i "$OVERLAY3" \
-filter_complex "\
[1:v]scale=200:100[ovrl1]; \
[2:v]scale=150:150[ovrl2]; \
[3:v]scale=100:200[ovrl3]; \
[0:v][ovrl1]overlay=x=50:y=50[tmp1]; \
[tmp1][ovrl2]overlay=x=300:y=100[tmp2]; \
[tmp2][ovrl3]overlay=x=100:y=400" \

"$OUTPUT"
ffmpeg -i "$BACKGROUND" \
-i "$OVERLAY1" -i "$OVERLAY2" \
-filter_complex "\
[1:v]scale=200:100[ovrl1]; \
[2:v]scale=150:150[ovrl2]; \
[0:v][ovrl1]overlay=x=50:y=50[tmp1]; \
[tmp1][ovrl2]overlay=x=300:y=100[tmp2]; \
[tmp2]drawtext=\
fontfile=/var/www/myproject/foodyari_live/public/qrtemplate/macondo/Macondo-Regular.ttf: \
text='Hotel Khusi': \
x=100:y=300: \
fontsize=48: \
fontcolor=white: \
box=1:boxcolor=black@0.5:boxborderw=5, \
drawtext=\
fontfile=/var/www/myproject/foodyari_live/public/qrtemplate/macondo/Macondo-Regular.ttf: \
text='Welcome!': \
x=200:y=400: \
fontsize=80: \
fontcolor=yellow: \
box=1:boxcolor=blue@0.5:boxborderw=3" \
"$OUTPUT"
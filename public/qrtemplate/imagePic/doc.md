ffmpeg -f lavfi -i color=c=white:s=450x750 -frames:v 1 canvas.png

Single solid-color image (PNG)
======================================

ffmpeg -f lavfi -i color=c=black@0.0:s=450x750 -frames:v 1 -pix_fmt rgba canvas_transparent.png

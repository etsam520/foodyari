ffmpeg -i background.png -i overlay.png -filter_complex "overlay=x=100:y=50" output.png









===================
* -overlay position
ffmpeg -i template.jpg -i hotel-khusi.png -filter_complex "overlay=x=100:y=50" output.png

Center overlay:
-overlay=x=(main_w-overlay_w)/2:y=(main_h-overlay_h)/2
Bottom-right corner:
-overlay=x=main_w-overlay_w:y=main_h-overlay_h
===========================


==================================================================
You want to overlay an image on another and resize the overlay at the same time. With ffmpeg

ffmpeg -i template.jpg -i hotel-khusi.png -filter_complex "[1:v]scale=200:100[ovrl];[0:v][ovrl]overlay=x=100:y=50" output.png


Explanation:

[1:v]scale=200:100[ovrl] → resizes the overlay image (hotel-khusi.png) to width=200px, height=100px, and labels it [ovrl].

[0:v][ovrl]overlay=x=100:y=50 → places the resized overlay on the background (template.jpg) at position (100,50).

output.png → final image.

=========================================

ffmpeg -i overlay9.png -vf "drawtext=text='FOodyari':fontfile=/var/www/myproject/foodyari_live/public/qrtemplate/macondo/Macondo-Regular.ttf:fontcolor=white:fontsize=48:x=(w-text_w)/2:y=(h-text_h)/2" output.jpg

//
with custome color
ffmpeg -i overlay9.png -vf "drawtext=text='FOodyari':fontfile=/var/www/myproject/foodyari_live/public/qrtemplate/macondo/Macondo-Regular.ttf:fontcolor=#FF5733:fontsize=48:x=(w-text_w)/2:y=(h-text_h)/2" output.jpg



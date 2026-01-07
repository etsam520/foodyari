#!/bin/bash
# ffmpeg -y -f lavfi -i color=c=white:s=450x750 -frames:v 1 output.png
# ffmpeg -f lavfi -i color=c=black@0.0:s=450x750 -frames:v 1 -pix_fmt rgba canvas_transparent.png

# ffmpeg -y \
#   -f lavfi -i color=c=#ffa144:s=450x750 \
#   -i logo.png \
#   -i teplate.jpg \
#   -filter_complex "[0][1]overlay=x=50:y=50:scale=200:200[bg]; \
#                    [bg]drawtext=text='200x200':x=(w-text_w)/2:y=(h-text_h)/2:fontsize=72:fontcolor=black" \
#   -frames:v 1 output.png

#   qrbyffer = qrencod/e -o qr.png -s 30 -l H "https://example.com"

#   ffmpeg -y \
#   -i logo.png \
#   -i template.jpg \
#   -i hotel-khusi.png \
#   -filter_complex "[0]scale=300:300[logo]; \
#                    [2]scale=800:800[qr]; \
#                    [1][logo]overlay=550:140[tmp1]; \
#                    [tmp1][qr]overlay=x=(W-w)/2:y=(H-h)/1.75[tmp2]; \
#                    [tmp2]drawtext=text='200x200':x=(w-text_w)/2:y=(h-text_h)/2:fontsize=72:fontcolor=black" \
#   -frames:v 1 output.png
# qrencode -o - -s 30 -l H "https://example.com" | \
# ffmpeg -y \
#   -i logo.png \
#   -i template.jpg \
#   -f image2pipe -vcodec png -i - \
#   -filter_complex "[0]scale=300:300[logo]; \
#                    [2]scale=800:800[qr]; \
#                    [1][logo]overlay=550:140[tmp1]; \
#                    [tmp1][qr]overlay=x=(W-w)/2:y=(H-h)/1.75[tmp2]; \
#                    [tem2][logo]overlay=550:140; \
#                    [tmp2]drawtext=text='200x200':x=(w-text_w)/2:y=(h-text_h)/2:fontsize=72:fontcolor=black" \
#   -frames:v 1 output.png


# qrencode -o - -s 30 -l H "https://example.com" | \
# ffmpeg -y \
#   -i logo.png \
#   -i template.jpg \
#   -f image2pipe -vcodec png -i - \
#   -filter_complex "[0]scale=300:300[logo]; \
#                    [2]scale=800:800[qr]; \
#                    [1][logo]overlay=550:140[tmp1]; \
#                    [tmp1][qr]overlay=x=(W-w)/2:y=(H-h)/1.75[tmp2]; \
#                    [temp2][logo]overlay=550:140; \
#                    [tmp2]drawtext=text='200x200':x=(w-text_w)/2:y=(h-text_h)/2:fontsize=72:fontcolor=black" \
#   -frames:v 1 output.png


# ==================================================================
# qrencode -o - -s 30 -l H "https://example.com" | \
# ffmpeg -y \
#   -i logo.png \
#   -i template.jpg \
#   -f image2pipe -vcodec png -i - \
#   -filter_complex "[0]scale=300:300[logo]; \
#                    [2]scale=800:800[qr]; \
#                    [qr][logo]overlay=(W-w)/2:(H-h)/2[qr_logo]; \
#                    [1][qr_logo]overlay=x=(W-w)/2:y=(H-h)/1.75[tmp2]; \
#                    [tmp2]drawtext=text='Delid Darbary':x=(w-text_w)/5:y=(h-text_h)/1.12:fontsize=72:fontcolor=black" \
#   -frames:v 1 output.png

# =====================================================================================



#!/bin/bash

# restaurant="$1"
# link="$2"
# public_path="$3"
# output="$4"

# qrencode -o - -s 30 -l H "$link" | \
# ffmpeg -y \
#   -i logo.png \
#   -i template.jpg \
#   -f image2pipe -vcodec png -i - \
#   -i restaurant.png \
#   -filter_complex "[0]scale=300:300[logo]; \
#                    [2]scale=800:800[qr]; \
#                    [3]scale=-1:150[restaurant]; \
#                    [qr][logo]overlay=(W-w)/2:(H-h)/2[qr_logo]; \
#                    [1][qr_logo]overlay=x=(W-w)/2:y=(H-h)/1.75[tmp2]; \
#                    [tmp2][restaurant]overlay=x=(W-w)/6:y=(H-h)/1.12[tmp3]; \
#                    [tmp3]drawtext=text='${restaurant}':x=(w-text_w)/2.2:y=(h-text_h)/1.12:fontsize=100:fontcolor=black:fontfile=font/Macondo-Regular.ttf:fontcolor=#ffa144:\
#                    fontcolor_expr=#ffa144:font='Macondo-Regular': \
#       fontcolor_expr='#ffa144': \
#       shadowx=2:shadowy=2:shadowcolor=black" \
#   -frames:v 1 "$output"

restaurant="$1"
link="$2"
mainpath="$3"
output="$4"


qrencode -o - -s 30 -l H "$link" | \
ffmpeg -y \
  -i $mainpath/logo.png \
  -i $mainpath/template.jpg \
  -f image2pipe -vcodec png -i - \
  -i $mainpath/restaurant.png \
  -filter_complex "\
    [0]scale=300:300[logo]; \
    [2]scale=800:800[qr]; \
    [3]scale=-1:125[rest_icon]; \
    [qr][logo]overlay=(W-w)/2:(H-h)/2[qr_logo]; \
    [1][qr_logo]overlay=x=(W-w)/2:y=(H-h)/1.75[bg]; \
    color=c=0x00000000:s=1000x200[base]; \
    [base][rest_icon]overlay=10:(H-h)/2[tmp_group]; \
    [tmp_group]drawtext=text='${restaurant}':x=225:y=(h-text_h)/2:fontsize=110:fontcolor=#ffa144:fontfile=$mainpath/font/Macondo-Regular.ttf:shadowx=2:shadowy=2:shadowcolor=black[group]; \
    [bg][group]overlay=x=(W-w)/2:y=H-h-220[final]" \
  -map "[final]" -frames:v 1 "$output"




#   (W-w)/2:(H-h)/2


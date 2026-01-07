#!/bin/bash

# overlay_images.sh - Advanced image overlay script using ImageMagick
# Usage: ./overlay_images.sh [config_file]

# Default configuration file
CONFIG_FILE="${1:-overlay_config.conf}"

# Default values
BACKGROUND="template.jpg"
OUTPUT="output.png"
QUALITY=95
VERBOSE=false
WIDTH=1920
HEIGHT=1080
CANVAS_COLOR="white"

# Arrays to store overlay configurations
declare -a IMAGE_OVERLAYS
declare -a TEXT_OVERLAYS

# Function to display usage
usage() {
    echo "Usage: $0 [config_file]"
    echo "Default config file: overlay_config.conf"
    echo ""
    echo "Config file format:"
    echo "BACKGROUND=template.jpg"
    echo "OUTPUT=result.png"
    echo "QUALITY=95"
    echo "WIDTH=1920"
    echo "HEIGHT=1080"
    echo "CANVAS_COLOR=white"
    echo "VERBOSE=true"
    echo ""
    echo "# Image overlays with negative coordinates:"
    echo "OVERLAY_IMAGE_1=file=image.png,x=-50,y=100,width=200,height=200,opacity=1.0,gravity=northwest"
    echo "OVERLAY_IMAGE_2=file=logo.png,x=300,y=-20,width=150,height=150,opacity=0.8,gravity=northeast"
    echo ""
    echo "# Text overlays with advanced styling:"
    echo "OVERLAY_TEXT_1=text='Welcome!',x=100,y=300,fontsize=48,fontcolor=white,gravity=northwest"
    echo "OVERLAY_TEXT_2=text='Hotel Khusi',x=-50,y=400,fontsize=36,fontcolor=black,gravity=northeast"
    echo ""
    echo "Available gravity values: northwest, north, northeast, west, center, east, southwest, south, southeast"
}

# Function to parse configuration file
parse_config() {
    if [[ ! -f "$CONFIG_FILE" ]]; then
        echo "Error: Config file '$CONFIG_FILE' not found!"
        exit 1
    fi

    while IFS='=' read -r key value; do
        # Skip comments and empty lines
        [[ $key =~ ^# ]] || [[ -z $key ]] && continue
        
        # Remove quotes and extra spaces
        key=$(echo "$key" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')
        value=$(echo "$value" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//' | sed "s/^['\"]//;s/['\"]$//")
        
        case $key in
            BACKGROUND)
                BACKGROUND="$value"
                ;;
            OUTPUT)
                OUTPUT="$value"
                ;;
            QUALITY)
                QUALITY="$value"
                ;;
            WIDTH)
                WIDTH="$value"
                ;;
            HEIGHT)
                HEIGHT="$value"
                ;;
            CANVAS_COLOR)
                CANVAS_COLOR="$value"
                ;;
            VERBOSE)
                if [[ "$value" == "true" || "$value" == "1" ]]; then
                    VERBOSE=true
                else
                    VERBOSE=false
                fi
                ;;
            OVERLAY_IMAGE_*)
                IMAGE_OVERLAYS+=("$value")
                ;;
            OVERLAY_TEXT_*)
                TEXT_OVERLAYS+=("$value")
                ;;
            *)
                echo "Warning: Unknown configuration key '$key'"
                ;;
        esac
    done < "$CONFIG_FILE"
}

# Function to parse overlay parameters
parse_overlay_params() {
    local params="$1"
    local -A result=()
    
    IFS=',' read -ra pairs <<< "$params"
    for pair in "${pairs[@]}"; do
        IFS='=' read -r key val <<< "$pair"
        # Remove any quotes and trim spaces
        key=$(echo "$key" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//')
        val=$(echo "$val" | sed 's/^[[:space:]]*//;s/[[:space:]]*$//' | sed "s/^['\"]//;s/['\"]$//")
        result["$key"]="$val"
    done
    
    # Return the associative array by printing key=value pairs
    for key in "${!result[@]}"; do
        printf "%s='%s' " "$key" "${result[$key]}"
    done
}

# Function to get value from parsed params
get_param() {
    local params_str="$1"
    local key="$2"
    local default="${3:-}"
    
    # Use regex to extract the value
    if [[ $params_str =~ $key=\'([^\']+)\' ]]; then
        echo "${BASH_REMATCH[1]}"
    elif [[ $params_str =~ $key=([^[:space:]]+) ]]; then
        echo "${BASH_REMATCH[1]}"
    else
        echo "$default"
    fi
}

# Function to process image overlays
process_image_overlays() {
    local temp_image="$1"
    
    for i in "${!IMAGE_OVERLAYS[@]}"; do
        local params="${IMAGE_OVERLAYS[$i]}"
        local parsed_params=$(parse_overlay_params "$params")
        
        local file=$(get_param "$parsed_params" "file")
        local x=$(get_param "$parsed_params" "x" "0")
        local y=$(get_param "$parsed_params" "y" "0")
        local width=$(get_param "$parsed_params" "width")
        local height=$(get_param "$parsed_params" "height")
        local opacity=$(get_param "$parsed_params" "opacity" "1.0")
        local gravity=$(get_param "$parsed_params" "gravity" "northwest")
        
        if [[ ! -f "$file" ]]; then
            echo "Error: Overlay image '$file' not found!" >&2
            continue  # Skip this overlay but continue processing others
        fi
        
        local overlay_file="$file"
        
        # Handle resize if dimensions specified
        if [[ -n "$width" && -n "$height" ]]; then
            local resize_cmd="convert \"$file\" -resize ${width}x${height}\!"
            if [[ "$opacity" != "1.0" ]]; then
                resize_cmd+=" -alpha set -channel A -evaluate multiply $opacity +channel"
            fi
            resize_cmd+=" /tmp/resized_overlay_$i.png"
            if $VERBOSE; then
                echo "Resizing overlay: $resize_cmd"
            fi
            eval $resize_cmd
            overlay_file="/tmp/resized_overlay_$i.png"
        elif [[ "$opacity" != "1.0" ]]; then
            convert "$file" -alpha set -channel A -evaluate multiply $opacity +channel "/tmp/opacity_overlay_$i.png"
            overlay_file="/tmp/opacity_overlay_$i.png"
        fi
        
        # Build composite command
        local overlay_cmd="composite -gravity $gravity -geometry +${x#+}+${y#+}"
        overlay_cmd+=" \"$overlay_file\" \"$temp_image\" \"$temp_image\""
        
        if $VERBOSE; then
            echo "Applying image overlay: $overlay_cmd"
        fi
        
        eval $overlay_cmd
    done
}

# Function to process text overlays
process_text_overlays() {
    local temp_image="$1"
    
    for i in "${!TEXT_OVERLAYS[@]}"; do
        local params="${TEXT_OVERLAYS[$i]}"
        local parsed_params=$(parse_overlay_params "$params")
        
        local text=$(get_param "$parsed_params" "text")
        local x=$(get_param "$parsed_params" "x" "0")
        local y=$(get_param "$parsed_params" "y" "0")
        local fontsize=$(get_param "$parsed_params" "fontsize" "24")
        local fontcolor=$(get_param "$parsed_params" "fontcolor" "white")
        local fontfile=$(get_param "$parsed_params" "fontfile")
        local gravity=$(get_param "$parsed_params" "gravity" "northwest")
        local stroke=$(get_param "$parsed_params" "stroke")
        local strokecolor=$(get_param "$parsed_params" "strokecolor" "black")
        local strokewidth=$(get_param "$parsed_params" "strokewidth" "1")
        
        # Create a temporary file for the text overlay
        local text_temp="/tmp/text_overlay_$i.mpc"
        
        # Build convert command for text annotation
        local annotate_cmd="convert \"$temp_image\" -gravity $gravity -fill \"$fontcolor\""
        
        # Add font if specified
        if [[ -n "$fontfile" && -f "$fontfile" ]]; then
            annotate_cmd+=" -font \"$fontfile\""
        fi
        
        # Add stroke if specified
        if [[ -n "$stroke" && "$stroke" == "1" ]]; then
            annotate_cmd+=" -stroke \"$strokecolor\" -strokewidth $strokewidth"
        fi
        
        annotate_cmd+=" -pointsize $fontsize -annotate +${x#+}+${y#+} \"$text\" \"$temp_image\""
        
        if $VERBOSE; then
            echo "Applying text overlay: $annotate_cmd"
        fi
        
        eval $annotate_cmd
    done
}

# Function to create final image
create_final_image() {
    local temp_image="$1"
    
    # Convert to final format with quality
    local final_cmd="convert \"$temp_image\" -quality $QUALITY \"$OUTPUT\""
    
    if $VERBOSE; then
        echo "Creating final image: $final_cmd"
    fi
    
    eval $final_cmd
}

# Main execution
main() {
    # Check if ImageMagick is available
    if ! command -v convert &> /dev/null || ! command -v composite &> /dev/null; then
        echo "Error: ImageMagick (convert and composite commands) is not installed!"
        echo "Install with: sudo apt-get install imagemagick"
        exit 1
    fi
    
    # Parse configuration
    parse_config
    
    if $VERBOSE; then
        echo "Background: $BACKGROUND"
        echo "Output: $OUTPUT"
        echo "Quality: $QUALITY"
        echo "Canvas size: ${WIDTH}x${HEIGHT}"
        echo "Canvas color: $CANVAS_COLOR"
        echo "Image overlays: ${#IMAGE_OVERLAYS[@]}"
        for i in "${!IMAGE_OVERLAYS[@]}"; do
            echo "  Image $((i+1)): ${IMAGE_OVERLAYS[$i]}"
        done
        echo "Text overlays: ${#TEXT_OVERLAYS[@]}"
        for i in "${!TEXT_OVERLAYS[@]}"; do
            echo "  Text $((i+1)): ${TEXT_OVERLAYS[$i]}"
        done
    fi
    
    # Create base canvas
    local temp_image="/tmp/base_image.png"
    
    if [[ -f "$BACKGROUND" ]]; then
        # Resize background to canvas size
        convert "$BACKGROUND" -resize "${WIDTH}x${HEIGHT}^" -gravity center -extent "${WIDTH}x${HEIGHT}" "$temp_image"
    else
        # Create blank canvas
        convert -size "${WIDTH}x${HEIGHT}" "xc:$CANVAS_COLOR" "$temp_image"
        echo "Warning: Background image '$BACKGROUND' not found, using blank canvas"
    fi
    
    # Process image overlays
    if [[ ${#IMAGE_OVERLAYS[@]} -gt 0 ]]; then
        process_image_overlays "$temp_image"
    fi
    
    # Process text overlays
    if [[ ${#TEXT_OVERLAYS[@]} -gt 0 ]]; then
        process_text_overlays "$temp_image"
    fi
    
    # Create final image
    create_final_image "$temp_image"
    
    # Clean up temporary files
    cleanup
    
    echo "Successfully created: $OUTPUT"
}

# Handle help option
if [[ "$1" == "-h" || "$1" == "--help" ]]; then
    usage
    exit 0
fi

# Cleanup on exit
cleanup() {
    rm -f /tmp/resized_overlay_*.png /tmp/opacity_overlay_*.png /tmp/base_image.png /tmp/text_overlay_*.mpc /tmp/text_overlay_*.cache
}
trap cleanup EXIT

# Run main function
main "$@"
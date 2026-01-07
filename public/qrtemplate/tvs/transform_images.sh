#!/bin/bash

# overlay_images.sh - Advanced image overlay script with unlimited images and text
# Usage: ./overlay_images.sh [config_file]

# Default configuration file
CONFIG_FILE="${1:-overlay_config.conf}"

# Default values
BACKGROUND="template.jpg"
OUTPUT="output.png"
QUALITY=95
VERBOSE=false

# Arrays to store overlay configurations
declare -a IMAGE_OVERLAYS
declare -a TEXT_OVERLAYS

# Function to display usage
usage() {
    echo "Usage: $0 [config_file]"
    echo "Default config file: overlay_config.conf"
    echo ""
    echo "Config file format:"
    echo "BACKGROUND=input.jpg"
    echo "OUTPUT=result.png"
    echo "QUALITY=95"
    echo "VERBOSE=true"
    echo ""
    echo "# Image overlays:"
    echo "OVERLAY_IMAGE_1=file=image1.png,x=50,y=100,width=200,height=100,opacity=1.0"
    echo "OVERLAY_IMAGE_2=file=logo.png,x=300,y=200,width=150,height=150,opacity=0.8"
    echo ""
    echo "# Text overlays:"
    echo "OVERLAY_TEXT_1=text='Welcome!',x=100,y=300,fontsize=48,fontcolor=white,fontfile=font.ttf"
    echo "OVERLAY_TEXT_2=text='Hotel Khusi',x=200,y=400,fontsize=36,fontcolor=black,box=1,boxcolor=white@0.5"
    echo ""
    echo "See README for complete options list"
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
    
    # Use regex to extract the value with proper quoting
    if [[ $params_str =~ $key=\'([^\']+)\' ]]; then
        echo "${BASH_REMATCH[1]}"
    elif [[ $params_str =~ $key=([^[:space:]]+) ]]; then
        echo "${BASH_REMATCH[1]}"
    else
        echo "$default"
    fi
}

# Function to build FFmpeg filter complex
build_filter_complex() {
    local filter_complex=""
    local input_count=1  # Start after background (input 0)
    declare -A OVERLAY_INFO
    
    # Process image overlays
    for i in "${!IMAGE_OVERLAYS[@]}"; do
        local params="${IMAGE_OVERLAYS[$i]}"
        local parsed_params=$(parse_overlay_params "$params")
        
        local file=$(get_param "$parsed_params" "file")
        local x=$(get_param "$parsed_params" "x" "0")
        local y=$(get_param "$parsed_params" "y" "0")
        local width=$(get_param "$parsed_params" "width")
        local height=$(get_param "$parsed_params" "height")
        local opacity=$(get_param "$parsed_params" "opacity" "1.0")
        
        if [[ ! -f "$file" ]]; then
            echo "Error: Overlay image '$file' not found!"
            exit 1
        fi
        
        # Scale if dimensions specified
        if [[ -n "$width" && -n "$height" ]]; then
            filter_complex+="[$input_count:v]scale=$width:$height,format=rgba,colorchannelmixer=aa=$opacity[ovrl$i]; "
        else
            filter_complex+="[$input_count:v]format=rgba,colorchannelmixer=aa=$opacity[ovrl$i]; "
        fi
        
        # Store overlay info for later use
        OVERLAY_INFO[$i]="$x:$y:ovrl$i"
        ((input_count++))
    done
    
    # Start with background
    local current_layer="[0:v]"
    
    # Add image overlays
    for i in "${!OVERLAY_INFO[@]}"; do
        IFS=':' read -r x y overlay <<< "${OVERLAY_INFO[$i]}"
        filter_complex+="$current_layer[$overlay]overlay=x=$x:y=$y[tmp$i]; "
        current_layer="[tmp$i]"
    done
    
    # Add text overlays
    for i in "${!TEXT_OVERLAYS[@]}"; do
        local params="${TEXT_OVERLAYS[$i]}"
        local parsed_params=$(parse_overlay_params "$params")
        
        local text=$(get_param "$parsed_params" "text")
        local x=$(get_param "$parsed_params" "x" "0")
        local y=$(get_param "$parsed_params" "y" "0")
        local fontsize=$(get_param "$parsed_params" "fontsize" "24")
        local fontcolor=$(get_param "$parsed_params" "fontcolor" "white")
        local fontfile=$(get_param "$parsed_params" "fontfile")
        local box=$(get_param "$parsed_params" "box" "0")
        local boxcolor=$(get_param "$parsed_params" "boxcolor" "black@0.5")
        local boxborderw=$(get_param "$parsed_params" "boxborderw" "5")
        
        # Build drawtext filter - properly escape special characters
        text=$(echo "$text" | sed "s/'/\\\\'/g")
        
        local drawtext="drawtext="
        if [[ -n "$fontfile" && -f "$fontfile" ]]; then
            drawtext+="fontfile='$fontfile':"
        elif [[ -n "$fontfile" ]]; then
            echo "Warning: Font file '$fontfile' not found, using default font"
        fi
        
        drawtext+="text='$text':x=$x:y=$y:fontsize=$fontsize:fontcolor=$fontcolor"
        
        # Add box if requested
        if [[ "$box" == "1" ]]; then
            drawtext+=":box=1:boxcolor=$boxcolor:boxborderw=$boxborderw"
        fi
        
        if [[ $i -eq $((${#TEXT_OVERLAYS[@]} - 1)) ]]; then
            # Last text overlay doesn't need temporary output
            filter_complex+="$current_layer$drawtext"
        else
            filter_complex+="$current_layer$drawtext[text$i]; "
            current_layer="[text$i]"
        fi
    done
    
    echo "$filter_complex"
}

# Function to run FFmpeg command
run_ffmpeg() {
    local filter_complex="$1"
    local input_files=("$BACKGROUND")
    
    # Collect all overlay image files
    for overlay in "${IMAGE_OVERLAYS[@]}"; do
        local parsed_params=$(parse_overlay_params "$overlay")
        local file=$(get_param "$parsed_params" "file")
        input_files+=("$file")
    done
    
    # Build FFmpeg command
    local cmd="ffmpeg"
    if ! $VERBOSE; then
        cmd+=" -loglevel error"
    fi
    
    # Add input files
    for file in "${input_files[@]}"; do
        if [[ ! -f "$file" ]]; then
            echo "Error: Input file '$file' not found!"
            exit 1
        fi
        cmd+=" -i \"$file\""
    done
    
    # Add filter complex and output options
    cmd+=" -filter_complex \"$filter_complex\""
    cmd+=" -q:v $QUALITY -y \"$OUTPUT\""
    
    # Execute command
    if $VERBOSE; then
        echo "Running command: $cmd"
        echo "Filter complex: $filter_complex"
    fi
    
    eval $cmd
    
    if [[ $? -eq 0 ]]; then
        echo "Successfully created: $OUTPUT"
    else
        echo "Error: FFmpeg command failed!"
        exit 1
    fi
}

# Main execution
main() {
    # Check if FFmpeg is available
    if ! command -v ffmpeg &> /dev/null; then
        echo "Error: ffmpeg is not installed!"
        exit 1
    fi
    
    # Parse configuration
    parse_config
    
    if $VERBOSE; then
        echo "Background: $BACKGROUND"
        echo "Output: $OUTPUT"
        echo "Quality: $QUALITY"
        echo "Image overlays: ${#IMAGE_OVERLAYS[@]}"
        for i in "${!IMAGE_OVERLAYS[@]}"; do
            echo "  Image $((i+1)): ${IMAGE_OVERLAYS[$i]}"
        done
        echo "Text overlays: ${#TEXT_OVERLAYS[@]}"
        for i in "${!TEXT_OVERLAYS[@]}"; do
            echo "  Text $((i+1)): ${TEXT_OVERLAYS[$i]}"
        done
    fi
    
    # Build filter complex
    local filter_complex=$(build_filter_complex)
    
    # Run FFmpeg
    run_ffmpeg "$filter_complex"
}

# Handle help option
if [[ "$1" == "-h" || "$1" == "--help" ]]; then
    usage
    exit 0
fi

# Run main function
main "$@"
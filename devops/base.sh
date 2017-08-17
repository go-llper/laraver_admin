#!/bin/bash

NC='\033[0m'      # Normal Color
RED='\033[0;31m'  # Error Color
CYAN='\033[0;36m' # Info Color

# CONFIG_SERVER
# DOCKER_DOMAIN
# devops_prj_path

. $devops_prj_path/base/string.sh

if [ -t 1 ] ; then 
    docker_run_fg_mode='-it'
else 
    docker_run_fg_mode='-i'
fi

function run_cmd() {
    local t=`date`
    echo "$t: $1"
    eval $1
}

function ensure_dir() {
    if [ ! -d $1 ]; then
        run_cmd "mkdir -p $1"
    fi
}

function container_is_running() {
    local container_name=$1
    local num=$(docker ps -a -f name="^/$container_name$" -q | wc -l)
    if [ "$num" == "1" ]; then
        local ret=$(docker inspect -f {{.State.Running}} $1)
        echo $ret
    else
        echo 'false'
    fi
}

function stop_container() {
    local container_name=$1
    local cmd="docker ps -a -f name='^/$container_name$' | grep '$container_name' | awk '{print \$1}' | xargs -I {} docker rm -f --volumes {}"
    run_cmd "$cmd"
}

function push_image() {
    local image_name=$1
    local url=$DOCKER_DOMAIN/$image_name
    run_cmd "docker tag $image_name $url"
    run_cmd "docker push $url"
}

function docker0_ip() {
    local host_ip=$(ip addr show docker0 | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1' | awk '{print $1}' | head  -1)
    echo $host_ip
}


function inner_ip() {
    local inner_ip=$(ip addr show eth0 | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1' | awk '{print $1}' | head  -1)
    echo $inner_ip
}


function pull_image() {
    local image_name=$1
    local url=$DOCKER_DOMAIN/$image_name
    run_cmd "docker pull $url"
    run_cmd "docker tag $url $image_name"
}

function render_local_config() {
    local config_key=$1
    local template_file=$2
    local config_file=$3
    local out=$4

    shift
    shift
    shift
    shift

    local config_type=yaml
    cmd="curl -s -F 'template_file=@$template_file' -F 'config_file=@$config_file' -F 'config_key=$config_key' -F 'config_type=$config_type'"
    for kv in $*
    do
        cmd="$cmd -F 'kv_list[]=$kv'"
    done
    cmd="$cmd $CONFIG_SERVER/render-config > $out"
    run_cmd "$cmd"
    head $out && echo
}

function read_kv_config() {
    local file=$1
    if [ ! -f $file ]; then
        echo "Config file $file is not existent."
        exit 1
    fi
    local key=$2
    cat $file | grep "$key=" | awk -F '=' '{print $2}'
}

function render_server_config {
    local config_key=$1
    local template_file=$2
    local config_file_name=$3
    local out=$4

    shift
    shift
    shift
    shift

    cmd="curl -s -F 'template_file=@$template_file' -F 'config_key=$config_key' -F 'config_file_name=$config_file_name'"
    for kv in $*
    do
        cmd="$cmd -F 'kv_list[]=$kv'"
    done
    cmd="$cmd $CONFIG_SERVER/render-config > $out"
    run_cmd "$cmd"
    head $out && echo
}

function list_contains() {
    local var="$1"
    local str="$2"
    local val

    eval "val=\" \${$var} \""
    [ "${val%% $str *}" != "$val" ]
}

action=${1:-help}

. $devops_prj_path/base/init.sh

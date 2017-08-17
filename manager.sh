#!/bin/bash
set -e

prj_path=$(cd $(dirname $0); pwd -P)
devops_prj_path="$prj_path/devops"
templates_path="$devops_prj_path/templates"

load_init_module=1

CONFIG_SERVER='http://dev-config.jiudouyu.com.cn'
DOCKER_DOMAIN='docker-registry.sunfund.com'

src_path_in_docker='/opt/src'

php_image=9douyu-php
nginx_image=nginx:1.11-alpine
mysql_image=mysql:5.7
redis_image=redis:3.0.1

app_basic_name="laravel-rbac"

init_config_by_developer_name() {

    app="$developer_name-$app_basic_name"

    nginx_container="$app-nginx"
    mysql_container="$app-mysql"
    php_container="$app-php"
    redis_container="$app-redis"

    app_storage_path="/opt/data/$app"
    logs_dir="$app_storage_path/logs"
    app_tmp_path="$app_storage_path/tmp"

    mysql_data_dir=$app_storage_path/mysql

    dev_config_file="$app_tmp_path/auto-gen.manager.config"
    app_nginx_config_path="$app_storage_path/nginx-config"
}

_ensure_dir_and_file() {
    ensure_dir "$app_storage_path"
    ensure_dir "$app_tmp_path"
    ensure_dir "$app_nginx_config_path"

    ensure_dir "$logs_dir"
    if [ ! -f "$logs_dir/php-fpm-error.log" ]; then
        run_cmd "touch $logs_dir/php-fpm-error.log"
    fi
    if [ ! -f "$logs_dir/php-fpm-slow" ]; then
        run_cmd "touch $logs_dir/php-fpm-slow.log"
    fi
}

do_init_for_dev() {

    _ensure_dir_and_file

    local config_key="9douyu-rbac.vars.site_list.$developer_name"
    local config_file_name="sites-config"
    local template_file="$templates_path/manager.config.template"
    local dst_file="$dev_config_file"
    local extra_kv_list="developer_name=$developer_name"
    render_server_config $config_key $template_file $config_file_name $dst_file $extra_kv_list

    template_file="$templates_path/nginx/app.dev.conf"
    dst_file="$app_nginx_config_path/app.conf"
    render_server_config $config_key $template_file $config_file_name $dst_file $extra_kv_list

    run_cmd "cp $templates_path/nginx/fastcgi.conf $app_nginx_config_path/fastcgi.conf"
    run_cmd "cp $devops_prj_path/env-config/.env.dev.example $prj_path/.env"
}

do_init_for_deploy() {

    _ensure_dir_and_file

    run_cmd "cp $templates_path/nginx/app.$env.conf $app_nginx_config_path/app.conf"
    run_cmd "cp $templates_path/nginx/fastcgi.conf  $app_nginx_config_path/fastcgi.conf"
    run_cmd "cp $devops_prj_path/env-config/.env.$env.example $prj_path/.env"
}

load_config_for_dev() {
    if [ ! -f $dev_config_file ]; then
        echo "Config file $dev_config_file is not existent. Please call init_dev first."
        exit 1
    fi
    app_nginx_port=$(read_kv_config "$dev_config_file" "http_port")
    app_mysql_port=$(read_kv_config "$dev_config_file" "mysql_port")
}

load_config_for_deploy() {
    app_nginx_port=80
    app_mysql_port=3307
}

source $devops_prj_path/base.sh

pull() {
    pull_image $php_image
}

run_mysql() {
    local args="--restart always"
    args="$args -v $mysql_data_dir:/var/lib/mysql"

    # port
    args="$args -p $app_mysql_port:3306"

    # config
    args="$args -v $devops_prj_path/config/mysql/conf/:/etc/mysql/conf.d/"

    # auto import data
    args="$args -v $devops_prj_path/data/mysql-init:/docker-entrypoint-initdb.d/"

    args="$args -v $prj_path:$src_path_in_docker"
    args="$args -w $src_path_in_docker"

    args="$args -e MYSQL_ROOT_PASSWORD='123456'"
    run_cmd "docker run -d $args --name $mysql_container $mysql_image"
    #_wait_mysql
}

_wait_mysql() {
    local cmd="while ! mysqladmin ping -h 127.0.0.1 --silent; do sleep 1; done"
    _exec_cmd_in_mysql_container "$cmd"
}

stop_mysql() {
    stop_container $mysql_container
}

restart_mysql() {
    stop_mysql
    run_mysql
}

_exec_cmd_in_mysql_container() {
    local cmd=$1
    run_cmd "docker exec $docker_run_fg_mode $mysql_container bash -c '$cmd'"
}

to_mysql() {
    local cmd="mysql -h 127.0.0.1 -P 3306 -u root -p"
    _exec_cmd_in_mysql_container "$cmd"
}

remove_mysql() {
    stop_mysql
    local cmd="rm -rf $mysql_data_dir"
    _sudo_for_stroage "$cmd"
}

_sudo_for_stroage() {
    run_cmd "docker run --rm $docker_run_fg_mode -v /opt/data:/opt/data $nginx_image sh -c '$cmd'"
}


run_redis() {
    local args="--restart always"
    args="$args -v $devops_prj_path/config/redis/conf:/usr/local/etc/redis/redis.conf"
    run_cmd "docker run -d $args --name $redis_container $redis_image"
}

stop_redis() {
    stop_container $redis_container
}

to_redis() {
    local cmd='redis-cli'
    run_cmd "docker exec -it $redis_container bash -c '$cmd'"
}

restart_redis() {
    stop_redis
    run_redis
}

run_php() {
    local args="--restart always"
    args="$args --cap-add SYS_PTRACE"

    args="$args -v $logs_dir:/var/log/php"
    if [ "$env" = 'dev' ]; then
        args="$args -v $devops_prj_path/config/php/conf/php-dev.ini:/usr/local/etc/php/php.ini"
    else
        args="$args -v $devops_prj_path/config/php/conf/php-prod.ini:/usr/local/etc/php/php.ini"
    fi
    args="$args -v $devops_prj_path/config/php/conf/php-fpm.conf:/usr/local/etc/php-fpm.conf"

    args="$args -v $prj_path:$src_path_in_docker"
    args="$args -w $src_path_in_docker"


    args="$args --link $mysql_container:mysql"
    args="$args --link $redis_container:redis"


    local cmd='/usr/local/sbin/php-fpm -R'
    run_cmd "docker run -d $args --name $php_container $php_image $cmd"
}

stop_php() {
    stop_container $php_container
}

restart_php() {
    stop_php
    run_php
}

to_php() {
    run_cmd "docker exec $docker_run_fg_mode $php_container sh"
}

run_nginx() {
    local nginx_data_dir="$devops_prj_path/nginx-data"
    local nginx_log_path="$logs_dir/nginx"

    local args="--restart=always"

    args="$args -p $app_nginx_port:80"

    # nginx config
    args="$args -v $nginx_data_dir/conf/nginx.conf:/etc/nginx/nginx.conf"

    # for the other sites
    args="$args -v $nginx_data_dir/conf/extra/:/etc/nginx/extra"

    # logs
    args="$args -v $nginx_log_path:/var/log/nginx"

    # generated nginx docker sites config
    args="$args -v $app_nginx_config_path:/etc/nginx/docker-sites"

    args="$args --link $php_container:php"

    args="$args -v $prj_path:$src_path_in_docker"

    run_cmd "docker run -d $args --name $nginx_container $nginx_image"
    
    local app_config_file="$app_nginx_config_path/app.conf"
    local web=`cat $app_config_file | grep 'server_name' | awk '{print $2}'`
    echo "http://$web"
}

stop_nginx() {
    stop_container $nginx_container
}

restart_nginx() {
    stop_nginx
    run_nginx
}

run() {
    run_redis
    run_mysql
    run_php
    run_nginx
}

stop() {
    stop_nginx
    stop_php
    stop_mysql
    stop_redis
}

restart() {
    stop
    run
}

clean() {
    stop
    remove_mysql
    local cmd="rm -rf $app_storage_path"
    _sudo_for_stroage "$cmd"
}

run_prod(){
   run_php
   run_nginx
}

stop_prod(){
   stop_nginx
   stop_php
}

restart_prod(){
   stop_prod
   run_prod
}

clean_prod() {
    stop_prod
    local cmd="rm -rf $app_storage_path"
    _sudo_for_stroage "$cmd"
}
   
help() {
cat <<EOF
    Usage: manage.sh [options]

        Valid options are:

        init_dev
        deploy

        pull

        run
        stop
        restart
        clean

        run_mysql
        stop_mysql
        restart_mysql
        remove_mysql
        to_mysql

        run_php
        stop_php
        restart_php
        to_php

        run_nginx
        stop_nginx
        restart_nginx

        help    show this message
EOF
}
ALL_COMMANDS="init_dev deploy pull"
ALL_COMMANDS="$ALL_COMMANDS run stop restart clean run_prod stop_prod restart_prod clean_prod"
ALL_COMMANDS="$ALL_COMMANDS run_mysql stop_mysql restart_mysql remove_mysql to_mysql"
ALL_COMMANDS="$ALL_COMMANDS run_php to_php stop_php restart_php to_php"
list_contains ALL_COMMANDS "$action" || action=help
$action "$@"

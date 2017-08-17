#!/bin/bash

# define: load_init_module
# defined: init_config_by_developer_name / do_init_for_dev / do_init_for_deploy / load_config_for_dev / load_config_for_deploy

developer_name=$('whoami')

if [ "$load_init_module" == "1" ]; then
    if [ "$action" = 'deploy' ]; then
        if [ $# -lt 3 ]; then
            echo "usage sh $0 deploy env cmd";
            exit 1
        fi
        env=$2
        if [ -z "$env" ]; then
            env='prod'
        fi
        init_config_by_developer_name
        do_init_for_deploy
        load_config_for_deploy
        action=$3
        return
    fi
    if [ "$action" = 'init_dev' ]; then
        env='dev'
        init_config_by_developer_name
        do_init_for_dev
        exit 0
    else
        env='dev'
        if [ $(str_contains "$(hostname)" 'test-') == 1 ]; then
            env='test'
        fi

        init_config_by_developer_name
        load_config_for_dev
    fi
fi

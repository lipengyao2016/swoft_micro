#!/bin/sh

# for debug only
#set -x

# check if term supports UTF8
# shell supports UTF-8 not meaning that Chinese char can be displayed
# (for example: linux tty without any kernel patch
if [ ${LANG%'UTF-8'} != ${LANG} ] || [ ${LC_ALL%'UTF-8'} != ${LC_ALL} ]
then
    USE_UTF8=yes
fi

# install node-agent first
./deploy_env.sh || {
    if [ x"${USE_UTF8}" != "x" ] ; then
        printf "安装node-agent失败\nFailed to install node-agent...\n"
    else
        printf "Failed to install node-agent...\n"
    fi
    exit 1
}

# ask for if install ext
if [ x"${USE_UTF8}" != "x" ] ; then
    printf "要为php安装swoole_tracker扩展吗\nInstall swoole_tracker extension for PHP?(Y/n) "
else
    # if it not supported, use pure ascii prompt
    printf "Install swoole_tracker extension for PHP?(Y/n) "
fi
read extinst
if [ 'xyes' = "x${extinst}" ] || [ 'xy' = "x${extinst}" ] || [ 'xY' = "x${extinst}" ] || [ 'x' = "x${extinst}" ]
then
    /opt/swoole/script/luajit /opt/swoole/script/extinst.lua swoole_tracker70.so swoole_tracker71.so swoole_tracker72.so swoole_tracker73.so
fi

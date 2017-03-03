#!/usr/bin/env bash

# get the directory in which this script is stored
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
touch ${DIR}/jatbox_stop

touch /tmp/hobopyjamas
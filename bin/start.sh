#!/usr/bin/env bash

# Cleanup function that runs when this script exits. Normally this will be after the stop file (see the loop at bottom)
# has been created to signal the script to end, but we bind it to the EXIT signal so that the script will clean up after
# itself if it's terminated unexpectedly
finish(){
    vagrant halt
    pkill -f "selenium-server"
    rm -f ${STOP_FILE}
}
trap finish EXIT

# get the directory in which this script is stored
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
STOP_FILE=/tmp/jatbox_stop
cd ${DIR}/..

# Start the VM that will run our tests
vagrant up

# Start the local Selenium node for running local tests
selenium/node &

open index.html

# Don't end this script until the stop file has been put into place
while [ ! -f ${STOP_FILE} ] ; do
    sleep 1
done
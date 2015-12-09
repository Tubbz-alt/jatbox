#!/usr/bin/env bash

# behat_grid - This script runs every profile in the selenium grid configuration
# that is marked with the @SeleniumGrid annotation in parallel and dumps their
# output to reports/

CONFIG_PATH=/vagrant/behat_selenium_grid.yml

# Replace spaces with underscores and remove : from date, used for report file name
TIMESTAMP=`date | sed -e 's/ /_/g' | sed -e 's/://g'`

REPORTS_TEMP_DIR=reports/${TIMESTAMP}

mkdir ${REPORTS_TEMP_DIR}

# cat -> Gets the contents of the behat configuration
# grep -> Filters just the lines containing the @SeleniumGrid annotation
# cut -> Discards the colon and everything after from the matched lines
# parallel -> Runs bin/behat with each profile in parallel, dump output to temp folder
cat ${CONFIG_PATH} | grep "@SeleniumGrid" | cut -d ":" -f1 | parallel "/vagrant/bin/behat --config=${CONFIG_PATH} --profile={} > ${REPORTS_TEMP_DIR}/{}"

# Combine the files
tail -n +1 ${REPORTS_TEMP_DIR}/* > reports/${TIMESTAMP}.txt

# Delete the temporary files
rm -rf ${REPORTS_TEMP_DIR}
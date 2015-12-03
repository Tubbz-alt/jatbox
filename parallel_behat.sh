#!/usr/bin/env bash
mkdir -p test_out
find features/ -iname '*.feature' | parallel --gnu 'bin/behat --ansi --format pretty,html --out ,test_out/index.html {}' || exit 1
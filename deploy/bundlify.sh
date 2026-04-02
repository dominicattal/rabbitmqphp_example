#!/bin/bash

if [ $# -ne 1 ]; then
    echo "Usage: bundlify.sh [path_to_bundle]"
fi

bundle_path=$1
if [ -z "$bundle_path/info.ini" ]; then
    echo "Missing info.ini, consult README.md"
fi
if [ ! -d "$bundle_path/files" ]; then
    echo "Missing files folder, consult README.md"
fi

tar -cvf "$(basename $bundle_path).tar" "$bundle_path"


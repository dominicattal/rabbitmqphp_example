#!/bin/bash

if [ $# -ne 1 ]; then
    echo "Usage: bundlify.sh [path_to_bundle]"
    exit 1
fi

bundle_path=$1
if [ -z "$bundle_path/info.ini" ]; then
    echo "Missing info.ini, consult README.md"
    exit 1
fi
if [ ! -d "$bundle_path/files" ]; then
    echo "Missing files folder, consult README.md"
    exit 1
fi

archive_path="/tmp/$(basename $bundle_path).tar"
echo $archive_path
tar -C "$bundle_path" -cvf "$archive_path" "info.ini" "files"

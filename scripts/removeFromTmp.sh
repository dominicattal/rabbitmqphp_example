#!/bin/bash
#file needs to be dynamically made to be the incoming file

name=$1
type=$2;
#newName=${type}_${version}_${name}

echo "$name"

mkdir ~/bundles
cp /tmp/$name ~/bundles/$name
rm /tmp/$name

echo "${name}"

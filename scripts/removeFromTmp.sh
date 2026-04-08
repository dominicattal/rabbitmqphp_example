#!/bin/bash
#file needs to be dynamically made to be the incoming file

name=$1
type=$2;
version=$3;
newName=${type}_${version}_${name}

echo "$name"

mkdir ~/bundles
cp /tmp/$name ~/bundles/$newName
rm /tmp/$name

#Now need to unzip the bundle then store its data in the DB
#Need to unzip it, give it a name as a concat of its version + type

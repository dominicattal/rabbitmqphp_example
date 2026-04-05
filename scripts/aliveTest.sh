#!/bin/bash

i=0

if ping -c 1 100.111.93.122 > /dev/null 2>&1; then
  echo "online!"
else
   echo "offline!"
 fi

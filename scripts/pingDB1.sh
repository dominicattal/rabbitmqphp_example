#!/bin/bash

i=0

if ping -c 1 127.0.0.1 > /dev/null 2>&1; then
  echo "online!"
else
   echo "offline!"
 fi

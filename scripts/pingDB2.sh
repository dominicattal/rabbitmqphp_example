#!/bin/bash

if ping -c 1 100.73.249.27 > /dev/null 2>&1; then
  echo "online!"
else
   echo "offline!"
fi

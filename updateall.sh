#!/bin/bash

find bundles -type d -name "*Bun" -exec deploy/client.php update {} \;

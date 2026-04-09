#!/bin/bash
echo "Hello World"          # <--- this gets sent to $output array
`echo "Goodbye World" >&2`  # <--- this prints to stderr
if [ ! -d sample ]; then
	mkdir sample
fi
mv navbar.php search.php validation_handler.php header.php sample/

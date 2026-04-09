#!/bin/bash
echo "Hello World"          # <--- this gets sent to $output array
`echo "Goodbye World" >&2`  # <--- this prints to stderr
if [ ! -d sample ]; then
		mkdir sample
fi
mv /tmp/files/{details.php,get_reviews_handler.php,reviews.html,reviewsView.html,reviewsView_handler.php,reviews_handler.php} sample/



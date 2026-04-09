#!/bin/bash
file="db_version.txt"
v=0

if [ -r "$file" ]; then
	v=$(cat "$file")
	v=$((v + 1))
else
	echo "Error: Cannot find file db_version.txt" >&2
	exit 1
fi
rm -r db_bundle

mkdir db_bundle
mkdir db_bundle/files
cp db.php db_bundle/files
cp schema.sql db_bundle/files
cp scripts/db.sh db_bundle/files
cp scripts/db_clean.sh db_bundle/files
cp scripts/db_purge.sh db_bundle/files

touch info.ini
echo "[info]
BUNDLE_NAME=\"test_bundle\" 
BUNDLE_HR_NAME=\"Test Bundle\"
BUNDLE_DESC=\"This Bundle is a Test\"
BUNDLE_TYPE=\"db\"" > info.ini

mv info.ini db_bundle

name="db_bundle"${v}

tar -cf "${name}.tar" db_bundle
echo $v > db_version.txt


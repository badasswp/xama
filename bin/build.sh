#!/bin/bash

# Build Parameters
PLUGIN_FILE="xama.php"
PLUGIN_BUILD="build"
PLUGIN_DIRS="dist inc languages"

# Create the build directory
if [ ! -d "$PLUGIN_BUILD" ]; then
  	mkdir "$PLUGIN_BUILD"
else
  	rm -r "$PLUGIN_BUILD"
	mkdir "$PLUGIN_BUILD"
fi

# Ensure Latest NPM build
echo "Building NPM production files..."
echo "================================"
npm run build

# Copy Plugin directories
echo ""
echo "Building Plugin directories..."
echo "=============================="
for dir in $PLUGIN_DIRS
do
  cp -rv "$dir" "$PLUGIN_BUILD/$dir"
done

# Copy Plugin files
echo ""
echo "Building Plugin setup files..."
echo "=============================="
cp -v "$PLUGIN_FILE" "$PLUGIN_BUILD/$PLUGIN_FILE"
cp -v "readme.txt" "$PLUGIN_BUILD/readme.txt"

# Set up Vendor within Build
echo ""
echo "Building Composer autoload files..."
echo "==================================="
cp -v "composer.json" "$PLUGIN_BUILD/composer.json"
cp -v "composer.lock" "$PLUGIN_BUILD/composer.lock"
cd "$PLUGIN_BUILD"
composer install --no-dev
rm -v "composer.json"
rm -v "composer.lock"

# Finish Build
echo ""
echo "Build Complete!"
echo "==============="

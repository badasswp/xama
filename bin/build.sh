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
for dir in $PLUGIN_DIRS
do
  cp -r "$dir" "$PLUGIN_BUILD/$dir"
done

# Copy Plugin files
cp "$PLUGIN_FILE" "$PLUGIN_BUILD/$PLUGIN_FILE"
cp "readme.txt" "$PLUGIN_BUILD/readme.txt"
cp "composer.json" "$PLUGIN_BUILD/composer.json"
cp "composer.lock" "$PLUGIN_BUILD/composer.lock"

# Set up Vendor within Build
cd "$PLUGIN_BUILD"
composer install --no-dev
rm "composer.json"
rm "composer.lock"

# Finish Build
echo "Build complete!"

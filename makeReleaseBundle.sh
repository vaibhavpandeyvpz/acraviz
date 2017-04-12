#!/bin/sh

if [ $# -ne 1 ]
    then
        echo "$0 versionName"
        exit -1
fi

version=$1

composer run-script build

zip --symlinks -r release-${version}.zip . -x .\* -x bower_components/\* -x node_modules/\* -x .env\* -x composer.\* \
                                -x bower.json -x .gitignore -x gulpfile.js -x makeReleaseBundle.sh -x package.json \
                                -x release-\*.zip
#!/usr/bin/env bash

packages=(Customer MapRoute Finance Company StaticList)

for package in ${packages[@]}; do
        echo "testing ${package}"
        cd ${package}
        bin/phpspec run
        cd ..
done

exit 0
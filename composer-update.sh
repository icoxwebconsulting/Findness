#!/usr/bin/env bash

packages=(Customer MapRoute framework)

for package in ${packages[@]}; do
        echo "composer update ${package}"
        cd ${package}
        composer update
        cd ..
done

exit 0
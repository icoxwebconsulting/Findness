#!/usr/bin/env bash

PRODUCTION=false

for i in "$@"
do
case $i in
    -p|--production)
    PRODUCTION=true
    shift # past argument=value
    ;;
    *)
          # unknown option
    ;;
esac
done

packages=(Customer MapRoute Finance Company StaticList framework)

for package in ${packages[@]}; do
    cd ${package}
    if [[ "$PRODUCTION" = true ]]
    then
        echo "composer update ${package} <optimized>"
        SYMFONY_ENV=prod composer update -o --no-dev
    else
        echo "composer update ${package}"
        composer update
    fi
    cd ..
done

exit 0
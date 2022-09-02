#!/bin/bash

docker_user=''
proj_root="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

docker_user="--user $(id -u):$(id -g)"

docker run --rm --volume $proj_root/www/ms_babel:/app $docker_user composer install --ignore-platform-reqs
#!/bin/bash

docker run -v $(pwd):/var/loadtest --net host -it --rm direvius/yandex-tank -c tank/config.ini

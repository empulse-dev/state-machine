#!/bin/bash
docker run -it --rm -v .:/empulse -w $PWD php:cli $@
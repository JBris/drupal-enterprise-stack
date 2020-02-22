#!/usr/bin/env bash

if [ ! -f ".env" ]; then 
    cp .env.example .env 
    echo "Please edit .env before continuing"
    exit 0
fi

while getopts ce opt; do
    case $opt in
        c) 
            cp .env.example .env  
            ;;
        e)
            echo "Please edit .env"
            exit 0
            ;; 
        *) 
            exit 1
            ;;
  esac
done

. .env

shift $(($OPTIND - 1))

make down
make pull
make dbuild
make up 
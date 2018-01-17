#! /bin/bash

IMG_NAME="schora"

#echo "Remove old image"
#docker rmi -f $IMG_NAME

echo "Build docker image $IMG_NAME"
docker build -t $IMG_NAME .

echo "Done"

#!/usr/bin/env bash

IMAGE_NAME=btms:latest
CONTAINER_NAME=btms01
PORTS=4001:4000

{
    {
        docker container stop ${CONTAINER_NAME}
        docker rm ${CONTAINER_NAME}
        {
            docker rmi ${IMAGE_NAME}
        }
    }
    {
        docker build . -t ${IMAGE_NAME}
        #docker run --name ${CONTAINER_NAME} -p ${PORTS} ${IMAGE_NAME}
    }
}
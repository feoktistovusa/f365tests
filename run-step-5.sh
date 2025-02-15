#!/bin/bash

set -e

command_exists() {
    if ! command -v "$1" >/dev/null 2>&1; then
        case "$1" in
            docker)
                echo "Error: Docker is not installed. Please install it from:"
                echo "https://docs.docker.com/get-docker/"
                ;;
            docker-compose|docker\ compose)
                echo "Error: Docker Compose is not installed. Please install it from:"
                echo "https://docs.docker.com/compose/install/"
                ;;
        esac
        exit 1
    fi
}

command_exists docker
command_exists docker compose

if [ "$1" == "down" ]; then
    echo "Stopping and removing Docker containers..."
    docker compose down
    echo "Docker containers have been stopped and removed."
    exit 0
fi

echo "Pulling latest Docker images..."
docker compose pull

echo "Building and starting Docker containers..."
docker compose build main nginx api db
docker compose up -d main nginx api db

echo "Showing running containers..."
docker ps

echo "Docker containers are up and running!"

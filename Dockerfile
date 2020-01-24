# Use an official Elixir runtime as a parent image
FROM ubuntu:latest

RUN apt-get update && apt-get install inotify-tools -y
RUN apt-get -y install curl dirmngr apt-transport-https lsb-release ca-certificates vim
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get -y install nodejs
RUN apt-get install gcc g++ make

# Create app directory and copy the Elixir projects into it
RUN mkdir /app
COPY . /app
WORKDIR /app

# Install hex package manager
RUN mix local.hex --force
# Install Phoenix framwork
RUN mix archive.install https://github.com/phoenixframework/archives/raw/master/phx_new.ez

# Compile the project
RUN mix do compile

RUN cd assets && npm install && cd ..

# Run Server
RUN mix phx.server
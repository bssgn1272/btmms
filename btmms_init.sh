#!/usr/bin/env bash

mix deps.get
{
   mix ecto.create
}

{
  mix ecto.migrations
  mix ecto.migrate
}

cd scale_driver

mix deps.get

cd ..
iex -S mix phx.server

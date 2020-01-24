#!/usr/bin/env bash
{
    export PATH="$PATH:/root/elixir/bin"
}

iex -S mix phx.server

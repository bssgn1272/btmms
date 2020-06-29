#!/bin/sh
export SECRET_KEY_BASE=R5azag9dX9hOumakWQL/v1IQwqvLuGF1HmW//WpcWAdCMUCUzmZHZXdVzTFNiiHB
export DATABASE_URL=ecto://root:Qwerty12@localhost:3306/bus_terminal_system_dev
#export MIX_ENV=prod
#export PORT=4000
mix deps.get --only prod
#mix deps.compile absinthe
npm install --prefix ./assets
mix phx.digest
MIX_ENV=prod mix compile.protocols
MIX_ENV=prod PORT=4000 mix release
_build/dev/rel/bus_terminal_system/bin/bus_terminal_system start_iex

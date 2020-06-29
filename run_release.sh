#!/bin/sh
export SECRET_KEY_BASE=R5azag9dX9hOumakWQL/v1IQwqvLuGF1HmW//WpcWAdCMUCUzmZHZXdVzTFNiiHB
export DATABASE_URL=ecto://root:Qwerty12@localhost:3306/bus_terminal_system_dev
export PORT=4000
export MIX_ENV=prod
_build/prod/rel/bus_terminal_system/bin/bus_terminal_system foreground
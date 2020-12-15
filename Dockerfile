FROM elixir:1.11.2-alpine AS build

# install build dependencies
RUN apk add --no-cache build-base npm git

ARG DATABASE_URL=ecto://root:Qwerty12@ops.probasegroup.com:3306/nrfa_core_db
# app ecto database env argument
#ENV DATABASE_URL=$DATABASE_URL

ARG SECRET_KEY_BASE=R5azag9dX9hOumakWQL/v1IQwqvLuGF1HmW//WpcWAdCMUCUzmZHZXdVzTFNiiHB
# app secret key env argument
#ENV SECRET_KEY_BASE=$SECRET_KEY_BASE

# prepare build dir
WORKDIR /app

# export database url
RUN export DATABASE_URL=ecto://root:Qwerty12@ops.probasegroup.com:3306/nrfa_core_db

# export secret key
RUN export SECRET_KEY_BASE=R5azag9dX9hOumakWQL/v1IQwqvLuGF1HmW//WpcWAdCMUCUzmZHZXdVzTFNiiHB

# install hex + rebar
RUN mix local.hex --force && \
    mix local.rebar --force

# set build ENV
ENV MIX_ENV=prod

# install mix dependencies
COPY mix.exs mix.lock ./
COPY config config
RUN mix do deps.get, deps.compile

# build assets
COPY assets/package.json assets/package-lock.json ./assets/
RUN npm --prefix ./assets ci --progress=false --no-audit --loglevel=error

COPY priv priv
COPY assets assets
RUN npm run --prefix ./assets deploy
RUN mix phx.digest

# compile and build release
COPY lib lib
# uncomment COPY if rel/ exists
# COPY rel rel
RUN mix do compile, release

# prepare release image
FROM alpine:3.9 AS app
RUN apk add --no-cache openssl ncurses-libs

WORKDIR /app

RUN chown nobody:nobody /app

USER nobody:nobody

COPY --from=build --chown=nobody:nobody /app/_build/prod/rel/bus_terminal_system ./

ENV HOME=/app

CMD ["bin/bus_terminal_system", "start"]
defmodule BusTerminalSystemWeb.Schema.ContentTypes do
  use Absinthe.Schema.Notation

  object :tickets do
    field :id, :id
    field :reference_number, :string
    field :serial_number, :string
  end
end
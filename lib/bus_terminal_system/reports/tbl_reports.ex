defmodule BusTerminalSystem.Reporting.Report  do
  use Endon
  use Ecto.Schema

  schema "probase_tbl_reports" do
    field :name, :string
    field :iframe, :string
    field :link, :string
  end

end
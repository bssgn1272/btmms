defmodule BusTerminalSystem.Reporting.Report  do
  use Endon
  use Ecto.Schema

  schema "probase_tbl_reports" do
    field :name, :string
    field :iframe, :string
    field :link, :string
    field :auth_status, :boolean, default: false
    field :maker, :integer
    field :checker, :integer
    field :maker_date_time, :naive_datetime
    field :checker_date_time, :naive_datetime
    field :user_description, :string
    field :system_description, :string
  end

end
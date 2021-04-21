defmodule BusTerminalSystem.Repo.Migrations.Reports do
  use Ecto.Migration

  def up do
    create_if_not_exists table (:probase_tbl_reports) do
      add :name, :string
      add :iframe, :string
      add :link, :string
    end
  end

  def down do
    drop()
  end

  def drop do
    drop_if_exists table(:probase_tbl_reports)
  end
end

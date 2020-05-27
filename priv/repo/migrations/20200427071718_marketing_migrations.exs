defmodule BusTerminalSystem.Repo.Migrations.MarketingMigrations do
  use Ecto.Migration

  def up do

  end

  def up_ do

    create_if_not_exists table(:probase_tbl_market) do
      add :market_name, :string
      add :location, :string
      add :market_uid, :string
      add :city_town, :string
      add :estimated_population, :string

      timestamps()
    end

    create_if_not_exists table(:probase_tbl_market_section) do
      add :section_name, :string
      add :section_lable, :string
      add :number_of_shops, :integer
      add :market_id, :integer

      timestamps()
    end

    create_if_not_exists table(:probase_tbl_market_section_shop) do
      add :shop_code, :string
      add :section_id, :integer
      add :maketeer_id, :integer
      add :shop_number, :integer

      timestamps()
    end

  end

  def down do

    drop_if_exists table(:probase_tbl_market)
    drop_if_exists table(:probase_tbl_market_section)
    drop_if_exists table(:probase_tbl_market_section_shop)
  end

end

defmodule BusTerminalSystem.Repo.Migrations.BusInformationModelLf do
  use Ecto.Migration

  def change do

    alter table(:bus_terminus) do
      add_if_not_exists :fitness_liscence, :string
    end

  end
end

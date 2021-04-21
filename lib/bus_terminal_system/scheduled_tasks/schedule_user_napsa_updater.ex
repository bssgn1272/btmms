defmodule BusTerminalSystem.NapsaUserUpdater do

  alias BusTerminalSystem.AccountManager.User

  def run do
    User.all()
    |> Enum.each(fn user ->
      if user.dob == nil or user.ssn == "-" do
        BusTerminalSystem.Napsa.NapsaQueryDetails.connect(%{"id" => user.nrc})
        |> case do
           nil -> :skip
           napsa_user -> User.update(user, [dob: napsa_user["payload"]["dob"], ssn: napsa_user["payload"]["ssn"]])
         end
      end

    end)
  end
end
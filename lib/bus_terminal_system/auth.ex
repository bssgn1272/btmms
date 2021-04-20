defmodule BusTerminalSystem.Auth do

  def confirm_password(user, password) do
    case user do
      nil -> {:error, "Session login failed"}
      _->
        if user.password == Base.encode16(:crypto.hash(:sha512, password)) do
          {:ok, user}
        else
          {:error, :invalid_credentials}
        end
    end

  end

end
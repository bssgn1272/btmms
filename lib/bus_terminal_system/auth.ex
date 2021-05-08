defmodule BusTerminalSystem.Auth do

  def confirm_password(user, password) do
    case user do
      nil -> {:error, "Session login failed"}
      _->
      IO.inspect user.password
      IO.inspect Base.encode16(:crypto.hash(:sha512, password))
      IO.inspect password

        if user.password == Base.encode16(:crypto.hash(:sha512, password)) do
          {:ok, user}
            check_status(user)
        else
          {:error, :invalid_credentials}
        end
    end

  end
  defp check_status(user) do
    case user.auth_status != false do
      true ->
        {:ok, user}
      false ->
        {:error, "Login failed. Contact Administrator"}
    end

  end
end
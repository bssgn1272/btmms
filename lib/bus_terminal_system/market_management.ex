defmodule BusTerminalSystem.MarketManagement do
  @moduledoc """
  The MarketManagement context.
  """

  import Ecto.Query, warn: false
  alias BusTerminalSystem.Repo

  alias BusTerminalSystem.MarketManagement.Marketer

  @doc """
  Returns the list of marketers.

  ## Examples

      iex> list_marketers()
      [%Marketer{}, ...]

  """
  def list_marketers do
    Repo.all(Marketer)
  end

  @doc """
  Gets a single marketer.

  Raises `Ecto.NoResultsError` if the Marketer does not exist.

  ## Examples

      iex> get_marketer!(123)
      %Marketer{}

      iex> get_marketer!(456)
      ** (Ecto.NoResultsError)

  """
  def get_marketer!(id), do: Repo.get!(Marketer, id)

  @doc """
  Creates a marketer.

  ## Examples

      iex> create_marketer(%{field: value})
      {:ok, %Marketer{}}

      iex> create_marketer(%{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def create_marketer(attrs \\ %{}) do
    %Marketer{}
    |> Marketer.changeset(attrs)
    |> Repo.insert()
  end

  @doc """
  Updates a marketer.

  ## Examples

      iex> update_marketer(marketer, %{field: new_value})
      {:ok, %Marketer{}}

      iex> update_marketer(marketer, %{field: bad_value})
      {:error, %Ecto.Changeset{}}

  """
  def update_marketer(%Marketer{} = marketer, attrs) do
    marketer
    |> Marketer.changeset(attrs)
    |> Repo.update()
  end

  @doc """
  Deletes a Marketer.

  ## Examples

      iex> delete_marketer(marketer)
      {:ok, %Marketer{}}

      iex> delete_marketer(marketer)
      {:error, %Ecto.Changeset{}}

  """
  def delete_marketer(%Marketer{} = marketer) do
    Repo.delete(marketer)
  end

  @doc """
  Returns an `%Ecto.Changeset{}` for tracking marketer changes.

  ## Examples

      iex> change_marketer(marketer)
      %Ecto.Changeset{source: %Marketer{}}

  """
  def change_marketer(%Marketer{} = marketer) do
    Marketer.changeset(marketer, %{})
  end
end

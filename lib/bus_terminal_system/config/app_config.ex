defmodule BusTerminalSystem.AppConfig do

  @moduledoc false

  alias Aggregator.Randomizer

  @config_folder "configuration"
  @config_filename "config.yml"
  @config_dir_path Path.join(File.cwd!(), @config_folder)
  @config_file_path File.cwd!() |> Path.join(@config_folder) |> Path.join(@config_filename)
  @key_config_path_const "config_path"
  @key_config_path_status "config_path_status"
  @key_config_dir "config_dir"
  @key_config_file "config_file"

  def get_config(name, default \\ "") do
    c = name |> gfs
    if c != nil do
      c
    else
      default
    end
  end

  def session_uuid, do: "session_uuid" |> gfs

  def gfs(key), do: Cachex.get!(:session, key)

  def ats(key, value), do: Cachex.put(:session, key, value)

  def cache_lookup_token(key) do
    Cachex.get!(:session, key)
  end

  def cache_register_token(key, value) do
    Cachex.put(:session, key, value)
    Cachex.expire(:session, key, :timer.seconds(300))
  end

  def store_token(key, value) do
    Cachex.put(:session, key, value)
    Cachex.expire(:session, key, :timer.seconds(15))
  end

  def purge_cache do
    :session |> Cachex.purge
  end

  def count_tokens do
    :session |> Cachex.count
  end

  def cwd do
    case File.cwd() do
      {:ok, path} ->
        @key_config_path_const |> ats(path)
        @key_config_path_status |> ats(true)
        @key_config_dir |> ats(@config_dir_path)
        @key_config_file |> ats(@config_file_path)
      {_, _} ->
        @key_config_path_const |> ats("")
        @key_config_path_status |> ats(false)
        @key_config_dir |> ats(@config_dir_path)
        @key_config_file |> ats(@config_file_path)
    end
  end

  def check_dir do
    cwd()

    if @key_config_path_status |> gfs do
      create_config_file()
    end
  end

  defp create_config_file do
    if !(@key_config_file |> gfs |> File.exists?) do
      @key_config_dir |> gfs |> File.mkdir()
      {_, file_pid} = @key_config_file |> gfs |> File.open([:write])
      File.close(file_pid)
    else
      IO.puts("File Exists")
    end
  end

  def read_config_file do
    check_dir()
    file = @config_file_path |> YamlElixir.read_from_file!
  end

  def add_1(number) do
    answer = number + 1
    answer
  end

end
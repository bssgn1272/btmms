defmodule BusTerminalSystem.EmailSender do

  import Swoosh.Email
  alias BusTerminalSystem.Mailer

  def test do
#     Task.async(fn  ->
      new()
      |> to("philip@probasegroup.com")
      |> from("BTMMS@napsa.co.zm")
      |> subject("NAPSA BTMS")
      |> text_body("NAPSA\n")
      |> Mailer.deliver
      |> IO.inspect
#    end)
  end

  def composer_html(to,subject,html) do
    Task.async(fn ->
      new()
      |> to(to)
      |> from("BTMS@probasemail.test.com")
      |> subject(subject)
      |> html_body(html)
      |> Mailer.deliver
    end)
  end

  def composer_text(to,subject,text) do
    Task.async(fn ->
      new()
      |> to(to)
      |> from("BTMS@probasemail.test.com")
      |> subject(subject)
      |> text_body(text)
      |> Mailer.deliver
    end)
  end


end
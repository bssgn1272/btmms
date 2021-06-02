defmodule BusTerminalSystem.EmailSender do

  import Swoosh.Email
  alias BusTerminalSystem.Mailer
  alias BusTerminalSystem.Settings

  def test do
#     Task.async(fn  ->
      new()
      |> to("philip@probasegroup.com")
      |> from("BTMMS@napsa.co.zm")
      |> subject("NAPSA BTMS")
      |> text_body("NAPSA\n")
      |> Mailer.deliver
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

    BusTerminalSystem.Notification.Table.Email.create([
      to: to,
      from: "BTMMS@napsa.co.zm",
      message: text,
      attended: false,
      subject: subject,
      status: "0"
    ])
  end

  def run() do
    if Settings.find_by(key: "EMAIL_SERVICE").value == "TRUE" do
      BusTerminalSystem.Notification.Table.Email.where([attended: false])
      |> Enum.each(fn email ->
        BusTerminalSystem.Notification.Table.Email.update(email, [attended: true])
        Task.async(fn ->
          new()
          |> to(email.to)
          |> from(email.from)
          |> subject(email.subject)
          |> text_body(email.message)
          |> Mailer.deliver
        end)
      end)
    end
  end


end
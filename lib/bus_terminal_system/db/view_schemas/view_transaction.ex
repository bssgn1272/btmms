defmodule BusTerminalSystem.Database.View.Transactions do

  use Endon
  use Ecto.Schema
  import Ecto.Changeset

  @db_columns [:user_id, :teller, :srcAcc, :srcBranch, :srcCurrency, :transferTyp, :transferRef, :referenceNo, :destAcc, :destBranch,
    :payCurrency, :amount, :payDate, :remarks, :status, :request_reference, :op_description, :service, :atd_number,
    :atd_amount, :bank_id, :nrc_no, :account_no, :deposit_date, :bank_ref_number, :name, :senderMobileNo, :reference,
    :currency, :account, :receiverMobileNo, :datePaymentReceived, :paymentMode, :senderEmail, :userName, :customerId,
    :channelType, :country, :service_id, :msisdn, :account_number, :payer_transaction_id, :narration, :extraData, :currency_code,
    :customer_names, :date_payment_received, :extra_data, :payment_mode, :country_code, :beneName, :beneEmail, :beneMobileNo,
    :senderAddress1, :senderAddress2, :senderAddress3, :bankName, :destCurrency, :ipAddress, :senderName, :sendermobileno,
    :sortCode, :otp, :hostrefno, :customerNo, :customerPhoto, :customerSignature, :rrn]

  @derive {Poison.Encoder, only: @db_columns}
  schema "vw_bank_teller_txn" do
    field :teller, :string
    field :user_id, :integer
    field :srcAcc, :string
    field :srcBranch, :string
    field :srcCurrency, :string
    field :transferTyp, :string
    field :transferRef, :string
    field :referenceNo, :string
    field :destAcc, :string
    field :destBranch, :string
    field :payCurrency, :string
    field :amount, :string
    field :payDate, :string
    field :remarks, :string
    field :status, :string
    field :request_reference, :string

    # Operation column
    field :op_description, :string
    field :service, :string
    # Fisp
    field :atd_number, :string
    field :atd_amount, :integer
    field :bank_id, :string

    field :nrc_no, :string
    field :account_no, :string
    field :deposit_date, :string
    field :bank_ref_number, :string

    field :name, :string
    field :senderMobileNo, :string
    field :reference, :string
    field :currency, :string
    field :account, :string
    field :receiverMobileNo, :string
    field :datePaymentReceived, :string
    field :paymentMode, :string
    field :senderEmail, :string

    field :userName, :string
    field :customerId, :string
    field :channelType, :string
    field :country, :string

    field :service_id, :string
    field :msisdn, :string
    field :account_number, :string
    field :payer_transaction_id, :string
    field :narration, :string
    field :extraData, :string
    field :currency_code, :string
    field :customer_names, :string
    field :date_payment_received, :string
    field :extra_data, :string
    field :payment_mode, :string
    field :country_code, :string

    field :beneName, :string
    field :senderName, :string
    field :beneEmail, :string
    field :beneMobileNo, :string
    field :destCurrency, :string
    field :ipAddress, :string
    field :sortCode, :string

    field :serviceId, :string
    field :otp, :string
    field :hostrefno, :string
    field :customerNo, :string
    field :customerPhoto, :string
    field :customerSignature, :string
    field :rrn, :string

    timestamps(type: :utc_datetime)
  end

  #  def changeset(transaction, attrs) do
  #    transaction
  #    |> cast(attrs, @db_columns)
  #    |> validate_required(@db_columns)
  #  end

end
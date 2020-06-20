defmodule BusTerminalSystem.Repo.Migrations.CreateUsers do
  use Ecto.Migration

  def up do
    tmp()
  end

  def tmp do
    create_if_not_exists table(:probase_tbl_users) do
      add :username, :string
      add :password, :string
      add :first_name, :string
      add :last_name, :string
      add :ssn, :string
      add :role, :string
      add :email, :string
      add :mobile, :string
      add :tel, :string
      add :uuid, :string
      add :nrc, :string
      add :account_status, :string
      add :operator_role, :string
      add :pin, :string
      add :tmp_pin, :string
      add :company, :string
      add :account_type, :string
      add :account_number, :string

      timestamps()
    end

    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, inserted_at, updated_at)
                                VALUES ('manager',UPPER(SHA2('password', 512)),'probase','zambia','NOT AVAILABLE','ADMIN','admin@probasegroup.com','+260950773797','000000/00/0','ACTIVE',
                                        'ADMINISTRATOR',current_date,current_date)")

    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, company,inserted_at, updated_at)
                                VALUES ('bop',UPPER(SHA2('password', 512)),'operator','zambia','NOT AVAILABLE','BOP','operator@btmsnapsa.com','+260950773797','000000/00/0','ACTIVE',
                                        'BUS OPERATOR','NAPSA BUS SERVICES',current_date,current_date)")

    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, inserted_at, updated_at)
                                VALUES ('mop',UPPER(SHA2('password', 512)),'marketeer','zambia','NOT AVAILABLE','MOP','marketeer@btmsnapsa.com','+260950773797','000000/00/0','ACTIVE',
                                        'MARKETER',current_date,current_date)")

    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, inserted_at, updated_at)
                                VALUES ('teller',UPPER(SHA2('password', 512)),'teller','zambia','NOT AVAILABLE','TOP','teller@btmsnapsa.com','+260950773797','000000/00/0','ACTIVE',
                                        'TELLER',current_date,current_date)")

  end

  def down do
    drop_if_exists table(:probase_tbl_users)
  end

end

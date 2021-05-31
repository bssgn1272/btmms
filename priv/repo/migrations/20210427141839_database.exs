defmodule BusTerminalSystem.Repo.Migrations.Database do
  use Ecto.Migration

  def up do
    unique_keys()
#    create()
#    alter_tables()
#    run_scripts()
  end
  def down do
#    drop()
  end

  def unique_keys do
    create_if_not_exists table (:probase_tbl_banks) do
      add :bankName, :string
      add :branch, :string
      add :bankCode, :string
      add :bicCode, :string
      add :branchDesc, :string
      add :cntryCode, :string
      add :sortCode, :string

      timestamps()
    end
  end


  def create do
    # BusTerminalSystem.Repo.Migrations.RouteMapping
    create_if_not_exists table(:probase_tbl_route_mapping) do
      add :operator_id, :string
      add :bus_id, :string
      add :route_id, :string
      add :fare, :integer

      add :date, :string
      add :time, :string

      add :route_uid, :integer
      add :auth_status, :boolean

      timestamps()
    end

#    BusTerminalSystem.Repo.Migrations.Reports
    create_if_not_exists table (:probase_tbl_reports) do
      add :name, :string
      add :iframe, :string
      add :link, :string
    end

    create_if_not_exists table (:probase_tbl_settings) do
      add :key, :string
      add :value, :string, size: 2000
      add :status, :boolean

      timestamps()
    end

    create_if_not_exists table(:probase_tbl_travel_routes) do
      add :route_name, :string
      add :start_route, :string
      add :end_route, :string
      add :route_code, :string
      add :ticket_id, :int
      add :route_fare, :int

      add :parent, :integer

      add :source_state, :string
      add :route_uuid, :string

      timestamps()
    end


#    BusTerminalSystem.Repo.Migrations.CreateTickets
    create_if_not_exists table(:probase_tbl_tickets) do
      add :reference_number, :string
      add :serial_number, :string
      add :external_ref, :string
      add :route, :int
      add :date, :string
      add :bus_no, :string
      add :maker, :string
      add :class, :string
      add :activation_status, :string
      add :first_name, :string
      add :last_name, :string
      add :other_name, :string
      add :id_type, :string
      add :passenger_id, :string
      add :mobile_number, :string
      add :email_address, :string
      add :transaction_channel, :string
      add :travel_date, :string
      add :bus_schedule_id, :string
      add :route_information, :string
      add :amount, :float, default: 0, precision: 10, scale: 2
      add :payment_mode, :string
      add :discount_applied, :boolean
      add :discount_amount, :float
      add :discount_original_amount, :float
      add :ticket_description, :string

      add :has_luggage, :boolean
      add :luggage_total, :float
      add :info, :string

      timestamps()
    end

#    BusTerminalSystem.Repo.Migrations.CreateUsers
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
      add :role_id, :string
      add :apply_discount, :boolean
      add :discount_amount, :float
      add :compliance, :boolean
      add :employer_number, :string
      add :dob, :string
      add :sex, :string
      add :bank_message, :string, size: 2000
      add :bank_account_status, :string
      add :bank_account_balance, :float
      add :bank_srcBranch, :string
      add :bank_destBranch, :string


      timestamps()
    end

#    BusTerminalSystem.Repo.Migrations.CreateTables
    create_if_not_exists table(:probase_roles) do
      add :permissions, :string
      add :role, :string
    end

    create_if_not_exists table(:probase_user_role) do
      add :role, :integer
      add :user, :integer
      add :permission, :string
      timestamps()
    end


    create_if_not_exists table(:probase_permissions) do
      add :name, :string
      add :code, :string

      timestamps
    end

    create_if_not_exists table(:probase_audit_log) do
      add :operation, :string
      add :log, :string

      timestamps
    end

    create_if_not_exists table(:probase_tbl_tickets) do
      add :reference_number, :string
      add :serial_number, :string
      add :external_ref, :string
      add :route, :int
      add :date, :string
      add :bus_no, :string
      add :maker, :string
      add :class, :string
      add :activation_status, :string
      add :first_name, :string
      add :last_name, :string
      add :other_name, :string
      add :id_type, :string
      add :passenger_id, :string
      add :mobile_number, :string
      add :email_address, :string
      add :transaction_channel, :string
      add :travel_date, :string
      add :bus_schedule_id, :string
      add :route_information, :string
      add :amount, :float, default: 0.00, precision: 10, scale: 2
      add :payment_mode, :string
      add :has_luggage, :boolean, default: false
      add :luggage_total, :float, default: 0.00, precision: 13, scale: 2
      add :info, :string

      timestamps()
    end

    create_if_not_exists table(:probase_tbl_sms) do
      add :status, :string
      add :status_code, :integer
      add :recipient, :string
      add :message, :string, size: 2000
      add :request, :string, size: 2000
      add :response, :string, size: 2000
      add :sent, :boolean

      timestamps
    end

#    BusTerminalSystem.Repo.Migrations.CreateTerminus
    create_if_not_exists table(:probase_tbl_terminus) do
      add :terminus_name, :string
      add :terminus_location, :string
      add :estimated_buses, :integer
      add :city_town, :string

      timestamps()
    end

#    BusTerminalSystem.Repo.Migrations.CreateTableLuggageTarrifs
    create_if_not_exists table(:probase_tbl_luggage_tarrifs) do
      add :cost_per_kilo, :float

      timestamps()
    end

#    BusTerminalSystem.Repo.Migrations.CreateTableLuggageTable
    create_if_not_exists table(:probase_tbl_luggage) do
      add :description, :string
      add :ticket_id, :integer
      add :weight, :float
      add :cost, :float

      timestamps()
    end

#    BusTerminalSystem.Repo.Migrations.CreateBusTable
    create_if_not_exists table(:probase_tbl_bus) do
      add :license_plate, :string
      add :uid, :string
      add :engine_type, :string
      add :model, :string
      add :make, :string
      add :year, :string
      add :color, :string
      add :state_of_registration, :string
      add :vin_number, :string
      add :serial_number, :string
      add :hull_number, :string
      add :operator_id, :integer
#      add :operator_id, :string
      add :vehicle_class, :string
      add :company, :string
      add :company_info, :string
      add :fitness_license, :string
      add :vehicle_capacity, :string
      add :cosec, :string
      add :card, :string

      timestamps()
    end

#    BusTerminalSystem.Repo.Migrations.MarketingMigrations
    create_if_not_exists table(:probase_tbl_market) do
      add :market_name, :string
      add :location, :string
      add :market_uid, :string
      add :city_town, :string
      add :estimated_population, :string

      timestamps()
    end
    create_if_not_exists table(:probase_tbl_market_section) do
      add :section_name, :string
      add :section_lable, :string
      add :number_of_shops, :integer
      add :market_id, :integer

      timestamps()
    end

    create_if_not_exists table(:probase_tbl_market_section_shop) do
      add :shop_code, :string
      add :section_id, :integer
      add :maketeer_id, :integer
      add :shop_number, :integer
      add :shop_price, :integer

      timestamps()
    end

    create_if_not_exists table(:probase_tbl_bank_transactions) do

      add :srcAcc, :string, default: "NOT USED"
      add :srcBranch, :string, default: "NOT USED"
      add :srcCurrency, :string, default: "NOT USED"
      add :transferTyp, :string, default: "NOT USED"
      add :transferRef, :string, default: "NOT USED"
      add :referenceNo, :string, default: "NOT USED"
      add :destAcc, :string, default: "NOT USED"
      add :destBranch, :string, default: "NOT USED"
      add :payCurrency, :string, default: "NOT USED"
      add :amount, :string, default: "NOT USED"
      add :payDate, :string, default: "NOT USED"
      add :remarks, :string, default: "NOT USED"
      add :status, :string, default: "PENDING"
      add :request_reference, :string, default: "NOT USED"

      add :op_description, :string, default: "NO OPERATION DESCRIPTION SET"

      add :atd_number, :string, default: "NOT USED"
      add :atd_amount, :integer, default: 0
      add :service, :string, default: "NO DESCRIPTION"
      add :bank_id, :string, default: "NOT USED"

      add :nrc_no, :string, default: "NOT USED"
      add :account_no, :string, default: "NOT USED"
      add :deposit_date, :string, default: "NOT USED"
      add :bank_ref_number, :string, default: "NOT USED"

      add :name, :string, default: "NOT USED"
      add :senderMobileNo, :string, default: "NOT USED"
      add :reference, :string, default: "NOT USED"
      add :currency, :string, default: "NOT USED"
      add :account, :string, default: "NOT USED"
      add :receiverMobileNo, :string, default: "NOT USED"
      add :datePaymentReceived, :string, default: "NOT USED"
      add :paymentMode, :string, default: "NOT USED"
      add :senderEmail, :string, default: "NOT USED"

      add :userName, :string, default: "NOT USED"
      add :customerId, :string, default: "NOT USED"
      add :channelType, :string, default: "NOT USED"
      add :country, :string, default: "NOT USED"

      add :service_id, :string, default: "NOT USED"
      add :msisdn, :string, default: "NOT USED"
      add :account_number, :string, default: "NOT USED"
      add :payer_transaction_id, :string, default: "NOT USED"
      add :narration, :string, default: "NOT USED"
      add :extraData, :string, default: "NOT USED"
      add :currency_code, :string, default: "NOT USED"
      add :country_code, :string, default: "NOT USED"
      add :customer_names, :string, default: "NOT USED"
      add :date_payment_received, :string, default: "NOT USED"
      add :extra_data, :string, default: "NOT USED"
      add :payment_mode, :string, default: "NOT USED"

      add :beneName, :string
      add :senderName, :string
      add :beneEmail, :string
      add :beneMobileNo, :string
      add :destCurrency, :string
      add :ipAddress, :string
      add :sortCode, :string

      add :customerNo, :string
      add :customerPhoto, :string
      add :customerSignature, :string
      add :serviceId, :string

      add :otp, :string, default: "NOT USED"
      add :hostrefno, :string, default: "NOT USED"
      add :rrn, :string, default: "NOT USED"

      timestamps(type: :utc_datetime)
    end
  end


  def alter_tables do
    try do
      #      alter table(:probase_tbl_users) do
      #        add :auth_status, :integer, default: 0
      #        add :maker, :integer
      #        add :checker, :integer
      #        add :maker_date_time, :naive_datetime
      #        add :checker_date_time, :naive_datetime
      #        add :user_description, :string
      #        add :system_description, :string
      #      end
    rescue
      _ -> ""
    end


    #    alter table(:probase_tbl_travel_routes) do
    #      add :auth_status, :integer, default: 0
    #      add :maker, :integer
    #      add :checker, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end

    alter table(:probase_tbl_bus) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_luggage_tarrifs) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_market) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_market_section) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_market_section_shop) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_reports) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_terminus) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_travel_routes) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end

    alter table(:probase_tbl_users) do
      add :auth_status, :integer, default: 0
      add :maker, :integer
      add :checker, :integer
      add :maker_date_time, :naive_datetime
      add :checker_date_time, :naive_datetime
      add :user_description, :string
      add :system_description, :string
    end


    #    alter table(:probase_roles) do
    #      add :auth_status, :integer, default: 0
    #      add :maker_id, :integer
    #      add :checker_id, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end



    #    alter table(:probase_user_role) do
    #      add :auth_status, :integer, default: 0
    #      add :maker, :integer
    #      add :checker, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end


    #    alter table(:probase_tbl_terminus) do
    #      add :auth_status, :integer, default: 0
    #      add :maker, :integer
    #      add :checker, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end


    #    alter table(:probase_tbl_bus) do
    #      add :auth_status, :integer, default: 0
    #      add :maker, :integer
    #      add :checker, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end




    #    alter table(:probase_tbl_market) do
    #      add :auth_status, :boolean, default: false
    #      add :maker, :integer
    #      add :checker, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end



    #    alter table(:probase_tbl_market_section) do
    #      add :auth_status, :integer, default: 0
    #      add :maker, :integer
    #      add :checker, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end



    #    alter table(:probase_tbl_market_section_shop) do
    #      add :auth_status, :boolean, default: false
    #      add :maker, :integer
    #      add :checker, :integer
    #      add :maker_date_time, :naive_datetime
    #      add :checker_date_time, :naive_datetime
    #      add :user_description, :string
    #      add :system_description, :string
    #    end

  end

  def run_scripts do




    #    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, inserted_at, updated_at, auth_status)
    #                                VALUES ('manager',UPPER(SHA2('password', 512)),'probase','zambia','NOT AVAILABLE','ADMIN','admin@probasegroup.com','+260950773797','000000/00/0','ACTIVE',
    #                                        'ADMINISTRATOR',current_date,current_date, true)")
    #
    #    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, company,inserted_at, updated_at)
    #                                VALUES ('bop',UPPER(SHA2('password', 512)),'operator','zambia','NOT AVAILABLE','BOP','operator@btmsnapsa.com','+260950773797','000000/00/0','ACTIVE',
    #                                        'BUS OPERATOR','NAPSA BUS SERVICES',current_date,current_date)")
    #
    #    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, inserted_at, updated_at)
    #                                VALUES ('mop',UPPER(SHA2('password', 512)),'marketeer','zambia','NOT AVAILABLE','MOP','marketeer@btmsnapsa.com','+260950773797','000000/00/0','ACTIVE',
    #                                        'MARKETER',current_date,current_date)")
    #
    #    execute("INSERT INTO probase_tbl_users (username, password, first_name, last_name, ssn, role, email, mobile,nrc, account_status, operator_role, inserted_at, updated_at)
    #                                VALUES ('teller',UPPER(SHA2('password', 512)),'teller','zambia','NOT AVAILABLE','TOP','teller@btmsnapsa.com','+260950773797','000000/00/0','ACTIVE',
    #                                        'TELLER',current_date,current_date)")




    execute "create table if not exists probase_acc_gl_account
            (
                id bigint auto_increment
                    primary key,
                name varchar(200) not null,
                parent_id bigint null,
                hierarchy varchar(50) null,
                gl_code varchar(45) not null,
                disabled tinyint(1) default 0 not null,
                manual_journal_entries_allowed tinyint(1) default 1 not null,
                account_usage tinyint(1) default 2 not null,
                classification varchar(6) not null comment 'A- Asset,L- Liability,I- Income,E- Expense',
                tag_id int null,
                description varchar(500) null,
                maker_id varchar(20) null,
                checker_id varchar(20) null,
                constraint acc_gl_code
                    unique (gl_code),
                constraint FK_ACC_0000000001
                    foreign key (parent_id) references probase_acc_gl_account (id)
            )
            comment 'Table stores chart of accounts and will be basis of balance sheet reports';"

    execute "create definer = probase@`%` trigger insert_ac_gl_bal
            after INSERT on probase_acc_gl_account
            for each row
            BEGIN
                INSERT INTO probase_tbl_acgl_bal (account, entry_date, opening_bal, closing_bal, dr_mov, cr_mov)
                values (NEW.gl_code,now(),0.00,0.00,0.00,0.00);
            END;"

    execute "create table if not exists probase_process_dates
            (
                id int not null,
                site_code varchar(3) null comment 'Site code of the station or market ',
                prev_day date null comment 'Previous day eod process',
                today date null comment 'todays date after eod has been run',
                next_day date null comment 'next date after eod has been run. this is affected when there are holidays',
                constraint probase_process_dates_id_uindex
                    unique (id)
            );"

    execute "alter table probase_process_dates
            add primary key (id);"

    execute "create table if not exists probase_tbl_acgl_bal
            (
                account varchar(20) not null,
                entry_date date null,
                opening_bal decimal(19,2) null comment 'Account opening balance',
                closing_bal decimal(19,2) null comment 'Account closing balance',
                dr_mov decimal(19,2) null comment 'Account debit movement from daily transactions',
                cr_mov decimal(19,2) null comment 'Account credit movement from daily transactions',
                gen_balance decimal(13,2) as ((`opening_bal` + (`cr_mov` - `dr_mov`))),
                constraint probase_tbl_acgl_bal
                    unique (account, entry_date),
                constraint probase_tbl_acgl_bal_probase_acc_gl_account_gl_code_fk
                    foreign key (account) references probase_acc_gl_account (gl_code)
            )
            comment 'Stores daily gl and account balances';"

    execute "create table if not exists probase_tbl_bus
            (
                id int auto_increment
                    primary key,
                license_plate varchar(255) null,
                uid varchar(255) null,
                engine_type varchar(255) null,
                model varchar(255) null,
                make varchar(255) null,
                year varchar(255) null,
                color varchar(255) null,
                state_of_registration varchar(255) null,
                vin_number varchar(255) null,
                serial_number varchar(255) null,
                hull_number varchar(255) null,
                operator_id varchar(255) null,
                vehicle_class varchar(255) null,
                company varchar(255) null,
                company_info varchar(255) null,
                fitness_license varchar(255) null,
                vehicle_capacity varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                constraint plate_number
                    unique (license_plate)
            );"

    execute "create table if not exists probase_tbl_luggage_tarrifs
            (
                id bigint unsigned auto_increment
                    primary key,
                cost_per_kilo double null,
                inserted_at datetime not null,
                updated_at datetime not null
            );"

    execute "create table if not exists probase_tbl_terminus
            (
                id bigint unsigned auto_increment
                    primary key,
                terminus_name varchar(255) null,
                terminus_location varchar(255) null,
                estimated_buses int null,
                city_town varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null
            );"

    execute "create table if not exists probase_tbl_tickets
            (
                id bigint auto_increment
                    primary key,
                reference_number varchar(255) null,
                serial_number varchar(255) null,
                external_ref varchar(255) null,
                route int null,
                date varchar(255) null,
                bus_no varchar(255) null,
                class varchar(255) null,
                activation_status varchar(255) null,
                first_name varchar(255) null,
                last_name varchar(255) null,
                other_name varchar(255) null,
                id_type varchar(255) null,
                passenger_id varchar(255) null,
                mobile_number varchar(255) null,
                email_address varchar(255) null,
                transaction_channel varchar(255) null,
                travel_date varchar(255) null,
                bus_schedule_id varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                constraint probase_tbl_tickets_reference_number_index
                    unique (reference_number)
            );"

    execute "create table if not exists probase_tbl_luggage
            (
                id bigint auto_increment
                    primary key,
                description varchar(255) null,
                ticket_id bigint null,
                weight double null,
                cost double null,
                inserted_at datetime not null,
                updated_at datetime not null,
                constraint probase_tbl_luggage_probase_tbl_tickets_id_fk
                    foreign key (ticket_id) references probase_tbl_tickets (id)
            );"

    execute "create table if not exists probase_tbl_travel_routes
            (
                id int auto_increment
                    primary key,
                route_name varchar(255) null,
                start_route varchar(255) null,
                end_route varchar(255) null,
                route_code varchar(255) null,
                ticket_id int null,
                source_state varchar(255) null,
                route_uuid varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                maker_id varchar(20) null,
                checker_id varchar(20) null
            );"

    execute "create table if not exists probase_tbl_users
            (
                id bigint unsigned auto_increment
                    primary key,
                username varchar(255) null,
                password varchar(255) null,
                first_name varchar(255) null,
                last_name varchar(255) null,
                ssn varchar(255) null,
                role varchar(255) null,
                email varchar(255) null,
                mobile varchar(255) null,
                tel varchar(255) null,
                uuid varchar(255) null,
                nrc varchar(255) null,
                account_status varchar(255) null,
                operator_role varchar(255) null,
                pin varchar(255) null,
                tmp_pin varchar(255) null,
                company varchar(255) null,
                account_type char(5) null,
                account_number varchar(20) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                constraint probase_tbl_users_email_uindex
                    unique (email),
                constraint probase_tbl_users_nrc_uindex
                    unique (nrc),
                constraint probase_tbl_users_username_uindex
                    unique (username)
            );"

    execute "create definer = probase@`%` trigger ins_acc_gl_bal_cust
                after INSERT on probase_tbl_users
                for each row
              BEGIN
                  if new.role ='BOP' or new.role ='MOP' then
                      INSERT INTO probase_tbl_acgl_bal (account, entry_date, opening_bal, closing_bal, dr_mov, cr_mov)
                      values (NEW.account_number,now(),0.00,0.00,0.00,0.00);
                  end if ;
              END;"

    execute "create table if not exists probase_trans_code
            (
                trn_code varchar(20) not null,defmodule BusTerminalSystem.Repo.Migrations.Reports
                trn_desc varchar(50) null,
                auth_status char default 'U' null,
                maker_id varchar(50) null,
                checker_id varchar(50) null,
                constraint probase_trans_code_trn_code_uindex
                    unique (trn_code)
            );"

    execute "alter table probase_trans_code
            add primary key (trn_code);"

    execute "create table if not exists probase_tbl_transactions
            (
                ac_sr_no int auto_increment
                    primary key,
                trn_dt date null,
                val_dt date null,
                trans_ref_no varchar(16) null,
                ac_no varchar(20) null,
                trn_code varchar(20) null,
                drcr_ind char null,
                lcy_amount decimal(19,2) null,
                fin_cycle varchar(9) null,
                auth_stat char null,
                transaction_channel varchar(20) null,
                maker_id varchar(20) null,
                checker_id varchar(20) null,
                cust_gl char null comment 'is transaction a customer transaction or gl ?',
                related_customer int null comment 'Who is this transaction related to ?',
                constraint probase_daily_transactions_probase_trans_code_trn_code_fk
                    foreign key (trn_code) references probase_trans_code (trn_code)
                        on update cascade
            );"


    execute "create table if not exists probase_acc_gl_account
            (
                id bigint auto_increment
                    primary key,
                name varchar(200) not null,
                parent_id bigint null,
                hierarchy varchar(50) null,
                gl_code varchar(45) not null,
                disabled tinyint(1) default 0 not null,
                manual_journal_entries_allowed tinyint(1) default 1 not null,
                account_usage tinyint(1) default 2 not null,
                classification varchar(6) not null comment 'A- Asset,L- Liability,I- Income,E- Expense',
                tag_id int null,
                description varchar(500) null,
                maker_id varchar(20) null,
                checker_id varchar(20) null,
                constraint acc_gl_code
                    unique (gl_code),
                constraint FK_ACC_0000000001
                    foreign key (parent_id) references probase_acc_gl_account (id)
            )
            comment 'Table stores chart of accounts and will be basis of balance sheet reports';"

    execute "create table if not exists probase_acc_process_dates
            (
                id int not null,
                site_code varchar(3) null comment 'Site code of the station or market ',
                prev_day date null comment 'Previous day eod process',
                today date null comment 'todays date after eod has been run',
                next_day date null comment 'next date after eod has been run. this is affected when there are holidays',
                constraint probase_process_dates_id_uindex
                    unique (id)
            );"


    execute "create table if not exists probase_tbl_acc_acgl_bal
            (
                account varchar(20) not null,
                entry_date datetime null,
                opening_bal decimal(19,2) null comment 'Account opening balance',
                closing_bal decimal(19,2) null comment 'Account closing balance',
                dr_mov decimal(19,2) null comment 'Account debit movement from daily transactions',
                cr_mov decimal(19,2) null comment 'Account credit movement from daily transactions',
                gen_balance decimal(13,2) as ((`opening_bal` + (`cr_mov` - `dr_mov`))),
                constraint probase_tbl_acgl_bal
                    unique (account, entry_date),
                constraint probase_tbl_acgl_bal_probase_acc_gl_account_gl_code_fk
                    foreign key (account) references probase_acc_gl_account (gl_code)
            )
            comment 'Stores daily gl and account balances';"

    execute "create table if not exists probase_tbl_bus
            (
                id int auto_increment
                    primary key,
                license_plate varchar(255) null,
                uid varchar(255) null,
                engine_type varchar(255) null,
                model varchar(255) null,
                make varchar(255) null,
                year varchar(255) null,
                color varchar(255) null,
                state_of_registration varchar(255) null,
                vin_number varchar(255) null,
                serial_number varchar(255) null,
                hull_number varchar(255) null,
                operator_id varchar(255) null,
                vehicle_class varchar(255) null,
                company varchar(255) null,
                company_info varchar(255) null,
                fitness_license varchar(255) null,
                vehicle_capacity varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                constraint plate_number
                    unique (license_plate)
            );"

    execute "create table if not exists probase_tbl_luggage_tarrifs
            (
                id bigint unsigned auto_increment
                    primary key,
                cost_per_kilo double null,
                inserted_at datetime not null,
                updated_at datetime not null
            );"

    execute "create table if not exists probase_tbl_terminus
            (
                id bigint unsigned auto_increment
                    primary key,
                terminus_name varchar(255) null,
                terminus_location varchar(255) null,
                estimated_buses int null,
                city_town varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null
            );"


    execute "create table if not exists probase_trans_code
            (
                trn_code varchar(20) not null,
                trn_desc varchar(50) null,
                auth_status char default 'U' null,
                maker_id varchar(50) null,
                checker_id varchar(50) null,
                constraint probase_trans_code_trn_code_uindex
                    unique (trn_code)
            );"

    execute "create table if not exists probase_tbl_transactions
            (
                ac_sr_no int auto_increment
                    primary key,
                trn_dt datetime null,
                val_dt datetime null,
                trans_ref_no varchar(255) null,
                ac_no varchar(20) null,
                trn_code varchar(20) null,
                drcr_ind char null,
                lcy_amount decimal(19,2) null,
                fin_cycle varchar(9) null,
                auth_stat char null,
                transaction_channel varchar(20) null,
                maker_id varchar(20) null,
                checker_id varchar(20) null,
                cust_gl char null comment 'is transaction a customer transaction or gl ?',
                related_customer varchar(50) null comment 'Who is this transaction related to ?',
                constraint probase_daily_transactions_probase_trans_code_trn_code_fk
                    foreign key (trn_code) references probase_trans_code (trn_code)
                        on update cascade
            );"

    execute "create table if not exists probase_tbl_luggage
            (
                id bigint auto_increment
                    primary key,
                description varchar(255) null,
                ticket_id bigint null,
                weight double null,
                cost double null,
                inserted_at datetime not null,
                updated_at datetime not null

            );"

  end

  def unused_migrations() do
    execute "create table if not exists probase_tbl_tickets
            (
                id bigint auto_increment
                    primary key,
                reference_number varchar(255) null,
                serial_number varchar(255) null,
                external_ref varchar(255) null,
                route int null,
                date varchar(255) null,
                bus_no varchar(255) null,
                class varchar(255) null,
                activation_status varchar(255) null,
                first_name varchar(255) null,
                last_name varchar(255) null,
                other_name varchar(255) null,
                id_type varchar(255) null,
                passenger_id varchar(255) null,
                mobile_number varchar(255) null,
                email_address varchar(255) null,
                transaction_channel varchar(255) null,
                travel_date varchar(255) null,
                bus_schedule_id varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                constraint probase_tbl_tickets_reference_number_index
                    unique (reference_number)
            );"


    execute "create table if not exists probase_tbl_travel_routes
            (
                id int auto_increment
                    primary key,
                route_name varchar(255) null,
                start_route varchar(255) null,
                end_route varchar(255) null,
                route_code varchar(255) null,
                ticket_id int null,
                source_state varchar(255) null,
                route_uuid varchar(255) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                maker_id varchar(20) null,
                checker_id varchar(20) null
            );"

    execute "create table if not exists probase_tbl_users
            (
                id bigint unsigned auto_increment
                    primary key,
                username varchar(255) null,
                password varchar(255) null,
                first_name varchar(255) null,
                last_name varchar(255) null,
                ssn varchar(255) null,
                role varchar(255) null,
                email varchar(255) null,
                mobile varchar(255) null,
                tel varchar(255) null,
                uuid varchar(255) null,
                nrc varchar(255) null,
                account_status varchar(255) null,
                operator_role varchar(255) null,
                pin varchar(255) null,
                tmp_pin varchar(255) null,
                company varchar(255) null,
                account_type char(5) null,
                account_number varchar(20) null,
                inserted_at datetime not null,
                updated_at datetime not null,
                constraint probase_tbl_users_email_uindex
                    unique (email),
                constraint probase_tbl_users_nrc_uindex
                    unique (nrc),
                constraint probase_tbl_users_username_uindex
                    unique (username)
            );"
  end

  def drop do
#    drop_if_exists table(:probase_tbl_route_mapping)
#    drop_if_exists table(:probaserun_scripts_tbl_reports)
#    drop_if_exists table(:probase_tbl_travel_routes)
#    drop_if_exists table(:probase_tbl_tickets)
#    drop_if_exists table(:probase_tbl_users)

#    BusTerminalSystem.Repo.Migrations.CreateTables
#    drop_if_exists table(:probase_tbl_transactions)
#    drop_if_exists table(:probase_acc_trans_code)
#    drop_if_exists table(:probase_tbl_users)
#    drop_if_exists table(:probase_tbl_route_mapping)
#    drop_if_exists table(:probase_tbl_travel_routes)
#    drop_if_exists table(:probase_tbl_luggage)
#    drop_if_exists table(:probase_tbl_tickets)
#    drop_if_exists table(:probase_tbl_terminus)
#    drop_if_exists table(:probase_tbl_luggage_tarrifs)
#    drop_if_exists table(:probase_tbl_bus)
#    drop_if_exists table(:probase_tbl_acc_acgl_bal)
#    drop_if_exists table(:probase_acc_process_dates)
#    drop_if_exists table(:probase_acc_gl_account)
#    drop_if_exists table(:probase_tbl_sms)
#    drop_if_exists table(:probase_tbl_terminus)
#    drop_if_exists table(:probase_tbl_luggage_tarrifs)
#    drop_if_exists table(:probase_tbl_luggage)
#    drop_if_exists table(:probase_tbl_bus)
#    drop_if_exists table(:probase_tbl_market)
#    drop_if_exists table(:probase_tbl_market_section)
#    drop_if_exists table(:probase_tbl_market_section_shop)
  end

end

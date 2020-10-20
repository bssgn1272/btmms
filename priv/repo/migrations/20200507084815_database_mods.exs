defmodule BusTerminalSystem.Repo.Migrations.DatabaseMods do
  use Ecto.Migration

  def change do

    #create_new_tables()
#    add_columns()

  end

  def add_columns do
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
  end

  def create_new_tables do

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
                trn_code varchar(20) not null,
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
  end

end

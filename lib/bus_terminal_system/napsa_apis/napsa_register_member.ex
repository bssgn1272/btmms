defmodule BusTerminalSystem.Napsa.RegisterMember do
  @moduledoc false

  import BusTerminalSystem.Napsa.Connector

  def connect(conn \\ %{}, params \\ %{}) do
    params
    |> contribution_xml_request
    |> submit_request(
         "http://napsa-enapsauatsvr:8738/eNAPSAExternalAPI/2018/04/NPSService",
         "http://enapsa.napsa.co.zm/eNAPSAServicesLibrary/2016/11/IeNAPSAExternalAPI/RegisterEmployee"
       )
  end

  @validation_struct %{
    :member_provider_id => :string,

    :member_first_name => :string,
    :member_last_name => :string,
    :member_middel_name => :string,
    :member_national_id => :string,
    :member_mobile => :string,
    :member_email => :string,
    :member_title => :string,
    :member_dob => :string,
    :member_gender => :string,
    :member_marital_status => :string,
    :member_date_joined => :string,
    :member_fax => :string,

    :member_address_line1 => :string,
    :member_address_line2 => :string,
    :member_address_line3 => :string,
    :member_address_line4 => :string,

    :member_postal_address_line1 => :string,
    :member_postal_address_line2 => :string,
    :member_postal_address_line3 => :string,
    :member_postal_address_line4 => :string,

    :member_father_surname => :string,
    :member_father_first_name => :string,
    :member_mother_surname => :string,
    :member_mother_first_name => :string,

    :member_province_code => :string,
    :member_attachment_url => :string,
    :member_employer_account_number => :string,
    :member_occupation_code => :string,
    :member_center_code => :string,

    :member_bene_first_name => :string,
    :member_bene_last_name => :string,
    :member_bene_middel_name => :string,
    :member_bene_national_id => :string,
    :member_bene_dob => :string,
    :member_bene_gender => :string,
    :member_bene_relationship_code => :string,
  }
  def contribution_xml_request(args \\ %{}) do

    """
      <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns="http://enapsa.napsa.co.zm/eNAPSAServicesLibrary/2016/11" xmlns:enap="http://schemas.datacontract.org/2004/07/eNAPSAServicesLibrary.MessageContracts">
    <soapenv:Header/>
    <soapenv:Body>
      <ns:EmployeeRegistrationRequest>
         <!--Optional:-->
          <ns:ProviderID>#{args.member_provider_id}</ns:ProviderID>
         <!--Optional:-->
         <ns:MemberDetails>
            <!--Optional:-->
            <enap:first_name>#{args.member_first_name}</enap:first_name>
            <!--Optional:-->
            <enap:last_name>#{args.member_last_name}</enap:last_name>
            <!--Optional:-->
            <enap:middel_name>#{args.member_middel_name}</enap:middel_name>
            <!--Optional:-->
            <enap:national_id>#{args.member_national_id}</enap:national_id>
            <!--Optional:-->
            <enap:mobile>#{args.member_mobile}</enap:mobile>
            <!--Optional:-->
            <enap:email>#{args.email}</enap:email>
            <!--Optional:-->
            <enap:title>#{args.member_title}</enap:title>
            <!--Optional:-->
            <enap:DOB>#{args.member_dob}</enap:DOB>
            <!--Optional:-->
            <enap:gender>#{args.member_gender}</enap:gender>
            <!--Optional:-->
            <enap:marital_status>#{args.member_marital_status}</enap:marital_status>
            <!--Optional:-->
            <enap:center_code>#{args.member_center_code}</enap:center_code>
            <!--Optional:-->
            <enap:date_joined>#{args.member_date_joined}</enap:date_joined>
            <!--Optional:-->
            <enap:address_line1>#{args.member_address_line1}</enap:address_line1>
            <!--Optional:-->
            <enap:address_line2>#{args.member_address_line2}</enap:address_line2>
            <!--Optional:-->
            <enap:address_line3>#{args.member_address_line3}</enap:address_line3>
            <!--Optional:-->
            <enap:address_line4>#{args.member_address_line4}</enap:address_line4>
            <!--Optional:-->
            <enap:fax>#{args.member_fax}</enap:fax>
             <enap:postal_address_line1>#{args.member_postal_address_line1}</enap:postal_address_line1>
            <!--Optional:-->
            <enap:postal_address_line2>#{args.member_postal_address_line2}</enap:postal_address_line2>
            <!--Optional:-->
            <enap:postal_address_line3>#{args.member_postal_address_line3}</enap:postal_address_line3>
            <!--Optional:-->
            <enap:postal_address_line4>#{args.member_postal_address_line4}</enap:postal_address_line4>
            <!--Optional:-->
            <enap:father_surname>#{args.member_father_surname}</enap:father_surname>
            <!--Optional:-->
            <enap:father_first_name>#{args.member_father_first_name}</enap:father_first_name>
            <!--Optional:-->
            <enap:mother_surname>#{args.member_mother_surname}</enap:mother_surname>
            <!--Optional:-->
            <enap:mother_first_name>#{args.member_mother_first_name}</enap:mother_first_name>
            <!--Optional:-->
            <enap:occupation_code>#{args.member_occupation_code}</enap:occupation_code>
            <!--Optional:-->
            <enap:province_code>#{args.member_province_code}</enap:province_code>
            <!--Optional:-->
            <enap:attachment_url>#{args.member_attachment_url}</enap:attachment_url>
            <!--Optional:-->
            <enap:employer_account_number>#{args.member_employer_account_number}</enap:employer_account_number>
         </ns:MemberDetails>
         <!--Optional:-->
         <ns:BeneficiaryEntries>
            <!--Zero or more repetitions:-->
            <enap:Beneficiaries>
               <!--Optional:-->
               <enap:first_name>#{args.member_bene_first_name}</enap:first_name>
               <!--Optional:-->
               <enap:last_name>#{args.member_bene_last_name}</enap:last_name>
               <!--Optional:-->
               <enap:middel_name>#{args.member_bene_middel_name}</enap:middel_name>
               <!--Optional:-->
               <enap:national_id>#{args.member_bene_national_id}</enap:national_id>
               <!--Optional:-->
               <enap:DOB>#{args.member_bene_dob}</enap:DOB>
               <!--Optional:-->
               <enap:gender>#{args.member_bene_gender}</enap:gender>
               <!--Optional:-->
               <enap:relationship_code>#{args.member_bene_relationship_code}</enap:relationship_code>
            </enap:Beneficiaries>
         </ns:BeneficiaryEntries>
      </ns:EmployeeRegistrationRequest>
    </soapenv:Body>
    </soapenv:Envelope>
    """
  end

end
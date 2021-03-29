defmodule BusTerminalSystem.Napsa.NapsaContribution do

  import BusTerminalSystem.Napsa.Connector

  def connect(conn \\ %{}, params \\ %{}) do
    params
    |> contribution_xml_request
    |> submit_request(
         "http://napsa-enapsauatsvr:8738/eNAPSAExternalAPI/2018/04/NPSService",
        "http://enapsa.napsa.co.zm/eNAPSAServicesLibrary/2016/11/IeNAPSAExternalAPI/ReturnUpload"
       )
  end

  def contribution_xml_request(args \\ %{}) do
    """
      <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns="http://enapsa.napsa.co.zm/eNAPSAServicesLibrary/2016/11">
    <soapenv:Header/>
    <soapenv:Body>
      <ns:ReturnUploadRequest>
         <!--Optional:-->
         <ns:ReturnHeader>
            <!--Optional:-->
            <ns:ProviderID>#{args["provider_id"]}</ns:ProviderID>
            <!--Optional:-->
            <ns:EmployerAccountNumber>#{args["employer_account_number"]}</ns:EmployerAccountNumber>
            <!--Optional:-->
            <ns:Year>#{args["year"]}</ns:Year>
            <!--Optional:-->
            <ns:Month>#{args["month"]}</ns:Month>
            <!--Optional:-->
            <ns:PrincipalAmount>#{args["principal_amount"]}</ns:PrincipalAmount>
            <!--Optional:-->
            <ns:PenaltyAmount>#{args["penalty_amount"]}</ns:PenaltyAmount>
            <!--Optional:-->
            <ns:TotalAmount>#{args["total_amount"]}</ns:TotalAmount>
            <!--Optional:-->
            <ns:NumberOfEmployees>#{args["number_of_employees"]}</ns:NumberOfEmployees>
         </ns:ReturnHeader>
         <!--Optional:-->
         <ns:contributions>
            <!--Zero or more repetitions:-->
            <ns:Contributions>
               <!--Optional:-->
               <ns:SSN>#{args["ssn"]}</ns:SSN>
               <!--Optional:-->
               <ns:NationalID>#{args["national_id"]}</ns:NationalID>
               <!--Optional:-->
               <ns:Surname>#{args["surname"]}</ns:Surname>
               <!--Optional:-->
               <ns:FirstName>#{args["firstname"]}</ns:FirstName>
               <!--Optional:-->
               <ns:OtherName>#{args["othername"]}</ns:OtherName>
               <!--Optional:-->
               <ns:DOB>#{args["date_of_birth"]}</ns:DOB>
               <!--Optional:-->
               <ns:GrossWage>#{args["gross_wage"]}</ns:GrossWage>
               <!--Optional:-->
               <ns:EmployeeShare>#{args["employee_share"]}</ns:EmployeeShare>
               <!--Optional:-->
               <ns:EmployerShare>#{args["employer_share"]}</ns:EmployerShare>
               <!--Optional:-->
               <ns:SiebelID>#{args["siebel_id"]}</ns:SiebelID>
            </ns:Contributions>
         </ns:contributions>
      </ns:ReturnUploadRequest>
    </soapenv:Body>
    </soapenv:Envelope>
    """
  end

end
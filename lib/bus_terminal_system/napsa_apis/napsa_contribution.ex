defmodule BusTerminalSystem.Napsa.NapsaContribution do

  def connect(conn \\ %{}, params \\ %{}) do
#    contribution_xml_request |> XmlToMap.naive_map
    wsdl_path = "http://10.10.1.114:8738/eNapsaExternalAPI/2018/04/?singleWsdl"
    {:ok, wsdl} = Soap.init_model(wsdl_path, :url)
#    {"wsdl", Soap.operations(wsdl)}
    parameters = %{}
    {:ok, response} = Soap.call(wsdl_path, "ReturnUpload", parameters)

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
            <ns:ProviderID>?</ns:ProviderID>
            <!--Optional:-->
            <ns:EmployerAccountNumber>?</ns:EmployerAccountNumber>
            <!--Optional:-->
            <ns:Year>?</ns:Year>
            <!--Optional:-->
            <ns:Month>?</ns:Month>
            <!--Optional:-->
            <ns:PrincipalAmount>?</ns:PrincipalAmount>
            <!--Optional:-->
            <ns:PenaltyAmount>?</ns:PenaltyAmount>
            <!--Optional:-->
            <ns:TotalAmount>?</ns:TotalAmount>
            <!--Optional:-->
            <ns:NumberOfEmployees>?</ns:NumberOfEmployees>
         </ns:ReturnHeader>
         <!--Optional:-->
         <ns:contributions>
            <!--Zero or more repetitions:-->
            <ns:Contributions>
               <!--Optional:-->
               <ns:SSN>?</ns:SSN>
               <!--Optional:-->
               <ns:NationalID>?</ns:NationalID>
               <!--Optional:-->
               <ns:Surname>?</ns:Surname>
               <!--Optional:-->
               <ns:FirstName>?</ns:FirstName>
               <!--Optional:-->
               <ns:OtherName>?</ns:OtherName>
               <!--Optional:-->
               <ns:DOB>?</ns:DOB>
               <!--Optional:-->
               <ns:GrossWage>?</ns:GrossWage>
               <!--Optional:-->
               <ns:EmployeeShare>?</ns:EmployeeShare>
               <!--Optional:-->
               <ns:EmployerShare>?</ns:EmployerShare>
               <!--Optional:-->
               <ns:SiebelID>?</ns:SiebelID>
            </ns:Contributions>
         </ns:contributions>
      </ns:ReturnUploadRequest>
    </soapenv:Body>
    </soapenv:Envelope>
    """
  end

  defp submit_request(request) do
    headers = [
      {"Content-Type", "text/xml"},
    ]

    endpoint = "http://enapsa.napsa.co.zm/eNAPSAServicesLibrary/2016/11/IeNAPSAExternalAPI/ReturnUpload"

    case HTTPoison.post(endpoint, request, headers) do
      {status, %HTTPoison.Response{body: body, status_code: status_code}} ->
        body
      {_status, %HTTPoison.Error{reason: reason}} ->
        %{"message" => reason}
    end
  end

end
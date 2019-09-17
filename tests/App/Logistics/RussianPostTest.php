<?php

namespace Tests\App\Logistics;

use App\Logistics\RussianPost;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Class RussianPostTest.
 *
 * @package Tests\App\Logistics
 */
class RussianPostTest extends TestCase
{
    /**
     * Tests getOperationHistory method.
     *
     * @return void
     */
    public function testGetOperationHistory(): void
    {
        $definitions = <<<NOWDOC
<?xml version="1.0" encoding="UTF-8"?> <definitions xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:wsp="http://www.w3.org/ns/ws-policy" xmlns:wsp1_2="http://schemas.xmlsoap.org/ws/2004/09/policy" xmlns:wsam="http://www.w3.org/2007/05/addressing/metadata" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://russianpost.org/operationhistory" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://schemas.xmlsoap.org/wsdl/" targetNamespace="http://russianpost.org/operationhistory" name="OperationHistory12"> <types> <xsd:schema> <xsd:import namespace="http://www.russianpost.org/custom-duty-info/data" schemaLocation="https://tracking.russianpost.ru/rtm34?xsd=1"/> </xsd:schema> <xsd:schema> <xsd:import namespace="http://www.russianpost.org/RTM/DataExchangeESPP/Data" schemaLocation="https://tracking.russianpost.ru/rtm34?xsd=2"/> </xsd:schema> <xsd:schema> <xsd:import namespace="http://schemas.xmlsoap.org/soap/envelope/" schemaLocation="https://tracking.russianpost.ru/rtm34?xsd=3"/> </xsd:schema> <xsd:schema> <xsd:import namespace="http://russianpost.org/sms-info/data" schemaLocation="https://tracking.russianpost.ru/rtm34?xsd=4"/> </xsd:schema> <xsd:schema> <xsd:import namespace="http://russianpost.org/operationhistory/data" schemaLocation="https://tracking.russianpost.ru/rtm34?xsd=5"/> </xsd:schema> <xsd:schema> <xsd:import namespace="http://russianpost.org/operationhistory" schemaLocation="https://tracking.russianpost.ru/rtm34?xsd=6"/> </xsd:schema> </types> <message name="getOperationHistory"> <part name="parameters" element="tns:getOperationHistory"/> </message> <message name="getOperationHistoryResponse"> <part name="parameters" element="tns:getOperationHistoryResponse"/> </message> <message name="OperationHistoryFault"> <part xmlns:ns1="http://russianpost.org/operationhistory/data" name="fault" element="ns1:OperationHistoryFaultReason"/> </message> <message name="AuthorizationFault"> <part xmlns:ns2="http://russianpost.org/operationhistory/data" name="fault" element="ns2:AuthorizationFaultReason"/> </message> <message name="getLanguages"> <part name="parameters" element="tns:getLanguages"/> </message> <message name="getLanguagesResponse"> <part name="parameters" element="tns:getLanguagesResponse"/> </message> <message name="LanguageFault"> <part xmlns:ns3="http://russianpost.org/operationhistory/data" name="fault" element="ns3:LanguageFaultReason"/> </message> <message name="getCustomDutyEventsForMail"> <part name="parameters" element="tns:getCustomDutyEventsForMail"/> </message> <message name="getCustomDutyEventsForMailResponse"> <part name="parameters" element="tns:getCustomDutyEventsForMailResponse"/> </message> <message name="CustomDutyEventsForMailFault"> <part xmlns:ns4="http://www.russianpost.org/custom-duty-info/data" name="fault" element="ns4:CustomDutyEventsForMailFault"/> </message> <message name="getSmsHistory"> <part name="parameters" element="tns:getSmsHistory"/> </message> <message name="getSmsHistoryResponse"> <part name="parameters" element="tns:getSmsHistoryResponse"/> </message> <message name="SmsHistoryFault"> <part xmlns:ns5="http://russianpost.org/sms-info/data" name="fault" element="ns5:SmsHistoryFaultReason"/> </message> <message name="PostalOrderEventsForMail"> <part name="parameters" element="tns:PostalOrderEventsForMail"/> </message> <message name="PostalOrderEventsForMailResponse"> <part name="parameters" element="tns:PostalOrderEventsForMailResponse"/> </message> <message name="PostalOrderEventsForMailFault"> <part xmlns:ns6="http://www.russianpost.org/RTM/DataExchangeESPP/Data" name="fault" element="ns6:PostalOrderEventsForMailFault"/> </message> <portType name="OperationHistory12"> <operation name="getOperationHistory"> <input wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getOperationHistoryRequest" message="tns:getOperationHistory"/> <output wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getOperationHistoryResponse" message="tns:getOperationHistoryResponse"/> <fault message="tns:OperationHistoryFault" name="OperationHistoryFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getOperationHistory/Fault/OperationHistoryFault"/> <fault message="tns:AuthorizationFault" name="AuthorizationFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getOperationHistory/Fault/AuthorizationFault"/> </operation> <operation name="getLanguages"> <input wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getLanguagesRequest" message="tns:getLanguages"/> <output wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getLanguagesResponse" message="tns:getLanguagesResponse"/> <fault message="tns:OperationHistoryFault" name="OperationHistoryFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getLanguages/Fault/OperationHistoryFault"/> <fault message="tns:AuthorizationFault" name="AuthorizationFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getLanguages/Fault/AuthorizationFault"/> <fault message="tns:LanguageFault" name="LanguageFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getLanguages/Fault/LanguageFault"/> </operation> <operation name="getCustomDutyEventsForMail"> <input wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getCustomDutyEventsForMailRequest" message="tns:getCustomDutyEventsForMail"/> <output wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getCustomDutyEventsForMailResponse" message="tns:getCustomDutyEventsForMailResponse"/> <fault message="tns:CustomDutyEventsForMailFault" name="CustomDutyEventsForMailFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getCustomDutyEventsForMail/Fault/CustomDutyEventsForMailFault"/> <fault message="tns:AuthorizationFault" name="AuthorizationFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getCustomDutyEventsForMail/Fault/AuthorizationFault"/> <fault message="tns:LanguageFault" name="LanguageFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getCustomDutyEventsForMail/Fault/LanguageFault"/> </operation> <operation name="getSmsHistory"> <input wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getSmsHistoryRequest" message="tns:getSmsHistory"/> <output wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getSmsHistoryResponse" message="tns:getSmsHistoryResponse"/> <fault message="tns:SmsHistoryFault" name="SmsHistoryFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getSmsHistory/Fault/SmsHistoryFault"/> <fault message="tns:AuthorizationFault" name="AuthorizationFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getSmsHistory/Fault/AuthorizationFault"/> <fault message="tns:LanguageFault" name="LanguageFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/getSmsHistory/Fault/LanguageFault"/> </operation> <operation name="PostalOrderEventsForMail"> <input wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/PostalOrderEventsForMailRequest" message="tns:PostalOrderEventsForMail"/> <output wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/PostalOrderEventsForMailResponse" message="tns:PostalOrderEventsForMailResponse"/> <fault message="tns:PostalOrderEventsForMailFault" name="PostalOrderEventsForMailFault" wsam:Action="http://russianpost.org/operationhistory/OperationHistory12/PostalOrderEventsForMail/Fault/PostalOrderEventsForMailFault"/> </operation> </portType> <binding name="OperationHistory12PortBinding" type="tns:OperationHistory12"> <soap12:binding transport="http://schemas.xmlsoap.org/soap/http" style="document"/> <operation name="getOperationHistory"> <soap12:operation soapAction=""/> <input> <soap12:body use="literal"/> </input> <output> <soap12:body use="literal"/> </output> <fault name="OperationHistoryFault"> <soap12:fault name="OperationHistoryFault" use="literal"/> </fault> <fault name="AuthorizationFault"> <soap12:fault name="AuthorizationFault" use="literal"/> </fault> </operation> <operation name="getLanguages"> <soap12:operation soapAction=""/> <input> <soap12:body use="literal"/> </input> <output> <soap12:body use="literal"/> </output> <fault name="OperationHistoryFault"> <soap12:fault name="OperationHistoryFault" use="literal"/> </fault> <fault name="AuthorizationFault"> <soap12:fault name="AuthorizationFault" use="literal"/> </fault> <fault name="LanguageFault"> <soap12:fault name="LanguageFault" use="literal"/> </fault> </operation> <operation name="getCustomDutyEventsForMail"> <soap12:operation soapAction=""/> <input> <soap12:body use="literal"/> </input> <output> <soap12:body use="literal"/> </output> <fault name="CustomDutyEventsForMailFault"> <soap12:fault name="CustomDutyEventsForMailFault" use="literal"/> </fault> <fault name="AuthorizationFault"> <soap12:fault name="AuthorizationFault" use="literal"/> </fault> <fault name="LanguageFault"> <soap12:fault name="LanguageFault" use="literal"/> </fault> </operation> <operation name="getSmsHistory"> <soap12:operation soapAction=""/> <input> <soap12:body use="literal"/> </input> <output> <soap12:body use="literal"/> </output> <fault name="SmsHistoryFault"> <soap12:fault name="SmsHistoryFault" use="literal"/> </fault> <fault name="AuthorizationFault"> <soap12:fault name="AuthorizationFault" use="literal"/> </fault> <fault name="LanguageFault"> <soap12:fault name="LanguageFault" use="literal"/> </fault> </operation> <operation name="PostalOrderEventsForMail"> <soap12:operation soapAction=""/> <input> <soap12:body use="literal"/> </input> <output> <soap12:body use="literal"/> </output> <fault name="PostalOrderEventsForMailFault"> <soap12:fault name="PostalOrderEventsForMailFault" use="literal"/> </fault> </operation> </binding> <service name="OperationHistory12"> <port name="OperationHistory12Port" binding="tns:OperationHistory12PortBinding"> <soap12:address location="https://tracking.russianpost.ru/rtm34"/> </port> </service> </definitions>
NOWDOC;

        $response = <<<NOWDOC
<?xml version='1.0' encoding='UTF-8'?> <S:Envelope xmlns:S="http://www.w3.org/2003/05/soap-envelope"> <S:Body> <ns7:getOperationHistoryResponse xmlns:ns2="http://russianpost.org/sms-info/data" xmlns:ns3="http://russianpost.org/operationhistory/data" xmlns:ns4="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ns5="http://www.russianpost.org/custom-duty-info/data" xmlns:ns6="http://www.russianpost.org/RTM/DataExchangeESPP/Data" xmlns:ns7="http://russianpost.org/operationhistory"> <ns3:OperationHistoryData> <ns3:historyRecord> <ns3:AddressParameters> <ns3:DestinationAddress> <ns3:Index>663300</ns3:Index> <ns3:Description>Норильск Почтамт</ns3:Description> </ns3:DestinationAddress> <ns3:OperationAddress> <ns3:Index>111555</ns3:Index> <ns3:Description>Москва 555</ns3:Description> </ns3:OperationAddress> <ns3:MailDirect> <ns3:Id>643</ns3:Id> <ns3:Code2A>RU</ns3:Code2A> <ns3:Code3A>RUS</ns3:Code3A> <ns3:NameRU>Российская Федерация</ns3:NameRU> <ns3:NameEN>Russian Federation</ns3:NameEN> </ns3:MailDirect> <ns3:CountryOper> <ns3:Id>643</ns3:Id> <ns3:Code2A>RU</ns3:Code2A> <ns3:Code3A>RUS</ns3:Code3A> <ns3:NameRU>Российская Федерация</ns3:NameRU> <ns3:NameEN>Russian Federation</ns3:NameEN> </ns3:CountryOper> </ns3:AddressParameters> <ns3:FinanceParameters> <ns3:Payment>2500000</ns3:Payment> <ns3:Value>2500000</ns3:Value> <ns3:MassRate>152000</ns3:MassRate> <ns3:InsrRate>25000</ns3:InsrRate> <ns3:AirRate>0</ns3:AirRate> <ns3:Rate>0</ns3:Rate> </ns3:FinanceParameters> <ns3:ItemParameters> <ns3:Barcode>EA123456789RU</ns3:Barcode> <ns3:ValidRuType>true</ns3:ValidRuType> <ns3:ValidEnType>false</ns3:ValidEnType> <ns3:PostMark> <ns3:Id>0</ns3:Id> <ns3:Name>Без отметки</ns3:Name> </ns3:PostMark> <ns3:Id>0</ns3:Id> <ns3:Name>Без отметки</ns3:Name> <ns3:MailRank> <ns3:Id>0</ns3:Id> <ns3:Name>Без разряда</ns3:Name> </ns3:MailRank> <ns3:PostMark> <ns3:Id>0</ns3:Id> <ns3:Name>Без отметки</ns3:Name> </ns3:PostMark> <ns3:MailType> <ns3:Id>7</ns3:Id> <ns3:Name>Отправление EMS</ns3:Name> </ns3:MailType> <ns3:MailCtg> <ns3:Id>4</ns3:Id> <ns3:Name>С объявленной ценностью и наложенным платежом</ns3:Name> </ns3:MailCtg> <ns3:Mass>2281</ns3:Mass> </ns3:ItemParameters> <ns3:OperationParameters> <ns3:OperType> <ns3:Id>2</ns3:Id> <ns3:Name>Прием</ns3:Name> </ns3:OperType> <ns3:OperAttr> <ns3:Id>1</ns3:Id> <ns3:Name>Единичный</ns3:Name> </ns3:OperAttr> <ns3:OperDate>2015-07-09T18:08:00.000+03:00</ns3:OperDate> </ns3:OperationParameters> <ns3:UserParameters> <ns3:SendCtg> <ns3:Id>1</ns3:Id> <ns3:Name>Население</ns3:Name> </ns3:SendCtg> <ns3:Sndr>ИВАНОВ И Н</ns3:Sndr> <ns3:Rcpn>ПЕТРОВ Н И</ns3:Rcpn> </ns3:UserParameters> </ns3:historyRecord> </ns3:OperationHistoryData> </ns7:getOperationHistoryResponse> </S:Body> </S:Envelope>
NOWDOC;

        $mock = new MockHandler([
            new Response(200, [], $definitions),
            new Response(200, [], $response),
            new RequestException(
                'Error Communicating with Server',
                new Request('POST', 'https://tracking.russianpost.ru/rtm34?wsdl')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var RussianPost|MockObject $stub */
        $stub = $this->getMockBuilder(RussianPost::class)
                     ->setConstructorArgs([false, $handler])
                     ->setMethods(null)
                     ->getMock();

        $this->assertIsObject($stub->getOperationHistory('ZB005012591HK'));
        $this->assertFalse($stub->getOperationHistory('ZB005012591HK'));
    }

    /**
     * Tests getItemStatus method.
     *
     * @return void
     */
    public function testGetItemStatus(): void
    {
        /** @var RussianPost|MockObject $stub */
        $stub = $this->getMockBuilder(RussianPost::class)
                     ->setMethods(['getOperationHistory'])
                     ->getMock();

        $response                                    = new stdClass();
        $response->OperationParameters               = new stdClass();
        $response->OperationParameters->OperType     = new stdClass();
        $response->OperationParameters->OperType->Id = 2;
        $response->OperationParameters->OperAttr     = new stdClass();
        $response->OperationParameters->OperAttr->Id = 1;
        $response->OperationParameters->OperDate     = '2015-01-08T14:50:00.000+03:00';

        $response_2                                    = new stdClass();
        $response_2->OperationParameters               = new stdClass();
        $response_2->OperationParameters->OperType     = new stdClass();
        $response_2->OperationParameters->OperType->Id = 3;
        $response_2->OperationParameters->OperAttr     = new stdClass();
        $response_2->OperationParameters->OperAttr->Id = 4;
        $response_2->OperationParameters->OperDate     = '2015-01-08T14:50:00.000+03:00';

        $stub->method('getOperationHistory')
             ->will($this->onConsecutiveCalls(false, $response, $response_2));

        $this->assertFalse($stub->getItemStatus('ZB005012591HK'));
        $this->assertEquals([
            'status' => 'issued',
            'date'   => '2015-01-08T14:50:00.000+03:00'
        ], $stub->getItemStatus('ZB005012592HK'));
        $this->assertEquals([
            'status' => 'canceled_by_client_upon_receipt',
            'date'   => '2015-01-08T14:50:00.000+03:00'
        ], $stub->getItemStatus('ZB005012592HK'));
    }

    /**
     * Tests getResponseByTicket method.
     *
     * @return void
     */
    public function testGetResponseByTicket(): void
    {
        $definitions = <<<NOWDOC
<?xml version="1.0" encoding="UTF-8"?> <definitions xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:wsp="http://www.w3.org/ns/ws-policy" xmlns:wsp1_2="http://schemas.xmlsoap.org/ws/2004/09/policy" xmlns:wsam="http://www.w3.org/2007/05/addressing/metadata" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tns="http://fclient.russianpost.org" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://schemas.xmlsoap.org/wsdl/" targetNamespace="http://fclient.russianpost.org" name="ItemDataService"> <types> <xsd:schema> <xsd:import namespace="http://fclient.russianpost.org/postserver" schemaLocation="https://tracking.russianpost.ru/fc?xsd=1"/> </xsd:schema> <xsd:schema> <xsd:import namespace="http://fclient.russianpost.org" schemaLocation="https://tracking.russianpost.ru/fc?xsd=2"/> </xsd:schema> </types> <message name="getTicket"> <part xmlns:ns1="http://fclient.russianpost.org/postserver" name="parameters" element="ns1:ticketRequest"/> </message> <message name="getTicketResponse"> <part xmlns:ns2="http://fclient.russianpost.org/postserver" name="parameters" element="ns2:ticketResponse"/> </message> <message name="getResponseByTicket"> <part xmlns:ns3="http://fclient.russianpost.org/postserver" name="parameters" element="ns3:answerByTicketRequest"/> </message> <message name="getResponseByTicketResponse"> <part xmlns:ns4="http://fclient.russianpost.org/postserver" name="parameters" element="ns4:answerByTicketResponse"/> </message> <portType name="FederalClient"> <operation name="getTicket"> <input wsam:Action="http://fclient.russianpost.org/FederalClient/getTicketRequest" message="tns:getTicket"/> <output wsam:Action="http://fclient.russianpost.org/FederalClient/getTicketResponse" message="tns:getTicketResponse"/> </operation> <operation name="getResponseByTicket"> <input wsam:Action="http://fclient.russianpost.org/FederalClient/getResponseByTicketRequest" message="tns:getResponseByTicket"/> <output wsam:Action="http://fclient.russianpost.org/FederalClient/getResponseByTicketResponse" message="tns:getResponseByTicketResponse"/> </operation> </portType> <binding name="ItemDataServicePortBinding" type="tns:FederalClient"> <soap:binding transport="http://schemas.xmlsoap.org/soap/http" style="document"/> <operation name="getTicket"> <soap:operation soapAction=""/> <input> <soap:body use="literal"/> </input> <output> <soap:body use="literal"/> </output> </operation> <operation name="getResponseByTicket"> <soap:operation soapAction=""/> <input> <soap:body use="literal"/> </input> <output> <soap:body use="literal"/> </output> </operation> </binding> <service name="ItemDataService"> <port name="ItemDataServicePort" binding="tns:ItemDataServicePortBinding"> <soap:address location="https://tracking.russianpost.ru/fc"/> </port> </service> </definitions>
NOWDOC;

        $response = <<<NOWDOC
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/">
  <S:Body>
     <ns2:answerByTicketResponse xmlns:ns2="http://fclient.russianpost.org/postserver" xmlns:ns3="http://fclient.russianpost.org">
        <value FileName="" FileTypeID="2" FileNumber="1" RecipientID="1" DatePreparation="17.09.2015 17:20:48">
           <ns3:Item Barcode="ZB005012591HK">
              <ns3:Operation OperTypeID="1" OperCtgID="1" OperName="Прием" DateOper="08.09.2015 17:07:00" IndexOper="450083"/>
              <ns3:Operation OperTypeID="8" OperCtgID="4" OperName="Обработка" DateOper="10.09.2015 04:42:00" IndexOper="450962"/>
              <ns3:Operation OperTypeID="8" OperCtgID="0" OperName="Обработка" DateOper="12.09.2015 18:07:00" IndexOper="140983"/>
              <ns3:Operation OperTypeID="8" OperCtgID="4" OperName="Обработка" DateOper="13.09.2015 04:14:00" IndexOper="140980"/>
              <ns3:Operation OperTypeID="8" OperCtgID="4" OperName="Обработка" DateOper="13.09.2015 23:11:00" IndexOper="111949"/>
              <ns3:Operation OperTypeID="8" OperCtgID="2" OperName="Обработка" DateOper="14.09.2015 03:25:00" IndexOper="125362"/>
              <ns3:Operation OperTypeID="4" OperCtgID="3" OperName="Досылка почты" DateOper="15.09.2015 09:20:00" IndexOper="125362"/>
              <ns3:Operation OperTypeID="8" OperCtgID="2" OperName="Обработка" DateOper="16.09.2015 03:43:00" IndexOper="125364"/>
           </ns3:Item>
           <ns3:Item Barcode="ZB005012592HK">
              <ns3:Operation OperTypeID="1" OperCtgID="1" OperName="Прием" DateOper="08.09.2015 17:07:00" IndexOper="450083"/>
              <ns3:Operation OperTypeID="8" OperCtgID="4" OperName="Обработка" DateOper="10.09.2015 04:42:00" IndexOper="450962"/>
              <ns3:Operation OperTypeID="8" OperCtgID="0" OperName="Обработка" DateOper="12.09.2015 18:07:00" IndexOper="140983"/>
              <ns3:Operation OperTypeID="8" OperCtgID="4" OperName="Обработка" DateOper="13.09.2015 04:14:00" IndexOper="140980"/>
              <ns3:Operation OperTypeID="8" OperCtgID="4" OperName="Обработка" DateOper="13.09.2015 23:11:00" IndexOper="111949"/>
              <ns3:Operation OperTypeID="8" OperCtgID="2" OperName="Обработка" DateOper="14.09.2015 03:25:00" IndexOper="125362"/>
              <ns3:Operation OperTypeID="4" OperCtgID="3" OperName="Досылка почты" DateOper="15.09.2015 09:20:00" IndexOper="125362"/>
              <ns3:Operation OperTypeID="8" OperCtgID="2" OperName="Обработка" DateOper="16.09.2015 03:43:00" IndexOper="125364"/>
           </ns3:Item>
        </value>
     </ns2:answerByTicketResponse>
  </S:Body>
</S:Envelope>
NOWDOC;

        $mock = new MockHandler([
            new Response(200, [], $definitions),
            new Response(200, [], $response),
            new RequestException(
                'Error Communicating with Server',
                new Request('POST', 'https://tracking.russianpost.ru/rtm34?wsdl')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var RussianPost|MockObject $stub */
        $stub = $this->getMockBuilder(RussianPost::class)
                     ->setConstructorArgs([true, $handler])
                     ->setMethods(null)
                     ->getMock();

        $this->assertIsObject($stub->getResponseByTicket('20150917162048476CLIENTID'));
        $this->assertFalse($stub->getResponseByTicket('20150917162048476CLIENTID'));
    }

    /**
     * Tests getTicketMethod method.
     *
     * @return void
     */
    public function testGetTicket(): void
    {
        $definitions = <<<NOWDOC
<?xml version="1.0" encoding="UTF-8"?> <definitions xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:wsp="http://www.w3.org/ns/ws-policy" xmlns:wsp1_2="http://schemas.xmlsoap.org/ws/2004/09/policy" xmlns:wsam="http://www.w3.org/2007/05/addressing/metadata" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:tns="http://fclient.russianpost.org" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://schemas.xmlsoap.org/wsdl/" targetNamespace="http://fclient.russianpost.org" name="ItemDataService"> <types> <xsd:schema> <xsd:import namespace="http://fclient.russianpost.org/postserver" schemaLocation="https://tracking.russianpost.ru/fc?xsd=1"/> </xsd:schema> <xsd:schema> <xsd:import namespace="http://fclient.russianpost.org" schemaLocation="https://tracking.russianpost.ru/fc?xsd=2"/> </xsd:schema> </types> <message name="getTicket"> <part xmlns:ns1="http://fclient.russianpost.org/postserver" name="parameters" element="ns1:ticketRequest"/> </message> <message name="getTicketResponse"> <part xmlns:ns2="http://fclient.russianpost.org/postserver" name="parameters" element="ns2:ticketResponse"/> </message> <message name="getResponseByTicket"> <part xmlns:ns3="http://fclient.russianpost.org/postserver" name="parameters" element="ns3:answerByTicketRequest"/> </message> <message name="getResponseByTicketResponse"> <part xmlns:ns4="http://fclient.russianpost.org/postserver" name="parameters" element="ns4:answerByTicketResponse"/> </message> <portType name="FederalClient"> <operation name="getTicket"> <input wsam:Action="http://fclient.russianpost.org/FederalClient/getTicketRequest" message="tns:getTicket"/> <output wsam:Action="http://fclient.russianpost.org/FederalClient/getTicketResponse" message="tns:getTicketResponse"/> </operation> <operation name="getResponseByTicket"> <input wsam:Action="http://fclient.russianpost.org/FederalClient/getResponseByTicketRequest" message="tns:getResponseByTicket"/> <output wsam:Action="http://fclient.russianpost.org/FederalClient/getResponseByTicketResponse" message="tns:getResponseByTicketResponse"/> </operation> </portType> <binding name="ItemDataServicePortBinding" type="tns:FederalClient"> <soap:binding transport="http://schemas.xmlsoap.org/soap/http" style="document"/> <operation name="getTicket"> <soap:operation soapAction=""/> <input> <soap:body use="literal"/> </input> <output> <soap:body use="literal"/> </output> </operation> <operation name="getResponseByTicket"> <soap:operation soapAction=""/> <input> <soap:body use="literal"/> </input> <output> <soap:body use="literal"/> </output> </operation> </binding> <service name="ItemDataService"> <port name="ItemDataServicePort" binding="tns:ItemDataServicePortBinding"> <soap:address location="https://tracking.russianpost.ru/fc"/> </port> </service> </definitions>
NOWDOC;

        $response = <<<NOWDOC
<S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/"><S:Body><ns2:ticketResponse xmlns:ns2="http://fclient.russianpost.org/postserver" xmlns:ns3="http://fclient.russianpost.org"><value>20150917162048476CLIENTID</value></ns2:ticketResponse></S:Body> </S:Envelope>
NOWDOC;

        $mock = new MockHandler([
            new Response(200, [], $definitions),
            new Response(200, [], $response),
            new RequestException(
                'Error Communicating with Server',
                new Request('POST', 'https://tracking.russianpost.ru/rtm34?wsdl')
            ),
        ]);

        $handler = HandlerStack::create($mock);

        /** @var RussianPost|MockObject $stub */
        $stub = $this->getMockBuilder(RussianPost::class)
                     ->setConstructorArgs([true, $handler])
                     ->setMethods(null)
                     ->getMock();

        $this->assertIsString($stub->getTicket(['ZB005012591HK', 'ZB005012592HK']));
        $this->assertFalse($stub->getTicket(['ZB005012591HK', 'ZB005012592HK']));
    }
}

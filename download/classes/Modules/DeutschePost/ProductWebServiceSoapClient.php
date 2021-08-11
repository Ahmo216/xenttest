<?php

declare(strict_types=1);

namespace Xentral\Modules\DeutschePost;

use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use SoapClient;
use SoapFault;
use SoapHeader;
use SoapVar;

class ProductWebServiceSoapClient extends SoapClient
{
    /** @var string */
    const SOAP_ENDPOINT = 'https://prodws.deutschepost.de/ProdWSProvider_1_1/prodws?wsdl';

    /** @var string */
    private $mandantId;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    public function __construct(array $options, string $mandantId, string $username, string $password)
    {
        try {
            parent::__construct(self::SOAP_ENDPOINT, $options);
        } catch (SoapFault $exception){
            Log::error('Error while instantiating ' . __CLASS__ . " with message: {$exception->getMessage()}");
        }

        $this->mandantId = $mandantId;
        $this->username = $username;
        $this->password = $password;
    }


    public function __doRequest($request, $location, $action, $version, $oneWay = 0)
    {
        $root = new SimpleXMLElement($request);

        $body = $root->children('http://schemas.xmlsoap.org/soap/envelope/')[1];

        $requestNode = $body->children('urn:www-deutschepost-de:Product/ProductInformation/1.1/common')[0];

        $requestNode->addAttribute('shortList', 'true');

        return parent::__doRequest($root->asXML(), $location, $action, $version, $oneWay);
    }

    /**
     * @throws SoapFault
     * @return mixed
     *
     */
    public function getProductList()
    {
        $data = [
            'mandantID'         => $this->mandantId,
            'dedicatedProducts' => true,
            'responseMode'      => 0,
        ];

        $headerXml = <<<XML
    <wsse:Security xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
      <wsse:UsernameToken xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" wsu:Id="UsernameToken-47695D4677F169938C14458490956322">
        <wsse:Username>{$this->username}</wsse:Username>
        <wsse:Password
            Type="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText">{$this->password}</wsse:Password>
      </wsse:UsernameToken>
    </wsse:Security>
XML;

        $authHeader = new SoapHeader(
            'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd',
            'Security',
            new SoapVar($headerXml, XSD_ANYXML),
            true
        );

        $this->__setSoapHeaders($authHeader);

        return parent::getProductList($data);
    }
}

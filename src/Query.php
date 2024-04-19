<?php

declare(strict_types=1);

namespace Salsan\Clubs;

use DOMDocument;
use DOMXPath;
use Salsan\Utils\DOM\DOMDocumentTrait;

class Query
{
    use DOMDocumentTrait;

    private DOMDocument $dom;
    private string $url = 'https://www.federscacchi.com/fsi/index.php/struttura/societa';

    public function __construct(array $paramters)
    {
        $clubId = $paramters['clubId'] ?? '';
        $reg    = $paramters['reg'] ?? '';
        $pro    = $paramters['pro'] ?? '';
        $ord    = $paramters['ord'] ?? '';
        $senso  = $paramters['senso'] ?? '';
        $asc    = $paramters['asc'] ?? '';
        $year   = $paramters['year'] ?? '';
        $den    = $paramters['den'] ?? '';

        $this->url .=
            "?idx={$clubId}" . // FSI ID
            "&reg={$reg}" .    // Region
            "&pro={$pro}" .    // Province
            "&ord={$ord}" .
            "&senso={$senso}" . // Order List Asc or Desc
            "&asc={$asc}" .
            "&anno={$year}" .   // Year Affiliation
            "&den={$den}" .     // Denomination
            '&ric=1';           // Hidden Value

        $this->dom = $this->getHTML($this->url, null);
    }

    public function getInfo(): iterable
    {
        $club = array();

        $xpath = new DOMXPath($this->dom);

        $clubsNumber = $this->getNumber();

        $position = 0;

        for ($i = 0; $clubsNumber > $i; $i++) {
            $xpath_root = '//div[@class="alert alert-success"]
                /following-sibling::div[ position() >' .  1 + $position
                .  ' and position() < ' . 10 + $position  . ']';

            $getID = $xpath->query(
                $xpath_root .
                    '//b[contains(text(), "Id FSI:")]
                    //following-sibling::text()'
            );

            $id =  $getID->length > 0 ? trim($getID->item(0)->nodeValue) : '';

            $getClubName = $xpath->query($xpath_root . '//h2/b');
            $club[$id]['name'] =  $getClubName->length > 0 ? $getClubName->item(0)->nodeValue : '';


            $getProvince = $xpath->query(
                $xpath_root .
                    '//b[contains(text(), "Provincia:")]
                /following-sibling::text()[normalize-space()]'
            );
            $club[$id]['province'] =  $getProvince->length > 0 ? $getProvince->item(0)->nodeValue : '';

            $getRegion = $xpath->query(
                $xpath_root .
                    '//b[contains(text(), "Regione:")]
                /following-sibling::text()[normalize-space()]'
            );
            $club[$id]['region'] =  $getRegion->length > 0 ? $getRegion->item(0)->nodeValue : '';

            $getPresident = $xpath->query(
                $xpath_root .
                    '//b[contains(text(), "Presidente:")]
                /following-sibling::text()[normalize-space()]'
            );
            $club[$id]['president'] = $getPresident->length > 0 ? $getPresident->item(0)->nodeValue : '';

            $getWebsite =  $xpath->query(
                $xpath_root .
                    '//a[b[contains(text(), "Sito Internet")]]/@href'
            );

            $club[$id]['website'] = $getWebsite->length > 0 ? $getWebsite->item(0)->nodeValue : '';

            $getAddress = $xpath->query(
                $xpath_root .
                    '//b[contains(text(), "Indirizzo:")]
                /following-sibling::text()[normalize-space()]'
            );

            $address = $getAddress->length > 0 ? explode('-', $getAddress->item(0)->nodeValue) : '';

            $club[$id]['address'] = array(
                'postal_code' => $address[0] ?? '',
                'street'      => $address[1] ?? '',
                'city'        => $address[2] ?? '',
            );

            $getTelephone = $xpath->query(
                $xpath_root .
                    '//b[contains(text(), "Telefono:")]
                /following-sibling::text()[normalize-space()]'
            );
            $club[$id]['contact']['tel'] = $getTelephone->length > 0 ? $getTelephone->item(0)->nodeValue : '';

            $getEmail = $xpath->query(
                $xpath_root .
                    "//joomla-hidden-mail/@text"
            );
            $club[$id]['contact']['email'] = $getEmail->length > 0 ? base64_decode($getEmail->item(0)->nodeValue) : '';

            $getCouncilors = $xpath->query(
                $xpath_root .
                    '//b[contains(text(), "Consiglio:")]
                /following-sibling::text()'
            );
            $club[$id]['councilors']  = $getCouncilors->length > 0
                ? explode(';', $getCouncilors->item(0)->nodeValue, -1)
                :  '';

            $position += 8;
        }

        return $club;
    }

    public function getNumber(): int
    {
        $xpath = new DOMXPath($this->dom);

        $nodes = $xpath->query('//div[@class="container-fluid"]//div[@class="alert alert-success"]//b');

        $club_numbers = (int) $nodes->item(0)->nodeValue;

        return $club_numbers;
    }
}

<?php

declare(strict_types=1);

namespace Salsan\Clubs;

use DOMDocument;
use DOMXPath;
use Salsan\Utils\DOM\DOMDocumentTrait;
use Salsan\Utils\String\HiddenSpaceTrait;

class Query
{
    use DOMDocumentTrait;
    use HiddenSpaceTrait;

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

            $id = $this->getNodeValue(
                $xpath,
                $xpath_root . '//b[contains(text(), "Id FSI:")]//following-sibling::text()'
            );

            $club[$id]['name'] = $this->getNodeValue(
                $xpath,
                $xpath_root .  '//h2/b'
            );

            $club[$id]['province'] = $this->getNodeValue(
                $xpath,
                $xpath_root  . '//b[contains(text(), "Provincia:")]/following-sibling::text()[normalize-space()]'
            );

            $club[$id]['region'] = $this->getNodeValue(
                $xpath,
                $xpath_root . '//b[contains(text(), "Regione:")]/following-sibling::text()[normalize-space()]'
            );

            $club[$id]['president'] = $this->getNodeValue(
                $xpath,
                $xpath_root . '//b[contains(text(), "Presidente:")]/following-sibling::text()[normalize-space()]'
            );

            $club[$id]['website'] =  $this->getNodeValue(
                $xpath,
                $xpath_root . '//a[b[contains(text(), "Sito Internet")]]/@href'
            );

            $address = explode(
                '-',
                $this->getNodeValue(
                    $xpath,
                    $xpath_root . '//b[contains(text(), "Indirizzo:")]
                    /following-sibling::text()[normalize-space()]'
                )
            );

            $club[$id]['address'] = array(
                'postal_code' => $this->trimmer($address[0]) ?? '',
                'street'      => $this->trimmer($address[1]) ?? '',
                'city'        => $this->trimmer($address[2]) ?? '',
            );

            $club[$id]['contact']['tel'] = $this->getNodeValue(
                $xpath,
                $xpath_root . '//b[contains(text(), "Telefono:")]/following-sibling::text()[normalize-space()]'
            );

            $club[$id]['contact']['email'] = base64_decode($this->getNodeValue(
                $xpath,
                $xpath_root . '//joomla-hidden-mail/@text'
            ));

            $club[$id]['councilors'] = explode(
                ';',
                $this->getNodeValue(
                    $xpath,
                    $xpath_root . '//b[contains(text(), "Consiglio:")]/following-sibling::text()'
                ),
                -1
            );

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

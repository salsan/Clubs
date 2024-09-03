<?php

declare(strict_types=1);

namespace Salsan\Clubs\Ffe;

use DOMDocument;
use DOMXPath;
use Salsan\Utils\DOM\DOMDocumentTrait;

class Listing
{
    use DOMDocumentTrait;

    /** @var array<string, DOMDocument> */
    private array $dom = [];

    /** @var array<string, ?string> */
    public array $url = ['clubs' => null, 'departments' => null];

    public function __construct()
    {
        // $this->dom['clubs'] = new DOMDocument();
        $this->dom['clubs'] = new DOMDocument();
        $this->dom['departments'] = new DOMDocument();
        libxml_use_internal_errors(true);


        $this->url['clubs'] = "http://echecs.asso.fr/ListeTops.aspx?Action=CLUB";
        $this->url['departments'] = 'http://echecs.asso.fr/Comites.aspx';

        $this->dom['clubs']->loadHTML($this->getPage(1));
        $this->dom['departments'] =  $this->getHTML($this->url['departments'], null);
    }

    /**
     * @return string[]
     */
    public function clubs(): array
    {
        $clubs = [];
        $page_number = $this->getPageNumber();
        $page = 1;

        do {
            $xpath = new DOMXPath($this->dom['clubs']);

            $clubs_list = $xpath->query('//table//tr[not(@class="liste_titre")]//td[2]//b/text()');

            if ($clubs_list !== false) {
                foreach ($clubs_list as $club) {
                    $clubName = $club->nodeValue;
                    if ($clubName !== null) {
                        $clubs[] = $clubName;
                    }
                }
            }

            $page++;
            $this->dom['clubs']->loadHTML($this->getPage($page));
        } while ($page <= $page_number);

        return $clubs;
    }

    /**
     * @param int $page_number
     * @return string
     */
    public function getPage(int $page_number): string
    {
        $postData = array(
            '__EVENTTARGET'   => 'ctl00$ContentPlaceHolderMain$PagerFooter',
            '__EVENTARGUMENT' => $page_number
        );

        $options = array(
            CURLOPT_URL            => $this->url['clubs'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postData),
            CURLOPT_HTTPHEADER     => array(
                'User-Agent: https://github.com/salsan/Clubs',
                'Content-Type: application/x-www-form-urlencoded',
            )
        );

        $ch = curl_init();

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        curl_close($ch);

        return gettype($response) === 'string' ? $response : '';
    }

    public function getPageNumber(): int
    {
        $xpath = new DOMXPath($this->dom['clubs']);

        $last_page_nodes = $xpath->query('//table[@class="Pager"]//td[last()-1]//a/text()');

        return ($last_page_nodes !== false && $last_page_nodes->length > 0) ? (int) $last_page_nodes[0]->nodeValue : 0;
    }

    /**
     * @return \DOMNode|null
     */
    public function getTable(): ?\DOMNode
    {
        $xpath = new DOMXPath($this->dom['clubs']);

        $table_nodes = $xpath->query('//div[@class="page-mid"]/table');

        return ($table_nodes !== false && $table_nodes->length > 0) ? $table_nodes->item(0) : null;
    }

    public function getNumber(): int
    {
        return count($this->clubs());
    }

    /**
     *  @return array<string, string> $departments
     */
    public function departments(): array
    {
        $departments = [];

        $xpath = new \DOMXPath($this->dom['departments']);

        $areas = $xpath->query('//map[@name="MapDepartements"]/area');

        if ($areas === false) {
            return $departments;
        }

        foreach ($areas as $area) {
            $page = $this->dom['departments']->saveHTML($area);

            if ($page !== false) {
                preg_match('/href="FicheComite\.aspx\?Ref=([0-9A-Za-z]+)".+alt="?([^"]+)"/', $page, $clubs);
            }

            if (isset($clubs[1]) && isset($clubs[2])) {
                $departments[$clubs[1]] = $clubs[2];
            }
        }

        return $departments;
    }
}

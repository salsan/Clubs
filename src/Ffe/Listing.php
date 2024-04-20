<?php

declare(strict_types=1);

namespace Salsan\Clubs\Ffe;

use DOMDocument;
use DOMXPath;
use Salsan\Utils\DOM\DOMDocumentTrait;

class Listing
{
    use DOMDocumentTrait;

    private array $dom = array('clubs' => '', 'departments' => '');
    public $url = array('clubs' => null, 'departments' => null);

    public function __construct()
    {
        $this->dom['clubs'] = new DOMDocument();
        libxml_use_internal_errors(true);


        $this->url['clubs'] = "http://echecs.asso.fr/ListeTops.aspx?Action=CLUB";
        $this->url['departments'] = 'http://echecs.asso.fr/Comites.aspx';

        $this->dom['clubs']->loadHTML($this->getPage(1));
        $this->dom['departments'] =  $this->getHTML($this->url['departments'], null);
    }

    public function clubs(): array
    {
        $clubs = [];
        $page_number = $this->getPageNumber();
        $page = 1;

        do {
            $xpath = new DOMXPath($this->dom['clubs']);

            $clubs_list = $xpath->query('//table//tr[not(@class="liste_titre")]//td[2]//b/text()');

            foreach ($clubs_list as $club) {
                array_push($clubs, $club->nodeValue);
            }

            $page++;
            $this->dom['clubs']->loadHTML($this->getPage($page));
        } while ($page <= $page_number);

        return $clubs;
    }

    public function getPage($page_number)
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

        return $response;
    }

    public function getPageNumber()
    {
        $xpath = new DOMXPath($this->dom['clubs']);

        $last_page = $xpath->query('//table[@class="Pager"]//td[last()-1]//a/text()')[0]->nodeValue;

        return $last_page;
    }

    public function getTable()
    {
        $xpath = new DOMXPath($this->dom['clubs']);

        $table = $xpath->query('//div[@class="page-mid"]/table')->item(0);

        return $table;
    }

    public function getNumber(): int
    {
        return count($this->clubs());
    }

    public function departments(): array
    {
        $departments = [];

        $xpath = new \DOMXPath($this->dom['departments']);

        $areas = $xpath->query('//map[@name="MapDepartements"]/area');

        foreach ($areas as $area) {
            $page = $this->dom['departments']->saveHTML($area);

            preg_match('/href="FicheComite\.aspx\?Ref=([0-9A-Za-z]+)".+alt="?([^"]+)"/', $page, $clubs);

            if (isset($clubs[1]) && isset($clubs[2])) {
                $departments[$clubs[1]] = $clubs[2];
            }
        }

        return $departments;
    }
}

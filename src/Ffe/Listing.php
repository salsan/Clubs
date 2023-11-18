<?php

declare(strict_types=1);

namespace Salsan\Clubs\Ffe;

use DOMDocument;
use DOMXPath;

class Listing
{
    private DOMDocument $dom;
    public $url;

    function __construct()
    {
        $this->dom = new DOMDocument();
        libxml_use_internal_errors(true);


        $this->url = "http://echecs.asso.fr/ListeTops.aspx?Action=CLUB";

        $this->dom->loadHTML($this->get_page(1));
    }

    public function clubs(): array
    {
        $clubs = [];
        $page_number = $this->get_page_number();
        $page = 1;

        do {
            $xpath = new DOMXPath($this->dom);

            $clubs_list = $xpath->query('//table//tr[not(@class="liste_titre")]//td[2]//b/text()');

            foreach ($clubs_list as $club) {
                array_push($clubs,   $club->nodeValue);
            }

            $page++;
            $this->dom->loadHTML($this->get_page($page));
        } while ($page <= $page_number);

        return $clubs;
    }

    public function get_page($page_number)
    {
        $postData = array(
            '__EVENTTARGET'   => 'ctl00$ContentPlaceHolderMain$PagerFooter',
            '__EVENTARGUMENT' => $page_number
        );

        $options = array(
            CURLOPT_URL            => $this->url,
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

    public function get_page_number()
    {
        $xpath = new DOMXPath($this->dom);

        $last_page = $xpath->query('//table[@class="Pager"]//td[last()-1]//a/text()')[0]->nodeValue;

        return $last_page;
    }

    public function get_table()
    {
        $xpath = new DOMXPath($this->dom);

        $table = $xpath->query('//div[@class="page-mid"]/table')->item(0);

        return $table;
    }

    public function get_number_rows()
    {
        $table = $this->get_table();
        $number_row = count($table->getElementsByTagName('tr'));

        return $number_row;
    }
}

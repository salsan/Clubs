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
        $clubs_number = $this->get_number_rows();
        $page = 1;

        do {
            for ($i = 2; $i <= $clubs_number; $i++) {
                $club = $this->dom->getElementsByTagName('tr')->item($i - 2)->getElementsByTagName('td')[1]->textContent ?? '';

                if (empty($club)) return $clubs;

                array_unshift($clubs,  $club);
            }

            $page++;
            $this->dom->loadHTML($this->get_page($page));
        } while ($page <= $page_number);

        var_dump($clubs);
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

        $page = $xpath->query('//table[@class="Pager"]')->item(0);
        $n_page = $xpath->query('.//td', $page);
        $last_page = $n_page[$n_page->length - 2]->nodeValue;

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

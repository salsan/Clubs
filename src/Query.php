<?php

declare(strict_types=1);

namespace Salsan\Clubs;

use DOMDocument;

class Query
{
    private DOMDocument $dom;
    private string $url = "https://www.federscacchi.it/str_soc.php";

    function __construct(array $paramters)
    {

        $this->dom = new DOMDocument();
        libxml_use_internal_errors(true);

        $clubId = $paramters['clubId'] ?? '';
        $reg = $paramters['reg'] ?? '';
        $pro = $paramters['pro'] ?? '';
        $ord = $paramters['ord'] ?? '';
        $senso = $paramters['senso'] ?? '';
        $asc = $paramters['asc'] ?? '';
        $year = $paramters['year'] ?? '';
        $den = $paramters['den'] ?? '';

        $this->url .=
            "?id={$clubId}" .
            "&reg={$reg}" .
            "&pro={$pro}" .
            "&ord={$ord}" .
            "&senso={$senso}" .
            "&asc={$asc}" .
            "&anno={$year}" .
            "&den={$den}" .
            "&ric=1";

        $this->dom->loadHTMLFile($this->url);
    }

    public function getInfo(): iterable
    {
        $club = [];
        // Regular expression for email from https://www.linuxjournal.com/article/9585           
        $reg = "/(\d+)\W+\w-\w+\W+([a-zA-Z0-9]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+[a-zA-Z0-9\._-]++)/";

        $row = $this->dom
            ->getElementsByTagName("table")
            ->item(5)
            ->getElementsByTagName("td");

        $clubsNumber =  $this->getNumber();

        for ($i = 0; $clubsNumber > $i; $i++) {
            // 14 is number of infocard for club
            $club_select = 14 * $i;

            $id = $row[6 + $club_select]->textContent;

            $club[$id]["name"] = $row[7 + $club_select]->textContent;

            $club[$id]["province"] = $row[8 + $club_select]->textContent;

            $club[$id]["region"] = $row[9 + $club_select]->textContent;

            $club[$id]["president"] = $row[10 + $club_select]->textContent;

            $club[$id]["website"] =
                $row[7 + $club_select]->getElementsByTagName("a")->length > 0
                ? $row[7 + $club_select]->getElementsByTagName("a")[0]->getAttribute("href")
                : "";

            $club[$id]["address"] = $row[11 + $club_select]->textContent;

            preg_match_all($reg, $row[12 + $club_select]->textContent, $matches, PREG_SET_ORDER, 0);

            $club[$id]["contact"] = [
                "tel" => $matches[0][1],
                "email" => $matches[0][2],
            ];

            $councilorsArr = explode(
                ";",
                str_replace("Consiglio: ", "", $row[13 + $club_select]->textContent),
            );

            array_pop($councilorsArr);
            $club[$id]["councilors"] = $councilorsArr;
        }
        return $club;
    }

    public function getNumber(): int
    {
        $row = $this->dom
            ->getElementsByTagName("table")
            ->item(5)
            ->getElementsByTagName("td");

        preg_match(
            "/\d+/",
            $row[count($row) - 1]->textContent,
            $clubs_number,
            0,
            0
        );

        return (int) $clubs_number[0];
    }
}

<?php

namespace Salsan\Clubs;

use DOMDocument;

class Listing
{
 private DOMDocument $dom;

  function __construct()
  {
    $this->dom = new DOMDocument();
    libxml_use_internal_errors(true);

    $url = "https://www.federscacchi.it/tema/";

    $this->dom->loadHTMLFile($url);
  }

  public function clubs(): iterable
  {
    $clubs = [];
    $reg = "/[0-9]+\s-\s(\X+)/";

    $options = $this->dom->getElementsByTagName("option");

    foreach ($options as $index => $club) {
      if ($index > 0) {
        preg_match($reg, $club->nodeValue, $club_name);
        $clubs[$club->getAttribute("value")] = $club_name[1];
      }
    }
    return $clubs;
  }

  public function getNumber(): int
  {
    return count($this->clubs());
  }

  public function getNameFromId(int $id): string
  {
    $clubs = $this->clubs();
    return $clubs[$id];
  }
}
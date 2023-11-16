<?php

declare(strict_types=1);

namespace Salsan\Clubs\Fsi;

use DOMDocument;
use Salsan\Utils\DOM\DOMDocumentTrait;
use Salsan\Utils\DOM\Form\DOMOptionTrait;

class Listing
{
  use DOMOptionTrait;
  use DOMDocumentTrait;
  private DOMDocument $dom;

  function __construct()
  {
    $url = "https://www.federscacchi.it/tema/";
   
    $this->dom = $this->getHTML($url, null);
  }

  public function clubs(): iterable
  {
    $clubs = [];
    $reg = '/\d+ - /m';

    $options = $this->getArray('signup_societa', $this->dom);

    foreach ($options as $index => $club) {
      if ($index > 0) {
        $clubs[$index] = preg_replace($reg, '', $club);
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

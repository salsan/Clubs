<?php

declare(strict_types=1);

namespace Salsan\Clubs;

use DOMDocument;
use Salsan\Utils\DOM\Form\DOMOptionTrait;

class Listing
{
  use DOMOptionTrait;
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

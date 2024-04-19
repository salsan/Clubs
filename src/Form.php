<?php

declare(strict_types=1);

namespace Salsan\Clubs;

use DOMDocument;
use Salsan\Utils\DOM\Form\DOMOptionTrait;
use Salsan\Utils\DOM\DOMDocumentTrait;

class Form
{
    use DOMOptionTrait;
    use DOMDocumentTrait;

    private DOMDocument $dom;
    private string $url = "https://www.federscacchi.com/fsi/index.php/struttura/societa";


    public function __construct()
    {
        $this->dom = $this->getHTML($this->url, null);
    }

    public function getRegions(): array
    {
        return ($this->getArray("'reg'", $this->dom));
    }

    public function getProvinces(): array
    {
        return ($this->getArray("'pro'", $this->dom));
    }

    // public function getOrder(): array
    // {
    //     return ($this->getArray("'ord'", $this->dom));
    // }

    public function getDirection(): array
    {
        return ($this->getArray("'senso'", $this->dom));
    }

    public function getYears(): array
    {
        return ($this->getArray("'anno'", $this->dom));
    }
}

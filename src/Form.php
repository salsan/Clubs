<?php

declare(strict_types=1);

namespace Salsan\Clubs;

use DOMDocument;

class Form
{
    use DOMOptionTrait;

    private DOMDocument $dom;
    private string $url = "https://www.federscacchi.it/str_soc.php";


    function __construct()
    {
        $this->dom = new DOMDocument();
        libxml_use_internal_errors(true);

        $this->dom->loadHTMLFile($this->url);
    }

    public function getRegions(): array
    {
        return ($this->getArray("'reg'", $this->dom));
    }

    public function getProvinces(): array
    {
        return ($this->getArray("'pro'", $this->dom));
    }

    public function getOrder(): array
    {
        return ($this->getArray("'ord'", $this->dom));
    }

    public function getDirection(): array
    {
        return ($this->getArray("'senso'", $this->dom));
    }

    public function getYears(): array
    {
        return ($this->getArray("'anno'", $this->dom));
    }
}

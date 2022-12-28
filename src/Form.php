<?php

namespace Salsan\Clubs;

use DOMDocument;

class Form
{
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
        $option = new DOMOption();
        return($option->getArray("'reg'", $this->dom));     
    }

    public function getProvinces(): array
    {
        $option = new DOMOption();
        return ($option->getArray("'pro'", $this->dom));
    }

    public function getOrder(): array
    {
        $option = new DOMOption();
        return ($option->getArray("'ord'", $this->dom));
    }

    public function getDirection(): array
    {
        $option = new DOMOption();
        return ($option->getArray("'senso'", $this->dom));
    }

    public function getYears(): array
    {
        $option = new DOMOption();
        return ($option->getArray("'anno'", $this->dom));
    }
}

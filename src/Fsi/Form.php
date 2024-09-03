<?php

declare(strict_types=1);

namespace Salsan\Clubs\Fsi;

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

    /**
     * @return array<string, string>
    */
    public function getRegions(): array
    {
        return ($this->getArray("'reg'", $this->dom));
    }

    /**
     * @return array<string, string>
     */
    public function getProvinces(): array
    {
        return ($this->getArray("'pro'", $this->dom));
    }
    /**
     * @return array<string, string>
     */
    public function getDirection(): array
    {
        return ($this->getArray("'senso'", $this->dom));
    }
    /**
     * @return array<string, string>
     */
    public function getYears(): array
    {
        return ($this->getArray("'anno'", $this->dom));
    }
}

<?php

namespace Salsan\Clubs;

use DOMDocument;
use DOMXPath;

trait DOMOption
{
    public function getArray(string $name, DOMDocument $dom): array
    {
        $options = array();
        $xpath = new DOMXPath($dom);
        $select = $xpath->query("//*[contains(@name, $name)]//option");
        foreach ($select as $option) {
            $text = trim($option->textContent);
            if (strlen($text) > 0)
                $options[$option->getAttribute('value')] = $text;
        }
        return $options;
    }
}

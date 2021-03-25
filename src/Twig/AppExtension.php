<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface {
    public function getGlobals(): array
    {
        return [
            'locale' => $this->locale
        ];
    }
    private $locale;
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }



    // list of available filters
    public function getFilters(): array
    {
        return [
            // name followed by the callable
          new \Twig\TwigFilter('price', [
              // on this instance, method name
              $this, 'priceFilter'
          ])
        ];
    }

    // value being filtered
    public function priceFilter($number): string
    {
        return '$' . number_format($number, 2, ',', '.');
    }


}
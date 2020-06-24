<?php


namespace NS\SimpleUserBundle\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class GlobalsExtension extends AbstractExtension implements GlobalsInterface
{
    protected $templates;

    /**
     * GlobalsExtension constructor.
     *
     * @param $templates
     */
    public function __construct($templates)
    {
        $this->templates = $templates;
    }

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        return [
            'ns_simple_user' => [
                'templates' => $this->templates
            ]
        ];
    }
}

<?php declare(strict_types=1);

namespace DrdPlus\AsterionSkeleton\Web;

class WebFiles extends \Granam\WebContentBuilder\Web\WebFiles
{
    public function __construct(WebRootProvider $webRootProvider)
    {
        parent::__construct($webRootProvider);
    }
}
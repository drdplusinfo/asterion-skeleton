<?php declare(strict_types=1);

namespace DrdPlus\AsterionSkeleton\Web;

use DrdPlus\AsterionSkeleton\HtmlHelper;
use Granam\WebContentBuilder\Web\HeadInterface;

class RulesMainContent extends MainContent
{
    public function __construct(HtmlHelper $htmlHelper, HeadInterface $head, RulesMainBody $body)
    {
        parent::__construct($htmlHelper, $head, $body);
    }
}
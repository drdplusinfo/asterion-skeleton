<?php declare(strict_types=1);

namespace DrdPlus\AsterionSkeleton\Web;

use DrdPlus\AsterionSkeleton\HtmlHelper;
use Granam\WebContentBuilder\Web\HeadInterface;

class TablesContent extends MainContent
{
    public function __construct(HtmlHelper $htmlHelper, HeadInterface $head, TablesBody $tablesBody)
    {
        parent::__construct($htmlHelper, $head, $tablesBody);
    }

}
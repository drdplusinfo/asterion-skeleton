<?php
namespace DrdPlus\RulesSkeleton;

class PageCache extends Cache
{
    protected function getCachePrefix(): string
    {
        return 'page';
    }

}
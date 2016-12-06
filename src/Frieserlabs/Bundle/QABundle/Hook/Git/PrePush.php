<?php
namespace Frieserlabs\Bundle\QABundle\Hook\Git;

use Frieserlabs\Bundle\QABundle\Hook\Hook;

class PrePush extends Hook
{
    const PRE_PUSH_HOOK_NAME = 'pre_push';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::PRE_PUSH_HOOK_NAME;
    }
}
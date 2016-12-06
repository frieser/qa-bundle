<?php
namespace Frieserlabs\Bundle\QABundle\Hook\Git;

use Frieserlabs\Bundle\QABundle\Hook\Hook;

class PreCommit extends Hook
{
    const PRE_COMMIT_HOOK_NAME = 'pre_commit';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::PRE_COMMIT_HOOK_NAME;
    }
}
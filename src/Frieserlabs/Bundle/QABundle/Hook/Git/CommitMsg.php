<?php
namespace Frieserlabs\Bundle\QABundle\Hook\Git;

use Frieserlabs\Bundle\QABundle\Hook\Hook;

class CommitMsg extends Hook
{
    const COMMIT_MSG_HOOK_NAME = 'commit_msg';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return self::COMMIT_MSG_HOOK_NAME;
    }
}
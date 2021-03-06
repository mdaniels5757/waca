<?php
/******************************************************************************
 * Wikipedia Account Creation Assistance tool                                 *
 *                                                                            *
 * All code in this file is released into the public domain by the ACC        *
 * Development Team. Please see team.json for a list of contributors.         *
 ******************************************************************************/

namespace Waca\Background\Task;

use Waca\Background\BackgroundTaskBase;
use Waca\DataObjects\JobQueue;
use Waca\DataObjects\Request;
use Waca\DataObjects\User;
use Waca\DataObjects\WelcomeTemplate;
use Waca\Helpers\MediaWikiHelper;
use Waca\Helpers\OAuthUserHelper;
use Waca\PdoDatabase;
use Waca\RequestStatus;

class WelcomeUserTask extends BackgroundTaskBase
{
    public static function enqueue(User $triggerUser, Request $request, PdoDatabase $database)
    {
        $job = new JobQueue();
        $job->setDatabase($database);
        $job->setTask(WelcomeUserTask::class);
        $job->setRequest($request->getId());
        $job->setTriggerUserId($triggerUser->getId());
        $job->save();
    }

    public function execute()
    {
        $database = $this->getDatabase();
        $request = $this->getRequest();
        $user = $this->getTriggerUser();

        if ($user->getWelcomeTemplate() === null) {
            $this->markFailed('Welcome template not specified');

            return;
        }

        /** @var WelcomeTemplate $template */
        $template = WelcomeTemplate::getById($user->getWelcomeTemplate(), $database);

        if ($template === false) {
            $this->markFailed('Welcome template missing');

            return;
        }

        $oauth = new OAuthUserHelper($user, $database, $this->getOauthProtocolHelper(),
            $this->getSiteConfiguration());
        $mediaWikiHelper = new MediaWikiHelper($oauth, $this->getSiteConfiguration());

        if ($request->getStatus() !== RequestStatus::CLOSED) {
            $this->markFailed('Request is currently open');

            return;
        }

        if (!$mediaWikiHelper->checkAccountExists($request->getName())){
            $this->markFailed('Account does not exist!');

            return;
        }

        $this->performWelcome($template, $request, $mediaWikiHelper);
        $this->markComplete();
    }

    /**
     * Performs the welcome
     *
     * @param WelcomeTemplate $template
     * @param Request         $request
     * @param MediaWikiHelper $mediaWikiHelper
     */
    private function performWelcome(
        WelcomeTemplate $template,
        Request $request,
        MediaWikiHelper $mediaWikiHelper
    ) {
        $templateText = $template->getBotCode();
        $templateText = str_replace('$signature', '~~~~', $templateText);
        $templateText = str_replace('$username', $request->getName(), $templateText);

        $mediaWikiHelper->addTalkPageMessage($request->getName(), 'Welcome!', 'Welcoming user created through [[WP:ACC]]', $templateText);
    }
}
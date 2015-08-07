<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FormaLibre\PlatformAdministrationBundle\Manager;

use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use FormaLibre\PlatformAdministrationBundle\Entity\Platform;
use Claroline\CoreBundle\Persistence\ObjectManager;
use Claroline\CoreBundle\Manager\ApiManager;
use Claroline\CoreBundle\Manager\OauthManager;
use Claroline\CoreBundle\Entity\Oauth\FriendRequest;
use Psr\Log\LoggerInterface;
use Claroline\BundleRecorder\Log\LoggableTrait;
use Psr\Log\LogLevel;

/**
 * @Service("formalibre.manager.platform_manager")
 */
class PlatformManager
{
    use LoggableTrait;

    private $om;
    private $platformRepository;
    private $apiManager;
    private $oauthManager;
    /**
     * @InjectParams({
     *     "om"           = @Inject("claroline.persistence.object_manager"),
     *     "apiManager"   = @Inject("claroline.manager.api_manager"),
     *     "oauthManager" = @Inject("claroline.manager.oauth_manager"),
     * })
     */
    public function __construct(
        ObjectManager $om,
        ApiManager $apiManager,
        OauthManager $oauthManager
    )
    {
        $this->om = $om;
        $this->platformRepository = $om->getRepository('FormaLibrePlatformAdministrationBundle:Platform');
        $this->apiManager = $apiManager;
        $this->oauthManager = $oauthManager;
    }

    public function findAll()
    {
        return $this->platformRepository->findAll();
    }

    private function getData(FriendRequest $request)
    {
        $url = 'api/infos.json';
        $serverOutput = $this->apiManager->url($request, $url);
        $firstResult = json_decode($serverOutput, true);

        if ($firstResult === null || array_key_exists('error', $firstResult)) {
            throw new \Exception('something went wrong: ' . $serverOutput);
        }

        $url = 'api/packages.json';
        $serverOutput = $this->apiManager->url($request, $url);
        $secondResult = json_decode($serverOutput, true);

        if ($secondResult === null || array_key_exists('error', $secondResult)) {
            throw new \Exception('something went wrong: ' . $serverOutput);
        }

        return array('data' => $firstResult, 'packages' => $secondResult);
    }

    public function add(FriendRequest $friend)
    {
        $platform = new Platform();
        $platform->setFriendPlatform($friend);
        $platform->setSavedData($this->getData($friend));
        $this->om->persist($platform);
        $this->om->flush();

        return $platform;
    }

    public function refresh(Platform $platform)
    {
        $platform->setSavedData($this->getData($platform->getFriendPlatform()));
        $platform->setLastRefreshed(new \DateTime());
        $this->om->persist($platform);
        $this->om->flush();

        return $platform;
    }

    public function refreshNew()
    {
        $friends = $this->oauthManager->findAllActivatedFriendRequests();
        $platforms = $this->findAll();

        foreach ($friends as $friend) {
            $found = false;

            foreach ($platforms as $platform) {
                if ($platform->getFriendPlatform() === $friend) $found = $platform;
            }

            if (!$found) $this->add($friend);
        }
    }

    public function refreshAll()
    {
        $friends = $this->oauthManager->findAllActivatedFriendRequests();
        $total = count($friends);
        $this->log('Start refreshing ' . $total . ' platform(s)...');
        $platforms = $this->findAll();
        $i = 1;

        foreach ($friends as $friend) {
            $found = false;

            foreach ($platforms as $platform) {
                if ($platform->getFriendPlatform() === $friend) $found = $platform;
            }

            if ($this->logger) $this->log('Refreshing ' . $friend->getName() . " ($i/$total)...");
            $found ? $this->refresh($platform): $this->add($friend);
            $i++;
        }

        $this->log('Done.');
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}

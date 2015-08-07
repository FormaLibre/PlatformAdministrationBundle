<?php

namespace FormaLibre\PlatformAdministrationBundle\Listener;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Claroline\CoreBundle\Event\OpenAdministrationToolEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 *  @DI\Service()
 */
class PlatformAdministrationListener
{
    private $container;

    /**
     * @DI\InjectParams({
     *     "container" = @DI\Inject("service_container")
     * })
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @DI\Observe("administration_tool_platform_administration")
     *
     * @param OpenAdministrationToolEvent $event
     */
    public function onOpenEvent(OpenAdministrationToolEvent $event)
    {
        $response = new RedirectResponse(
            $this->container->get('router')->generate('formalibre_platformadministration_index'));

        $event->setResponse($response);
    }
}

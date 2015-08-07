<?php

namespace FormaLibre\PlatformAdministrationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as EXT;
use JMS\SecurityExtraBundle\Annotation as SEC;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use FormaLibre\PlatformAdministrationBundle\Manager\PlatformManager;
use FormaLibre\PlatformAdministrationBundle\Entity\Platform;

/**
 * @DI\Tag("security.secure_service")
 * @SEC\PreAuthorize("canOpenAdminTool('platform_administration')")
 */
class PlatformAdministrationController extends Controller
{
    /**
     * @DI\InjectParams({
     *     "pm" = @DI\Inject("formalibre.manager.platform_manager")
     * })
     */
    public function __construct(PlatformManager $pm)
    {
        $this->pm = $pm;
    }

    /**
     * @EXT\Route("/index", name="formalibre_platformadministration_index")
     * @EXT\Template
     *
     * @return Response
     */
    public function indexAction()
    {
        $platforms = $this->pm->findAll();

        return array('platforms' => $platforms);
    }

    /**
     * @EXT\Route("/refresh/new", name="formalibre_platformadministration_refresh_new")
     *
     * @return Response
     */
    public function refreshNewAction()
    {
        $this->pm->refreshNew();

        return new RedirectResponse($this->get('router')
            ->generate('formalibre_platformadministration_index'));
    }

    /**
     * @EXT\Route("/refresh/{platform}", name="formalibre_platformadministration_refresh")
     *
     * @return Response
     */
    public function refreshAction(Platform $platform)
    {
        $this->pm->refresh($platform);

        return new RedirectResponse($this->get('router')
            ->generate('formalibre_platformadministration_index'));
    }
}

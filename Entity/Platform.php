<?php

/*
 * This file is part of the Claroline Connect package.
 *
 * (c) Claroline Consortium <consortium@claroline.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FormaLibre\PlatformAdministrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Claroline\CoreBundle\Entity\Oauth\FriendRequest;

/**
 * @ORM\Table(name="formalibre__platform")
 * @ORM\Entity()
 */
class Platform
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var FriendRequest
     *
     * @ORM\OneToOne(targetEntity="Claroline\CoreBundle\Entity\Oauth\FriendRequest")
     * @ORM\JoinColumn(name="friend_request_id", onDelete="SET NULL")
     */
    protected $friendPlatform;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $savedData;

    /**
     * @ORM\Column(name="modification_date", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $lastRefreshed;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    public function getId()
    {
        return $this->id;
    }

    public function setFriendPlatform(FriendRequest $request)
    {
        $this->friendPlatform = $request;
    }

    public function getFriendPlatform()
    {
        return $this->friendPlatform;
    }

    public function setSavedData($data)
    {
        $this->savedData = $data;
    }

    public function getSavedData()
    {
        return $this->savedData;
    }

    public function getLastRefreshed()
    {
        return $this->lastRefreshed;
    }

    public function setLastRefreshed(\DateTime $lastRefreshed)
    {
        return $this->lastRefreshed = $lastRefreshed;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: elgrim
 * Date: 28/01/19
 * Time: 17:51
 */

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Client extends User
{
    /**
     * @ORM\ManyToMany(targetEntity="Request", inversedBy="clients")
     * @ORM\JoinTable(name="users_requests")
     */
    private $requests;

    public function __construct()
    {
        $this->requests = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getRequests()
    {
        return $this->requests;
    }

    public function addRequest(Request $request) : self
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
        }

        return $this;
    }

    public function deleteRequest(Request $request) : self
    {
        if ($this->requests->contains($request)) {
            $this->requests->removeElement($request);
        }

        return $this;
    }
}
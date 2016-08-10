<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use AppBundle\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
use AppBundle\Model\AccountInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class User
 * @package AppBundle\Entity
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserEntityRepository")
 * @ORM\Table(name="fos_user")
 * @JMSSerializer\ExclusionPolicy("all")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    protected $id;

    /**
     * @var string The email of the user.
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    protected $email;

    /**
     * @var string The username of the author.
     *
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("string")
     * @JMSSerializer\Groups({"users_all","users_summary"})
     */
    protected $username;

    /**
     * @ORM\ManyToMany(targetEntity="Account", inversedBy="users")
     * @ORM\JoinTable(name="users__accounts",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="account_id", referencedColumnName="id")}
     * )
     * @JMSSerializer\Expose
     * @JMSSerializer\Type("ArrayCollection")
     * @JMSSerializer\MaxDepth(2)
     * @JMSSerializer\Groups({"users_all"})
     */
    private $accounts;

    /**
     * @var string Plain password. Used for model validation. Must not be persisted.
     */
    protected $plainPassword;
    /**
     * @var boolean Shows that the user is enabled
     */
    protected $enabled;
    /**
     * @var array Array, role(s) of the user
     */
    protected $roles;

    /**
     * @param AccountInterface $accountInterface
     * @return \AppBundle\Model\UserInterface
     */
    public function addAccount(AccountInterface $accountInterface)
    {
        if ( ! $this->hasAccount($accountInterface)) {
            $accountInterface->addUser($this);
            $this->accounts->add($accountInterface);
        }

        return $this;
    }

    /**
     * @param AccountInterface $accountInterface
     * @return \AppBundle\Model\UserInterface
     */
    public function removeAccount(AccountInterface $accountInterface)
    {
        if ($this->hasAccount($accountInterface)) {
            $accountInterface->removeUser($this);
            $this->accounts->removeElement($accountInterface);
        }

        return $this;
    }

    /**
     * @param AccountInterface $accountInterface
     * @return bool
     */
    public function hasAccount(AccountInterface $accountInterface)
    {
        return $this->accounts->contains($accountInterface);
    }

    /**
     * @return \AppBundle\Model\Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @return string
     */
    function jsonSerialize()
    {
        return [
            'id'       => $this->id,
            'username' => $this->username,
            'accounts' => $this->accounts,
        ];
    }
}

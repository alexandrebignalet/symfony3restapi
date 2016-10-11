<?php

namespace AppBundle\Entity;

use DateTime;
use FOS\UserBundle\Model\User as BaseUser;
use AppBundle\Model\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMSSerializer;
use AppBundle\Model\AccountInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
     */
    private $accounts;

    /**
     * @var string Plain password. Used for model validation. Must not be persisted.
     */
    protected $plainPassword;

    /**
     * @var array Array, role(s) of the user
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("authorities")
     * @JMSSerializer\Groups({"users_all"})
     */
    protected $roles;

    /**
     * @var datetime lastLogin
     * @JMSSerializer\Expose
     * @JMSSerializer\SerializedName("last_login")
     * @JMSSerializer\Groups({"users_all"})
     */
    protected $lastLogin;

    /**
     * @var string firstname
     * @ORM\Column(type="string", name="firstname")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"users_all"})
     */
    private $firstname;

    /**
     * @var string lastname
     * @ORM\Column(type="string", name="lastname")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"users_all"})
     */
    private $lastname;

    /**
     * @var integer age
     * @ORM\Column(type="integer", name="age")
     * @JMSSerializer\Expose
     * @JMSSerializer\Groups({"users_all"})
     */
    protected $age;


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->accounts = new ArrayCollection();
    }

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
     * @return Collection
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return integer
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param integer $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return string
     */
    function jsonSerialize()
    {
        return [
            'id'            => $this->id,
            'username'      => $this->username,
            'email'         => $this->email,
            'authorities'   => $this->roles,
            'last_login'    => $this->lastLogin,
            'firstname'     => $this->firstname,
            'lastname'      => $this->lastname,
            'age'           => $this->age
        ];
    }
}

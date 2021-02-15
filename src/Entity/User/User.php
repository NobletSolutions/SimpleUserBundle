<?php


namespace NS\SimpleUserBundle\Entity\User;

use NS\AdminBundle\Entity\AdminSoftDeletableEntity;
use \Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 *
 * Class User
 * @ORM\Entity(repositoryClass="NS\SimpleUserBundle\Repository\UserRepository")
 * @ORM\Table(name="ns_user", uniqueConstraints={@ORM\UniqueConstraint(name="user_idx", columns={"email"})})
 * @UniqueEntity("email")
 */
class User extends AdminSoftDeletableEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $first_name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $last_name;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     * @Assert\Email
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;

    /**
     * @var string
     */
    protected $plainPassword;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $registeredOn;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $resetToken;

    /**
     * @var array
     * @ORM\Column(type="array", nullable=true)
     */
    protected $roles;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $approved;

    /**
     * @var object
     */
    protected $profile;

    public function __construct()
    {
        parent::__construct();
        $this->salt = sha1($this->createdAt->format('U').uniqid('', true));
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        if($this->first_name && $this->last_name)
        {
            return $this->first_name . ' ' . $this->last_name;
        }

        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password = null): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string $salt
     */
    public function setSalt(string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredOn(): ?\DateTime
    {
        return $this->registeredOn;
    }

    /**
     * @param \DateTime $registeredOn
     */
    public function setRegisteredOn(\DateTime $registeredOn): void
    {
        $this->registeredOn = $registeredOn;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime $lastLogin
     */
    public function setLastLogin(\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return string
     */
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    /**
     * @param string $resetToken
     */
    public function setResetToken(string $resetToken): void
    {
        $this->resetToken = $resetToken;
    }

    /**
     * @return array
     */
    public function getRoles(): ?array
    {
        $roles = $this->roles;

        if($this->isApproved())
        {
            $roles[] = 'ROLE_APPROVED';
        }

        return $roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        if(!is_iterable($roles))
        {
            $roles = [$roles];
        }

        $this->roles = $roles;
    }

    /**
     * @param string $role
     */
    public function addRole(string $role): void
    {
        $this->roles[] = $role;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getRoles(), false);
    }

    public function removeRole(string $role): void
    {
        if($this->roles) {
            $key = array_search($role, $this->roles, false);

            if ($key !== false) {
                unset($this->roles[$key]);
            }
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name ?: $this->email;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * @param bool $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * @return object
     */
    public function getProfile(): ?object
    {
        return $this->profile;
    }

    /**
     * @param object $profile
     */
    public function setProfile(?object $profile): void
    {
        $this->profile = $profile;
    }
}

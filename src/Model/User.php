<?php

namespace App\Model;

use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use App\Framework\Auth\Interfaces\UserEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use JsonSerializable;

#[Table(name: 'users'), Entity(repositoryClass: "App\Framework\Auth\Repository\UserRepository")]
#[HasLifecycleCallbacks]
class User implements JsonSerializable, UserEntity
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id = 0;

    #[Column(type: 'string', unique: false, nullable: false)]
    private string $name = '';

    #[Column(type: 'string', unique: false, nullable: false)]
    private string $password = '';

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $email = '';

    #[Column(type: 'boolean', unique: false, nullable: false)]
    private bool $activated = false;

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $verification_token = '';

    #[Column(type: 'string', unique: true, nullable: false)]
    private string $password_token = '';

    #[Column(name: 'email_verified_at', nullable: true)]
    private ?\DateTime $emailVerifiedAt = null;

    #[Column(name: 'created_at', nullable: false)]
    private ?\DateTime $createdAt = null;

    #[Column(name: 'updated_at', nullable: false)]
    private ?\DateTime $updatedAt = null;

    #[OneToMany(mappedBy: "user", targetEntity: Ascii::class,  cascade: ["remove"])]
    private Collection $art;

    #[OneToMany(mappedBy: "user", targetEntity: Category::class,  cascade: ["remove"])]
    private Collection $categories;

    #[OneToMany(mappedBy: "user", targetEntity: Tag::class, cascade: ["remove"])]
    private Collection $tags;

    public function __construct()
    {
        $this->art = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Update timestamps before persisting the entity.
     *
     * @param \Doctrine\Persistence\Event\LifecycleEventArgs $args Arguments.
     *
     * @return void
     */
    #[PrePersist, PreUpdate]
    public function updateTimeStamps(LifecycleEventArgs $args): void
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        $this->updatedAt = new \DateTime();
    }

    /**
     * Add new Category.
     *
     * @param \App\Model\Tag $tag The tag to add.
     *
     * @return void
     */
    public function addTag(Tag $tag): void
    {
        $tag->setUser($this);
        $this->tags[] = $tag;
    }

    /**
     * Add new Category.
     *
     * @param \App\Model\Category $category The category to add.
     *
     * @return void
     */
    public function addCategory(Category $category): void
    {
        $category->setUser($this);
        $this->categories[] = $category;
    }

    /**
     * Add new Ascii Art.
     *
     * @param \App\Model\Ascii $ascii The Ascii Art to add.
     *
     * @return void
     */
    public function addArt(Ascii $ascii): void
    {
        $ascii->setUser($this);
        $this->art[] = $ascii;
    }

    /**
     * Set the name for the user.
     *
     * @param string $name The name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Set activated true or false.
     *
     * @param bool $activated The activation status.
     *
     * @return void
     */
    public function setActivated(bool $activated): void
    {
        $this->activated = $activated;
    }

    /**
     * Set the confirmation token.
     *
     * @param string $verification_token The verifcation token.
     *
     * @return void
     */
    public function setVerificationToken(string $verification_token): void
    {
        $this->verification_token = $verification_token;
    }

    /**
     * Set the password token.
     *
     * @param string $password_token The password token.
     *
     * @return void
     */
    public function setPasswordToken(string $password_token): void
    {
        $this->password_token = $password_token;
    }

    /**
     * Set the user password.
     *
     * @param string $password The password.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Set the user email.
     *
     * @param string $email The email.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Set confirmed at.
     *
     * @param \DateTime|null $emailVerifiedAt The DateTime.
     *
     * @return void
     */
    public function setEmailVerifiedAt(?\DateTime $emailVerifiedAt): void
    {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt The Datetime.
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Set UpdatedAt.
     *
     * @param \DateTime $updatedAt The DateTime.
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Return the name for the user.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Return the user id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Return the password tokeen.
     *
     * @return string
     */
    public function getPasswordToken(): string
    {
        return $this->password_token;
    }

    /**
     * Return the password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Return the user email.
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Return the art belonging to the user.
     *
     * @return Collection<Ascii>
     */
    public function getArt(): Collection
    {
        return $this->art;
    }

    /**
     * Return the categories for a user.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Return the tags for a user.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }


    /**
     * Return the Confirmation token.
     *
     * @return string
     */
    public function getVerificationToken(): string
    {
        return $this->verification_token;
    }

    /**
     * Return ConfirmedAt.
     *
     * @return \DateTime|null
     */
    public function getEmailVerifiedAt(): ?\DateTime
    {
        return $this->emailVerifiedAt;
    }

    /**
     * Return CreatedAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Return UpdatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }


    /**
     * Check to see if the user is activated.
     *
     * @return bool
     */
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * Check if the user is Authenticated.
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        return ($this->getId() > 0);
    }

    /**
     * Check if the user is a guest.
     *
     * @return bool
     */
    public function isGuest(): bool
    {
        return ($this->getId() == 0);
    }

    /**
     * Return the User as a array.
     *
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'activated' => $this->isActivated(),
            'email' => $this->getEmail(),
            'createdAt' => $this->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $this->getUpdatedAt()->format('Y-m-d H:i:s')
        ];
    }
}

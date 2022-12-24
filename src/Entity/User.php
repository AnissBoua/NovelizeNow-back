<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column]
    private ?int $coins = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $username = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Cart $cart = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CoinTransaction::class)]
    private Collection $coinTransactions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserNovel::class)]
    private Collection $userNovels;

    public function __construct()
    {
        // $this->roles[] = 'ROLE_USER';
        $this->transactions = new ArrayCollection();
        $this->coinTransactions = new ArrayCollection();
        $this->userNovels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCoins(): ?int
    {
        return $this->coins;
    }

    public function setCoins(int $coins): self
    {
        $this->coins = $coins;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(Cart $cart): self
    {
        // set the owning side of the relation if necessary
        if ($cart->getUser() !== $this) {
            $cart->setUser($this);
        }

        $this->cart = $cart;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CoinTransaction>
     */
    public function getCoinTransactions(): Collection
    {
        return $this->coinTransactions;
    }

    public function addCoinTransaction(CoinTransaction $coinTransaction): self
    {
        if (!$this->coinTransactions->contains($coinTransaction)) {
            $this->coinTransactions->add($coinTransaction);
            $coinTransaction->setUser($this);
        }

        return $this;
    }

    public function removeCoinTransaction(CoinTransaction $coinTransaction): self
    {
        if ($this->coinTransactions->removeElement($coinTransaction)) {
            // set the owning side to null (unless already changed)
            if ($coinTransaction->getUser() === $this) {
                $coinTransaction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserNovel>
     */
    public function getUserNovels(): Collection
    {
        return $this->userNovels;
    }

    public function addUserNovel(UserNovel $userNovel): self
    {
        if (!$this->userNovels->contains($userNovel)) {
            $this->userNovels->add($userNovel);
            $userNovel->setUser($this);
        }

        return $this;
    }

    public function removeUserNovel(UserNovel $userNovel): self
    {
        if ($this->userNovels->removeElement($userNovel)) {
            // set the owning side to null (unless already changed)
            if ($userNovel->getUser() === $this) {
                $userNovel->setUser(null);
            }
        }

        return $this;
    }
}

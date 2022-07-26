<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TokenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
#[ApiResource(
    collectionOperations: [
        "get",
    ],
    itemOperations: [
        "get",
    ],
)]

class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[
        Assert\NotBlank,
        Assert\Length([
            'min' => 2,
            'max' => 255,
        ])
    ]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[
        Assert\NotBlank([
            'message' => 'token.createdAt.NotBlank',
        ]),
        Assert\LessThan('today'),
        ASsert\GreaterThan('2008-01-01'),
    ]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column]
    #[
        Assert\GreaterThanOrEqual(0),
        //   Assert\Regex('/^[0-9]+(\.[0-9]{1,3})?$/')
    ]
    private ?float $price = null;

    #[ORM\Column(nullable: true)]
    #[
        Assert\GreaterThan(0),
    ]
    private ?int $rank = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    #[
        Assert\GreaterThan(0),
    ]
    private ?string $maxSupply = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    #[
        Assert\GreaterThan(0),
    ]
    private ?string $circulatingSupply = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[
        Assert\Length([
            'max' => 255,
        ]),
    ]
    private ?string $blockchainType = null;

    #[ORM\OneToMany(mappedBy: 'token', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\OneToMany(mappedBy: 'token', targetEntity: UserToken::class)]
    private Collection $userTokens;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->userTokens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(?int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getMaxSupply(): ?string
    {
        return $this->maxSupply;
    }

    public function setMaxSupply(?string $maxSupply): self
    {
        $this->maxSupply = $maxSupply;

        return $this;
    }

    public function getCirculatingSupply(): ?string
    {
        return $this->circulatingSupply;
    }

    public function setCirculatingSupply(?string $circulatingSupply): self
    {
        $this->circulatingSupply = $circulatingSupply;

        return $this;
    }

    public function getBlockchainType(): ?string
    {
        return $this->blockchainType;
    }

    public function setBlockchainType(?string $blockchainType): self
    {
        $this->blockchainType = $blockchainType;

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
            $this->transactions[] = $transaction;
            $transaction->setToken($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getToken() === $this) {
                $transaction->setToken(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserToken>
     */
    public function getUserTokens(): Collection
    {
        return $this->userTokens;
    }

    public function addUserToken(UserToken $userToken): self
    {
        if (!$this->userTokens->contains($userToken)) {
            $this->userTokens[] = $userToken;
            $userToken->setToken($this);
        }

        return $this;
    }

    public function removeUserToken(UserToken $userToken): self
    {
        if ($this->userTokens->removeElement($userToken)) {
            // set the owning side to null (unless already changed)
            if ($userToken->getToken() === $this) {
                $userToken->setToken(null);
            }
        }

        return $this;
    }
}

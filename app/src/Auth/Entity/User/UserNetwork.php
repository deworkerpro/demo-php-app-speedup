<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(readOnly: true)]
#[ORM\Table(name: 'auth_user_networks')]
#[ORM\UniqueConstraint(columns: ['network_name', 'network_identity'])]
final class UserNetwork
{
    #[ORM\Column(type: 'guid')]
    #[ORM\Id]
    private string $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'networks')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Embedded(class: Network::class)]
    private Network $network;

    public function __construct(User $user, Network $network)
    {
        $this->id = Uuid::uuid4()->toString();
        $this->user = $user;
        $this->network = $network;
    }

    public function getNetwork(): Network
    {
        return $this->network;
    }
}

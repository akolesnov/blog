<?php

namespace App\Entity;

use App\Entity\Comment;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity()]
#[ORM\Table(name: 'posts')]
#[ORM\Index(columns: ['author_id'])]
#[ORM\Index(columns: ['status'])]
#[ORM\Index(columns: ['created_at'])]
class Post
{
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    #[ORM\Column(nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: false)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $publishedAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'posts', targetEntity: User::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    private Collection $comments;

    private function __construct()
    {
        $this->id = Uuid::v4();
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow'));
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public static function fromDraft(User $author, ?string $title, ?string $body, ?string $slug): Post
    {
        $post = new self();
        $post->author = $author;
        $post->title = $title;
        $post->body = $body;
        $post->slug = $slug;
        $post->updatedAt = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow'));
        $post->status = self::DRAFT;
        
        return $post;
    }

    public static function fromPublished(?string $title, ?string $body, ?string $slug): Post
    {
        $post = new self();
        $post->title = $title;
        $post->body = $body;
        $post->slug = $slug;
        $post->updatedAt = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow'));
        $post->publishedAt = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow'));
        $post->status = self::PUBLISHED;
        
        return $post;
    }
}

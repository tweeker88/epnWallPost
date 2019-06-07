<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    private const IS_POST = 'post';
    private const IS_WAIT = 'wait';
    private const IS_CHECK = 'check';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $img;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    private function __construct(string $content, string $url, string $img, string $status)
    {
        $this->content = $content;
        $this->url = $url;
        $this->img = $img;
        $this->status = $status;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = self::IS_WAIT;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->content;
    }

    public function setText(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, array(self::IS_CHECK, self::IS_POST), true)) {
            throw new \InvalidArgumentException("Invalid status");
        }

        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public static function create(
        string $content,
        string $url,
        string $img,
        string $status
    ) {
        return new static(
            $content,
            $url,
            $img,
            $status
        );
    }
}

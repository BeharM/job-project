<?php

namespace App\Resource;

use App\Entity\Job;

class JobResource
{
    private $id;
    private $description;
    private $title;
    private $createdAt;

    public function __construct(Job $job)
    {
        $this->id = $job->getId();
        $this->description = $job->getTitle();
        $this->title = $job->getDescription();
        $this->createdAt = $job->getCreatedAt();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->format('Y-m-d');
    }
}

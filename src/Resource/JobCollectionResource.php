<?php

namespace App\Resource;

use JMS\Serializer\Annotation as JMS;

class JobCollectionResource
{
    private array $jobs = [];

    public function __construct(array $jobs)
    {
        foreach ($jobs as $user) {
            $this->jobs[] = new JobResource($user);
        }
    }

    public function getJobs(): array
    {
        return $this->jobs;
    }
}

<?php

namespace App\Application;

use App\Domain\Post as DomainPost;
use App\Application\Ports\PostRepositoryPort;

class CreatePost
{
    public function __construct(private PostRepositoryPort $repository) {}

    public function execute(string $title, string $content)
    {
        $post = new DomainPost($title, $content);
        return $this->repository->save($post);
    }
}

<?php

namespace App\Controller\Api;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HelloWorldController
{
    #[Route('/api/hello-world', name: 'hello-world', methods: ['GET'])]
    #[OA\Get(
        summary: "Returns a simple hello message",
        tags: ["Test API"]
    )]
    #[OA\Response(
        response: 200,
        description: "Successful response",
        content: new OA\JsonContent(type: "string", example: "Hello World!")
    )]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse('Hello World!');
    }
}
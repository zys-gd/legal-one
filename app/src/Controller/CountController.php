<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\CountRequestDto;
use App\Repository\Log\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

class CountController extends AbstractController
{
    public function __construct(
        private readonly LogRepository $repository,
    ) {
    }

    #[Route('/count', methods: Request::METHOD_GET)]
    public function __invoke(#[MapQueryString] ?CountRequestDto $request = new CountRequestDto()): Response
    {
        if (get_object_vars($request)) { // workaround of problems with MapQueryString
            return new JsonResponse(['counter' => $this->repository->search($request)]);
        }

        throw new BadRequestHttpException('Bad request');
    }
}

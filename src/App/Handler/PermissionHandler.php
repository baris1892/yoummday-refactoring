<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\PermissionService;
use Fig\Http\Message\StatusCodeInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Response\JSONResponse;
use ProgPhil1337\SimpleReactApp\HTTP\Response\ResponseInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Attribute\Route;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\Handler\HandlerInterface;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\HttpMethod;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

#[Route(httpMethod: HttpMethod::GET, uri: '/has_permission/{token}')]
class PermissionHandler implements HandlerInterface
{
    public function __construct(
        // use dependency injection
        private readonly PermissionService $permissionService,
    )
    {
    }

    public function __invoke(ServerRequestInterface $serverRequest, RouteParameters $parameters): ResponseInterface
    {
        $token = $parameters->get('token');
        if ($token === null) {
            // status code 400: the route parameter is missing entirely - this is a malformed request,
            // not a permission check that returned false.
            return $this->permissionResponse(false, StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        $isAuthorized = $this->permissionService->isTokenAuthorized((string)$token);

        // HTTP 200 for both options true and false: this endpoint is a query ("does this token have permission?"),
        // not an access gate. The HTTP status reflects the success of the request itself, not the permission outcome.
        // If this endpoint were used as an authorization guard, 403 for false and 404 for unknown tokens would be
        // appropriate instead.
        // Note: Exceptions are expected to be handled by the global ExceptionHandler middleware.
        return $this->permissionResponse($isAuthorized);
    }

    /**
     * Single place to construct the permission response — DRY, and easy to change
     * the shape (e.g. add a "reason" field) without touching the main flow.
     */
    private function permissionResponse(
        bool $hasPermission,
        int  $statusCode = StatusCodeInterface::STATUS_OK
    ): JSONResponse
    {
        return new JSONResponse(
            ['permission' => $hasPermission],
            $statusCode
        );
    }
}

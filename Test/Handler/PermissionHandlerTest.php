<?php

declare(strict_types=1);

namespace Test\Handler;

use App\Handler\PermissionHandler;
use App\Service\PermissionService;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use ProgPhil1337\SimpleReactApp\HTTP\Routing\RouteParameters;
use Psr\Http\Message\ServerRequestInterface;

class PermissionHandlerTest extends TestCase
{
    /**
     * Verifies that the handler correctly maps a 'true' from the service to a 200 OK JSON response.
     */
    public function testReturnsSuccessfulResponseWhenPermissionIsGranted(): void
    {
        // Mock the PermissionService to control the outcome
        $permissionService = $this->createMock(PermissionService::class);
        $permissionService->expects($this->once())
            ->method('hasRequiredPermission')
            ->with('valid-token') // Verify the handler passes the correct data
            ->willReturn(true);

        $permissionHandler = new PermissionHandler($permissionService);

        // Mock Request & Params
        $routeParameters = $this->createMock(RouteParameters::class);
        $routeParameters->method('get')->with('token')->willReturn('valid-token');

        // Execute
        $response = $permissionHandler->__invoke(
            $this->createMock(ServerRequestInterface::class),
            $routeParameters
        );

        // Assert: Focus on HTTP translation
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['permission' => true]),
            $response->getContent()
        );
    }

    /**
     * Verifies that the handler returns 400 if the token parameter is missing.
     *
     * Note: While the current routing configuration might prevent a null token,
     * this test ensures the handler's contract remains robust (Defense in Depth).
     */
    public function testReturnsBadRequestIfTokenIsMissing(): void
    {
        $service = $this->createMock(PermissionService::class);
        $permissionHandler = new PermissionHandler($service);

        $routeParameters = $this->createMock(RouteParameters::class);
        $routeParameters->method('get')->with('token')->willReturn(null);

        $response = $permissionHandler->__invoke(
            $this->createMock(ServerRequestInterface::class),
            $routeParameters
        );

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getCode());
    }
}

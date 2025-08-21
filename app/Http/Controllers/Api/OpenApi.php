<?php

namespace App\Http\Controllers\Api;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Web24 API",
    description: "API for managing companies and employees"
)]
#[OA\Server(
    url: "http://web24api.devel/api/v1",
    description: "Development API Server"
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    description: "Bearer token authentication via Sanctum"
)]
class OpenApi {}
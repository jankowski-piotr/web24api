<?php

namespace App\Http\Schemas;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "PaginationLinks",
    properties: [
        new OA\Property(property: "first", type: "string", format: "uri", nullable: true),
        new OA\Property(property: "last", type: "string", format: "uri", nullable: true),
        new OA\Property(property: "prev", type: "string", format: "uri", nullable: true),
        new OA\Property(property: "next", type: "string", format: "uri", nullable: true),
    ]
)]
class PaginationLinks {}

#[OA\Schema(
    schema: "PaginationMeta",
    properties: [
        new OA\Property(property: "current_page", type: "integer", example: 1),
        new OA\Property(property: "from", type: "integer", nullable: true, example: 1),
        new OA\Property(property: "last_page", type: "integer", example: 10),
        new OA\Property(property: "links", type: "array", items: new OA\Items(
            properties: [
                new OA\Property(property: "url", type: "string", format: "uri", nullable: true),
                new OA\Property(property: "label", type: "string"),
                new OA\Property(property: "active", type: "boolean"),
            ]
        )),
        new OA\Property(property: "path", type: "string", format: "uri"),
        new OA\Property(property: "per_page", type: "integer", example: 15),
        new OA\Property(property: "to", type: "integer", nullable: true, example: 15),
        new OA\Property(property: "total", type: "integer", example: 100),
    ]
)]
class PaginationMeta {}
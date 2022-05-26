<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class AuthenticatedUserResource extends JsonResource
{
    public string $accessToken;

    /**
     * override resource constructor to accommodate $accessToken
     *
     * @param $resource
     * @param string $accessToken
     */
    public function __construct($resource, string $accessToken)
    {
        parent::__construct($resource);
        $this->accessToken = $accessToken;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request = null): array
    {
        return [
            'user' => $this->resource->toArray(),
            'access_token' => $this->accessToken,
        ];
    }
}

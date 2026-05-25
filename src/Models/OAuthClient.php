<?php

namespace TrackAnyDevice\Admin\Models;

use TrackAnyDevice\Core\Concerns\UsesCentralConnection;
use TrackAnyDevice\Core\Enums\OAuthClientKind;
use TrackAnyDevice\Core\Models\Tenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OAuthClient extends Model
{
    use HasUuids, UsesCentralConnection;

    protected $table = 'oauth_clients';

    protected $fillable = [
        'name',
        'kind',
        'tenant_id',
        'client_id',
        'client_secret_hash',
        'redirect_uris',
        'logout_webhook_url',
        'is_active',
        'user_id',
        'personal_access_client',
        'password_client',
        'revoked',
    ];

    protected function casts(): array
    {
        return [
            'kind'                   => OAuthClientKind::class,
            'redirect_uris'          => 'array',
            'is_active'              => 'boolean',
            'personal_access_client' => 'boolean',
            'password_client'        => 'boolean',
            'revoked'                => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

<?php

namespace App\Services\Spotify;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SpotifyTrackCatalog
{
    /**
     * @return array{
     *     id: string,
     *     title: string,
     *     artists: list<string>,
     *     album: string|null,
     *     image_url: string|null,
     *     duration_ms: int|null,
     *     spotify_url: string
     * }|null
     */
    public function fetchTrackFromUrl(?string $spotifyUrl): ?array
    {
        if (! is_string($spotifyUrl) || trim($spotifyUrl) === '') {
            return null;
        }

        $trackId = $this->extractTrackId($spotifyUrl);
        if ($trackId === null) {
            return null;
        }

        $accessToken = $this->accessToken();
        if ($accessToken === null) {
            return null;
        }

        $response = Http::baseUrl('https://api.spotify.com/v1')
            ->acceptJson()
            ->withToken($accessToken)
            ->get("tracks/{$trackId}");

        if (! $response->successful()) {
            return null;
        }

        /** @var array<string, mixed> $payload */
        $payload = $response->json();

        $id = $payload['id'] ?? null;
        $title = $payload['name'] ?? null;

        if (! is_string($id) || $id === '' || ! is_string($title) || $title === '') {
            return null;
        }

        $artists = collect($payload['artists'] ?? [])
            ->map(fn (mixed $artist): ?string => is_array($artist) && is_string($artist['name'] ?? null)
                ? $artist['name']
                : null)
            ->filter(fn (?string $artist): bool => $artist !== null && $artist !== '')
            ->values()
            ->all();

        $album = is_array($payload['album'] ?? null) ? $payload['album'] : [];
        $albumName = is_string($album['name'] ?? null) ? $album['name'] : null;
        $durationMs = is_int($payload['duration_ms'] ?? null) ? $payload['duration_ms'] : null;
        $spotifyExternalUrl = is_array($payload['external_urls'] ?? null)
            && is_string($payload['external_urls']['spotify'] ?? null)
            ? $payload['external_urls']['spotify']
            : $spotifyUrl;

        $imageUrl = null;
        foreach ($album['images'] ?? [] as $image) {
            if (is_array($image) && is_string($image['url'] ?? null) && $image['url'] !== '') {
                $imageUrl = $image['url'];
                break;
            }
        }

        return [
            'id' => $id,
            'title' => $title,
            'artists' => $artists,
            'album' => $albumName,
            'image_url' => $imageUrl,
            'duration_ms' => $durationMs,
            'spotify_url' => $spotifyExternalUrl,
        ];
    }

    private function accessToken(): ?string
    {
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        if (! is_string($clientId) || $clientId === '' || ! is_string($clientSecret) || $clientSecret === '') {
            return null;
        }

        $cacheKey = "spotify.client-credentials-token.{$clientId}";
        $cachedToken = Cache::get($cacheKey);

        if (is_string($cachedToken) && $cachedToken !== '') {
            return $cachedToken;
        }

        $response = Http::asForm()
            ->acceptJson()
            ->withBasicAuth($clientId, $clientSecret)
            ->post('https://accounts.spotify.com/api/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            return null;
        }

        $accessToken = $response->json('access_token');
        $expiresIn = $response->json('expires_in');

        if (! is_string($accessToken) || $accessToken === '') {
            return null;
        }

        $ttlSeconds = is_int($expiresIn) ? max(60, $expiresIn - 60) : 3540;
        Cache::put($cacheKey, $accessToken, now()->addSeconds($ttlSeconds));

        return $accessToken;
    }

    private function extractTrackId(string $spotifyUrl): ?string
    {
        $spotifyUrl = trim($spotifyUrl);

        if (preg_match('/^spotify:track:([A-Za-z0-9]+)$/', $spotifyUrl, $matches) === 1) {
            return $matches[1];
        }

        $parts = parse_url($spotifyUrl);
        if ($parts === false) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        if ($host === 'www.open.spotify.com') {
            $host = 'open.spotify.com';
        }

        if ($host !== 'open.spotify.com') {
            return null;
        }

        $path = trim($parts['path'] ?? '', '/');
        $segments = $path === '' ? [] : explode('/', $path);

        if (($segments[0] ?? null) !== 'track') {
            return null;
        }

        $trackId = $segments[1] ?? null;

        return is_string($trackId) && preg_match('/^[A-Za-z0-9]+$/', $trackId) === 1
            ? $trackId
            : null;
    }
}

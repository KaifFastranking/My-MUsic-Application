<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SpotifyController extends Controller
{
    private $client_id;
    private $client_secret;
    private $access_token;

    public function __construct()
    {
        $this->client_id = env('SPOTIFY_CLIENT_ID');
        $this->client_secret = env('SPOTIFY_CLIENT_SECRET');
        $this->getAccessToken();
    }

    // Get Access Token from Spotify
    private function getAccessToken()
    {
        $response = Http::asForm()->post('https://accounts.spotify.com/api/token', [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
        ]);

        $this->access_token = $response->json()['access_token'];
    }

    public function welcome(Request $request)
    {
        $query = $request->input('query');
        $tracks = [];
        $artistIds = [];

        if ($query) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->access_token,
            ])->get('https://api.spotify.com/v1/search', [
                'q' => $query,
                'type' => 'track',
                'limit' => 10,
            ]);
            $tracks = $response->json()['tracks']['items'] ?? [];
            foreach ($tracks as $track) {
                foreach ($track['artists'] as $artist) {
                    $artistIds[] = $artist['id'];
                }
            }
            $artistIds = array_unique($artistIds);
        } else {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->access_token,
            ])->get("https://api.spotify.com/v1/artists?ids=4YRxDV8wJFPHPTeXepOstw,1wRPtKGflJrBx9BmLsSwlU,6KImCVD70vtIoJWnq6nGn3");
            $artists = $response->json()['artists'] ?? [];
            $artistIds = array_column($artists, 'id');
        }

        foreach ($artistIds as $artistId) {
            $artistTracks = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->access_token,
            ])->get("https://api.spotify.com/v1/artists/{$artistId}/top-tracks", [
                'market' => 'IN'
            ]);
            $tracks = array_merge($tracks, $artistTracks->json()['tracks'] ?? []);
        }

        $defaultTrackId = null;
        if (!empty($tracks)) {
            $randomTrack = $tracks[array_rand($tracks)];
            $defaultTrackId = $randomTrack['id'] ?? null;
        }

        return view('welcome', [
            'tracks' => $tracks,
            'defaultTrackId' => $defaultTrackId
        ]);
    }
}

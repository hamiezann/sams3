<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller
{
    public function revokeAccess()
    {
        $user = auth()->user();

        // Clear tokens from database
        $user->google_token = null;
        $user->google_refresh_token = null;
        $user->google_token_expires_at = null;
        $user->save();

        // Redirect to Google to revoke access (optional)
        return redirect('https://myaccount.google.com/permissions')
            ->with('info', 'Please revoke access to our application in your Google account, then try connecting again.');
    }

    public function getClient()
    {
        $client = new \Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect'));
        $client->addScope(\Google_Service_Calendar::CALENDAR);

        // These settings are critical for getting a refresh token
        $client->setAccessType('offline');
        $client->setPrompt('consent select_account');  // Force consent screen and account selection

        return $client;
    }

    public function redirectToGoogle()
    {
        $client = $this->getClient();
        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = $this->getClient();

        try {
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

            // Check for errors
            if (isset($token['error'])) {
                Log::error('Google Auth Error:', $token);
                return redirect()->route('home')->with('error', 'Google Auth failed: ' . $token['error_description']);
            }

            // Debug the token response
            Log::info('Google token response:', [
                'has_access_token' => isset($token['access_token']),
                'has_refresh_token' => isset($token['refresh_token']),
                'expires_in' => $token['expires_in'] ?? 'not set'
            ]);

            // Save token to database
            $user = Auth::user();
            $user->google_token = $token['access_token'];
            $user->google_refresh_token = $token['refresh_token'] ?? null;
            $user->google_token_expires_at = now()->addSeconds($token['expires_in']);
            $user->save();

            return redirect()->route('home')->with('success', 'Google calendar connected successfully.');
        } catch (\Exception $e) {
            Log::error('Google callback error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Failed to connect Google Calendar: ' . $e->getMessage());
        }
    }

    public function createCalendarEvent($title, $description, $location, $startDateTime, $endDateTime)
    {
        $user = auth()->user();

        if (!$user || !$user->google_token) {
            throw new \Exception('Google token not found. Please connect to Google Calendar first.');
        }

        $client = $this->getClient();
        $client->setAccessToken($user->google_token);

        // Check if token is expired
        if ($client->isAccessTokenExpired()) {
            Log::info('Google token expired', [
                'user_id' => $user->id,
                'has_refresh_token' => !empty($user->google_refresh_token)
            ]);

            // Try to refresh the token if we have a refresh token
            if ($user->google_refresh_token) {
                try {
                    $client->fetchAccessTokenWithRefreshToken($user->google_refresh_token);
                    $token = $client->getAccessToken();

                    // Update the token in the database
                    $user->google_token = $token['access_token'];
                    $user->google_token_expires_at = now()->addSeconds($token['expires_in']);
                    $user->save();

                    Log::info('Successfully refreshed Google token');
                } catch (\Exception $e) {
                    Log::error('Failed to refresh token: ' . $e->getMessage());
                    throw new \Exception('Failed to refresh Google token. Please reconnect to Google Calendar.');
                }
            } else {
                throw new \Exception('Google token expired and no refresh token available. Please reconnect to Google Calendar.');
            }
        }

        $service = new \Google_Service_Calendar($client);

        $event = new \Google_Service_Calendar_Event([
            'summary'     => $title,
            'location'    => $location,
            'description' => $description,
            'start' => [
                'dateTime' => Carbon::parse($startDateTime)->toRfc3339String(),
                'timeZone' => 'Asia/Kuala_Lumpur',
            ],
            'end' => [
                'dateTime' => Carbon::parse($endDateTime)->toRfc3339String(),
                'timeZone' => 'Asia/Kuala_Lumpur',
            ],
        ]);

        $calendarId = 'primary';
        return $service->events->insert($calendarId, $event);
    }

    public function connectToGoogle()
    {
        $client = $this->getClient();

        // You can modify this logic to check if you want to re-auth or not
        if (!auth()->user()->google_token) {
            // First-time connection
            $client->setAccessType('offline');
            $client->setPrompt('consent select_account');
        } else {
            // Reconnect flow (you can customize this part)
            $client->addScope('https://www.googleapis.com/auth/calendar');
            $client->setPrompt('consent');
        }

        $authUrl = $client->createAuthUrl();

        return redirect()->away($authUrl);
    }

    public function createCalendarEventWithServiceAccount($title, $description, $location, $startDateTime, $endDateTime)
    {
        $client = new \Google_Client();

        // Set up service account
        $client->setAuthConfig(storage_path('app/google-service-account.json'));
        $client->setScopes([\Google_Service_Calendar::CALENDAR]);
        $client->setSubject('your-admin-email@your-domain.com'); // The user to impersonate

        $service = new \Google_Service_Calendar($client);

        $event = new \Google_Service_Calendar_Event([
            'summary'     => $title,
            'location'    => $location,
            'description' => $description,
            'start' => [
                'dateTime' => $startDateTime,
                'timeZone' => 'Asia/Kuala_Lumpur',
            ],
            'end' => [
                'dateTime' => $endDateTime,
                'timeZone' => 'Asia/Kuala_Lumpur',
            ],
        ]);

        $calendarId = 'primary'; // or the specific calendar ID
        return $service->events->insert($calendarId, $event);
    }
}

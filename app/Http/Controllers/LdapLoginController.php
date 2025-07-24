<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;
use LdapRecord\Connection;
use LdapRecord\Auth\BindException;

class LdapLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $upn = $email;

        $connection = new Connection([
            'hosts' => [env('LDAP_HOST')],
            'base_dn' => env('LDAP_BASE_DN'),
            'port' => env('LDAP_PORT', 389),
            'use_ssl' => env('LDAP_SSL', false),
            'use_tls' => env('LDAP_TLS', false),
        ]);

        try {
            $connection->connect();
            $connection->auth()->bind($upn, $password);

            $rawLdap = $connection->getLdapConnection()->getConnection();
            $dn = env('LDAP_BASE_DN');
            $filter = "(userPrincipalName={$upn})";
            $attributes = ['displayName', 'mail', 'department', 'distinguishedName','company','title'];
            
            $search = @ldap_search($rawLdap, $dn, $filter, $attributes);

            if (!$search) {
                return back()->withErrors(['ldap' => 'LDAP search error.']);
            }

            $entries = ldap_get_entries($rawLdap, $search);
            if ($entries['count'] === 0) {
                return back()->withErrors(['ldap' => 'Pengguna tidak ditemukan di LDAP.']);
            }

            $entry = $entries[0];

            $user = User::firstOrCreate([
                'email' => $email,
            ], [
                'name'       => $entry['displayname'][0] ?? $email,
                'username'   => explode('@', $email)[0], // opsional
                'department' => $entry['department'][0] ?? null,
                'password'   => bcrypt(str()->random(12)),
                'phone'      => '0',
            ]);

            Auth::login($user);

            return redirect()->route('dashboard.index')->with('success', 'Berhasil login sebagai ' . $user->name);
        } catch (\LdapRecord\Auth\BindException $e) {
            $error = $e->getDetailedError();
            return back()->withErrors([
                'ldap' => "Gagal login: {$error->getErrorCode()} - {$error->getDiagnosticMessage()}",
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

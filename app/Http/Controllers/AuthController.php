<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if ($request->session()->has('user')) {
            return redirect()->route(
                $this->defaultRouteForRole((int) data_get($request->session()->get('user'), 'role', 0))
            );
        }

        return view('login', [
            'recaptchaSiteKey' => config('services.recaptcha.site_key'),
            'mathQuestion' => $this->generateMathChallenge($request),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'captcha_mode' => ['nullable', 'in:recaptcha,math'],
        ]);

        $this->validateCaptcha($request);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return back()
                ->withErrors(['email' => 'Email atau password tidak sesuai.'])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('user', [
            'id_user' => $user->id_user,
            'username' => $user->username,
            'email' => $user->email,
            'role' => (int) $user->role,
        ]);

        return redirect()->route($this->defaultRouteForRole((int) $user->role));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function defaultRouteForRole(int $role): string
    {
        return match ($role) {
            1, 2, 3, 4 => 'home',
            default => 'login',
        };
    }

    private function generateMathChallenge(Request $request): string
    {
        $left = random_int(1, 20);
        $right = random_int(1, 20);
        $operator = random_int(0, 1) === 0 ? '+' : '-';

        if ($operator === '-' && $left < $right) {
            [$left, $right] = [$right, $left];
        }

        $answer = $operator === '+' ? $left + $right : $left - $right;

        $request->session()->put('login_math_captcha', [
            'question' => "{$left} {$operator} {$right}",
            'answer' => (string) $answer,
        ]);

        return "{$left} {$operator} {$right}";
    }

    private function validateCaptcha(Request $request): void
    {
        $mode = $request->input('captcha_mode', 'math');

        if ($mode === 'recaptcha' && filled(config('services.recaptcha.secret_key'))) {
            $token = $request->input('g-recaptcha-response');

            if (! filled($token)) {
                throw ValidationException::withMessages([
                    'captcha' => 'reCAPTCHA verification is required.',
                ]);
            }

            try {
                $response = Http::asForm()
                    ->timeout(5)
                    ->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => config('services.recaptcha.secret_key'),
                        'response' => $token,
                        'remoteip' => $request->ip(),
                    ]);

                if (data_get($response->json(), 'success') === true) {
                    return;
                }
            } catch (\Throwable $exception) {
                throw ValidationException::withMessages([
                    'captcha' => 'reCAPTCHA is unavailable right now. Reload the page and use the math question fallback.',
                ]);
            }

            throw ValidationException::withMessages([
                'captcha' => 'reCAPTCHA verification failed.',
            ]);
        }

        $request->validate([
            'math_answer' => ['required'],
        ], [
            'math_answer.required' => 'Math answer is required.',
        ]);

        $expected = (string) data_get($request->session()->get('login_math_captcha'), 'answer', '');
        $provided = trim((string) $request->input('math_answer'));

        if ($expected === '' || $provided !== $expected) {
            throw ValidationException::withMessages([
                'math_answer' => 'Math answer is incorrect.',
            ]);
        }
    }
}

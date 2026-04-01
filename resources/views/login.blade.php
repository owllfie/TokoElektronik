<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Toko Elektronik | Login</title>
    <style>
        :root {
            --ink: #1a1a1a;
            --muted: #6b6b6b;
            --accent: #ff914d;
            --accent-2: #1f5a5f;
            --panel: #fff;
            --shadow: rgba(0, 0, 0, 0.18);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Calibri", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 10% 20%, rgba(31, 90, 95, 0.2), transparent 40%),
                radial-gradient(circle at 90% 10%, rgba(255, 145, 77, 0.2), transparent 40%),
                linear-gradient(140deg, #fef6ef 0%, #f8f3ff 45%, #eef8f6 100%);
            color: var(--ink);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .frame {
            width: min(960px, 100%);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 28px;
            align-items: stretch;
        }

        .poster {
            background: linear-gradient(135deg, rgba(31, 90, 95, 0.85), rgba(14, 45, 48, 0.95));
            border-radius: 24px;
            color: #fff;
            padding: 32px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: 0 24px 50px rgba(0, 0, 0, 0.22);
        }

        .poster h1 {
            font-size: clamp(1.9rem, 2.8vw, 2.6rem);
            margin: 0 0 12px;
        }

        .poster p {
            margin: 0 0 20px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .poster .badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.15);
            font-size: 14px;
        }

        .card {
            background: var(--panel);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 18px 40px var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.06);
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .card h2 {
            margin: 0;
            font-size: 1.7rem;
        }

        .card p {
            margin: 0;
            color: var(--muted);
        }

        label {
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid rgba(0, 0, 0, 0.15);
            font-size: 16px;
            font-family: "Calibri", "Segoe UI", sans-serif;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
        }

        .button {
            padding: 12px 18px;
            border-radius: 12px;
            border: none;
            background: var(--accent);
            color: #3b1e0a;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(255, 145, 77, 0.35);
        }

        .link {
            color: var(--accent-2);
            text-decoration: none;
            font-weight: 600;
        }

        .switch {
            padding: 14px;
            border-radius: 16px;
            background: rgba(31, 90, 95, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 14px;
        }

        .challenge {
            padding: 14px;
            border-radius: 16px;
            background: rgba(31, 90, 95, 0.06);
            display: grid;
            gap: 10px;
        }

        .challenge.hidden {
            display: none;
        }

        .challenge-label {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--accent-2);
        }

        .challenge-question {
            font-size: 18px;
            font-weight: 700;
            color: var(--ink);
        }

        .captcha-error {
            color: #b42318;
            font-size: 13px;
        }

        .captcha-status {
            color: var(--muted);
            font-size: 13px;
            margin: 0;
        }

        @media (max-width: 720px) {
            .poster {
                order: 2;
            }
        }
    </style>
</head>
<body>
    <div class="frame">
        <section class="poster">
            <div>
                <h1>Welcome back to Electro.</h1>
                <p>
                    Sign in to monitor store activity, coordinate restocks, and check monthly reports.
                    Your retail cockpit is waiting.
                </p>
            </div>
            <div>
                <strong>Need help?</strong>
                <p>Contact admins for access or reset.</p>
            </div>
        </section>

        <form class="card" method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div>
                <h2>Login</h2>
                <p>Use your assigned credentials to continue.</p>
            </div>
            <div>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="name@tokoelektronik.com" value="{{ old('email') }}" required />
                @error('email')
                    <small style="color: #b42318;">{{ $message }}</small>
                @enderror
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="********" required />
                @error('password')
                    <small style="color: #b42318;">{{ $message }}</small>
                @enderror
            </div>
            <input type="hidden" name="captcha_mode" id="captchaMode" value="{{ old('captcha_mode', 'math') }}" />
            <div class="challenge" id="captchaStatusBox">
                <span class="challenge-label">Security Check</span>
                <p class="captcha-status" id="captchaStatus">Checking internet connection for reCAPTCHA...</p>
                @error('captcha')
                    <small class="captcha-error">{{ $message }}</small>
                @enderror
            </div>
            <div class="challenge hidden" id="recaptchaChallenge">
                <span class="challenge-label">reCAPTCHA</span>
                <div id="recaptchaContainer"></div>
            </div>
            <div class="challenge hidden" id="mathChallenge">
                <span class="challenge-label">Math Question</span>
                <div class="challenge-question">{{ $mathQuestion }}</div>
                <div>
                    <label for="math_answer">Answer</label>
                    <input id="math_answer" name="math_answer" type="text" inputmode="numeric" value="{{ old('math_answer') }}" autocomplete="off" />
                    @error('math_answer')
                        <small class="captcha-error">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="actions">
                <button class="button" type="submit">Sign In</button>
            </div>
        </form>
    </div>
    <script>
        const captchaModeInput = document.getElementById('captchaMode');
        const captchaStatus = document.getElementById('captchaStatus');
        const recaptchaChallenge = document.getElementById('recaptchaChallenge');
        const mathChallenge = document.getElementById('mathChallenge');
        const mathAnswerInput = document.getElementById('math_answer');
        const recaptchaSiteKey = @json($recaptchaSiteKey);
        let recaptchaWidgetId = null;

        const useMathFallback = (message) => {
            captchaModeInput.value = 'math';
            mathChallenge.classList.remove('hidden');
            recaptchaChallenge.classList.add('hidden');
            captchaStatus.textContent = message;
            if (mathAnswerInput) {
                mathAnswerInput.required = true;
            }
        };

        const useRecaptcha = () => {
            if (!window.grecaptcha || !recaptchaSiteKey) {
                useMathFallback('reCAPTCHA is unavailable. Solve the math question to continue.');
                return;
            }

            captchaModeInput.value = 'recaptcha';
            recaptchaChallenge.classList.remove('hidden');
            mathChallenge.classList.add('hidden');
            if (mathAnswerInput) {
                mathAnswerInput.required = false;
                mathAnswerInput.value = '';
            }
            captchaStatus.textContent = 'reCAPTCHA is active.';

            if (recaptchaWidgetId === null) {
                recaptchaWidgetId = window.grecaptcha.render('recaptchaContainer', {
                    sitekey: recaptchaSiteKey,
                });
            }
        };

        window.onRecaptchaLoaded = () => {
            useRecaptcha();
        };

        const loadRecaptcha = () => {
            if (!recaptchaSiteKey || !navigator.onLine) {
                useMathFallback('No internet detected. Solve the math question to continue.');
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoaded&render=explicit';
            script.async = true;
            script.defer = true;
            script.onerror = () => {
                useMathFallback('reCAPTCHA could not be loaded. Solve the math question to continue.');
            };
            document.head.appendChild(script);
        };

        loadRecaptcha();
        window.addEventListener('offline', () => {
            useMathFallback('Internet connection lost. Solve the math question to continue.');
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.app_name') }} - {{ __('messages.login') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}

        body {
            font-family: 'Poppins', sans-serif;
            background: #050505;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        /* Background blobs */
        .blob{position:fixed;border-radius:50%;pointer-events:none}
        .blob-pink{
            width:500px;height:500px;
            background:rgba(236,72,153,.2);
            filter:blur(120px);
            top:-120px;left:-120px;
        }
        .blob-purple{
            width:400px;height:400px;
            background:rgba(214,0,123,.2);
            filter:blur(120px);
            bottom:-120px;right:-120px;
        }

        /* Card */
        .card{
            position:relative;z-index:10;
            width:100%;max-width:420px;
            padding:40px 36px;
            border-radius:24px;
            background:rgba(255,255,255,.05);
            backdrop-filter:blur(24px) saturate(1.2);
            border:1px solid rgba(255,255,255,.1);
            box-shadow:0 10px 40px rgba(255,0,204,.15);
            text-align:center;
            transition:all .3s;
        }

        /* Truck icon */
        .truck-icon{
            width:56px;height:56px;
            margin:0 auto 20px;
            background:linear-gradient(135deg,#ec4899,#a855f7);
            border-radius:16px;
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 8px 30px rgba(236,72,153,.35);
        }
        .truck-icon svg{width:28px;height:28px;color:#fff}

        /* Title */
        .title{font-size:1.75rem;font-weight:700;letter-spacing:-.03em;color:#fff;margin-bottom:4px}
        .title span{color:#ec4899}
        .sub{color:rgba(255,255,255,.4);font-size:.85rem;margin-bottom:32px}

        /* PIN inputs */
        .pin-row{display:flex;justify-content:space-between;gap:8px;width:100%}
        .pin{
            width:100%;max-width:52px;height:60px;
            border-radius:12px;text-align:center;
            font-size:20px;font-weight:600;
            font-family:'Poppins',sans-serif;
            background:rgba(255,255,255,.05);
            border:1px solid rgba(255,255,255,.1);
            color:#fff;
            transition:all .25s ease;
            outline:none;
        }
        .pin:focus{
            border-color:#ff00cc;
            box-shadow:0 0 15px rgba(255,0,204,.3);
            transform:translateY(-2px);
        }
        .pin.filled{border-color:rgba(236,72,153,.4);background:rgba(236,72,153,.05)}

        /* Error */
        .err{
            background:rgba(239,68,68,.08);
            border:1px solid rgba(239,68,68,.25);
            border-radius:12px;padding:12px 16px;
            margin-bottom:20px;display:flex;align-items:center;
            gap:10px;color:#ef4444;font-size:.82rem;
            animation:shake .4s ease;
        }
        @keyframes shake{0%,100%{transform:translateX(0)}20%,60%{transform:translateX(-5px)}40%,80%{transform:translateX(5px)}}

        /* Footer */
        .footer{
            margin-top:32px;padding-top:24px;
            border-top:1px solid rgba(255,255,255,.05);
            text-align:center;
        }
        .footer p{
            font-size:.68rem;text-transform:uppercase;
            letter-spacing:.15em;color:rgba(255,255,255,.25);
        }

        @media(max-width:768px){
            .card{padding:32px 20px}
            .pin{max-width:48px;height:52px;font-size:18px}
        }
    </style>
</head>
<body>
    <div class="blob blob-pink"></div>
    <div class="blob blob-purple"></div>

    <div class="card">
        <!-- Truck Icon -->
        <div class="truck-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 3h15v13H1z"/>
                <path d="M16 8h4l3 3v5h-7V8z"/>
                <circle cx="5.5" cy="18.5" r="2.5"/>
                <circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
        </div>

        <!-- Title -->
        <h1 class="title">{{ __('messages.app_name') }}</h1>
        <p class="sub">{{ __('messages.login_title') }}</p>

        <!-- Error -->
        @if($errors->any())
        <div class="err">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $errors->first() }}
        </div>
        @endif
        @if(session('error'))
        <div class="err">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" id="pin-form">
            @csrf
            <div class="pin-row">
                <input type="password" maxlength="1" class="pin" data-i="0" required inputmode="numeric" pattern="[0-9]*" autofocus>
                <input type="password" maxlength="1" class="pin" data-i="1" required inputmode="numeric" pattern="[0-9]*">
                <input type="password" maxlength="1" class="pin" data-i="2" required inputmode="numeric" pattern="[0-9]*">
                <input type="password" maxlength="1" class="pin" data-i="3" required inputmode="numeric" pattern="[0-9]*">
            </div>
            <input type="hidden" name="pin" id="pinHidden">
        </form>

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('messages.delivery_management_system') }}</p>
        </div>
    </div>

    <script>
    const inputs = document.querySelectorAll('.pin');
    const form = document.getElementById('pin-form');
    const hidden = document.getElementById('pinHidden');

    function submitPin() {
        hidden.value = [...inputs].map(i => i.value).join('');
        form.submit();
    }

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            const allFilled = [...inputs].every(i => i.value.length === 1);
            if (allFilled) submitPin();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    form.addEventListener('paste', (e) => {
        const paste = (e.clipboardData || window.clipboardData).getData('text');
        if (/^\d{4}$/.test(paste)) {
            e.preventDefault();
            paste.split('').forEach((char, i) => {
                if (inputs[i]) inputs[i].value = char;
            });
            submitPin();
        }
    });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>IFRAN PRÉSENCE</title>
  <style>
    body {
      background: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }

    h1 {
      color: black;
      font-size: 3rem;
      border-right: .15em solid black;
      white-space: nowrap;
      overflow: hidden;
      animation: typing 3s steps(14, end), blink .75s step-end infinite;
    }

    @keyframes typing {
      from { max-width: 0 }
      to { max-width: 100% }
    }

    @keyframes blink {
      50% { border-color: transparent }
    }

    .btn-login {
      margin-top: 40px;
      padding: 12px 24px;
      font-size: 1.2rem;
      border: 2px solid black;
      background: transparent;
      color: black;
      cursor: pointer;
      border-radius: 8px;
      transition: 0.3s;
      text-decoration: none;

    }

    .btn-login:hover {
      background: black;
      color: white;
    }
  </style>
</head>
<body>
  <h1>IFRAN PRÉSENCE</h1>
    <a
        href="{{ route('login') }}"
        onclick="console.log('Redirection vers login...')"
        class="btn-login">Log in
    </a>
    <div id="debug-info" style="margin-top: 20px; font-size: 12px; color: #666;"></div>
    <script>
        document.querySelector('.btn-login').addEventListener('click', function(e) {
            document.getElementById('debug-info').textContent = 'Tentative de redirection...';
        });
    </script>
</body>
</html>

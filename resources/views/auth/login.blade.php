<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso - Sistema Escolar</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/css/main.css') }}">
</head>
<body>
    <div id="main-container">
        <div id="left-container">
            <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
                @csrf 
                
                <div id="logo-container">
                    <img src="{{ asset('backend/img/favicon.png') }}" width="400" alt="Logo">
                </div>

                <div id="title-container">
                    <h2>Bienvenidos</h2>
                </div>

                @if($errors->any())
                    <div style="color: red; margin-bottom: 10px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div id="inputs-container">
                    <label for="username">Usuario</label>
                    <input type="text" name="username" placeholder="Ingrese nombre de usuario" value="{{ old('username') }}" required>

                    <label for="password">Contraseña</label>
                    <input type="password" name="password" placeholder="Contraseña" required>

                    <div id="options-container">
                        <div>
                            <input type="checkbox" name="remember" id="stay_connected"> Conectado
                        </div>
                        <div class="right"><a href="#">Olvidé mi contraseña</a></div>
                    </div>

                    <div id="buttons-container">
                        <button type="submit" class="btn">Ingresar</button>
                    </div>
                </div>

                <div id="register-container">
                    <span>¿No tienes cuenta? </span> <a href="#">Regístrate</a>
                </div>
            </form>
        </div>
        <div id="right-container"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if($errors->first() == 'Cuenta bloqueada temporalmente. Contacte al administrador.')
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Cuenta bloqueada',
        html: `
            Debe comunicarse con secretaria de la I.E .<br><br>
            <b>Horario:</b> Lunes a Viernes 8:00am - 5:00pm<br>
            <b>Teléfono:</b> 925-302-476
        `
    });
    </script>
    @endif

</html>
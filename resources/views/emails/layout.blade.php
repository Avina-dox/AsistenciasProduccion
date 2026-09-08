<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Notificación')</title>
    <style>
        @media only screen and (max-width: 620px) {
            .email-container {
                width: 100% !important;
            }

            .email-padding {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }

            .stack-cell {
                display: block !important;
                width: 100% !important;
                padding-top: 2px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background-color:#F3EDE3;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#F3EDE3;">
        <tr>
            <td align="center" style="padding:32px 12px;">

                <table role="presentation" class="email-container" width="600" cellpadding="0" cellspacing="0" border="0"
                    style="width:600px; max-width:600px; background-color:#ffffff; border-radius:16px; overflow:hidden; font-family:'Segoe UI', Arial, Helvetica, sans-serif;">

                    {{-- Encabezado --}}
                    <tr>
                        <td style="background-color:#45193F; background-image:linear-gradient(135deg,#6A2C75,#45193F); padding:26px 32px;" class="email-padding">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="font-size:21px; font-weight:bold; color:#ffffff; letter-spacing:.5px;">
                                        DASAVENA <span style="color:#D6A644;">GOURMET</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:11px; color:#E4D9A0; text-transform:uppercase; letter-spacing:2px; padding-top:4px;">
                                        Sistema de Asistencias &middot; DasAsistencias
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Título --}}
                    <tr>
                        <td style="padding:26px 32px 6px;" class="email-padding">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="font-size:22px; vertical-align:middle;">
                                        @yield('icono', '📋')
                                    </td>
                                    <td style="padding-left:10px; font-size:19px; font-weight:700; color:#2B2030; vertical-align:middle;">
                                        @yield('titulo', 'Notificación')
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Contenido --}}
                    <tr>
                        <td style="padding:10px 32px 28px;" class="email-padding">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Pie --}}
                    <tr>
                        <td style="background-color:#FBF8F3; border-top:1px solid rgba(43,32,48,0.08); padding:18px 32px;" class="email-padding">
                            <p style="margin:0; font-size:12px; line-height:1.6; color:#6E6274;">
                                Este correo fue generado automáticamente por
                                <strong style="color:#6A2C75;">DasAsistencias</strong>,
                                el sistema de control de asistencias de Dasavena Gourmet.
                                <br>
                                Por favor no respondas directamente a este mensaje.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>

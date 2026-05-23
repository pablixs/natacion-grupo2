<?php
require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function sendEmailResetPassword($toEmail, $token)
    {

        $colorPrincipal = '#007bff';
        $colorFondo = '#f4f7f9';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = Env::get('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = Env::get('MAIL_USERNAME');
            $mail->Password   = Env::get('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = Env::get('MAIL_PORT');

            $mail->setFrom(Env::get('MAIL_FROM'), 'Soporte Escuela de Natación');
            //$mail->setFrom( 'lic.juanpablocesarini@gmail.com', 'Escuela de Natación' );
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperación de contraseña';

            $baseUrl = Env::get('APP_URL');
            $resetLink = rtrim($baseUrl, '/') . '/index.php?url=reset-password&token=' . $token;

            // Armamos el Body con un formato más robusto
            $mail->Body = "
                 <div style='background-color: {$colorFondo}; padding: 40px; font-family: Arial, sans-serif; line-height: 1.6;'>
        <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e1e8ed;'>
            
            <div style='background-color: {$colorPrincipal}; padding: 20px; text-align: center;'>
                <h1 style='color: #ffffff; margin: 0; font-size: 24px;'>Escuela de Natación</h1>
            </div>

            <div style='padding: 30px; text-align: center;'>
                <h2 style='color: #333333;'>¿Olvidaste tu contraseña?</h2>
                <p style='color: #666666; font-size: 16px;'>
                    No te preocupes, nos pasa a todos. Haz clic en el botón de abajo para elegir una nueva clave y volver al agua.
                </p>
                
                <div style='margin: 30px 0;'>
                    <a href='{$resetLink}' style='background-color: {$colorPrincipal}; color: #ffffff; padding: 15px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>
                        Restablecer Contraseña
                    </a>
                </div>

                <p style='color: #999999; font-size: 12px; margin-top: 30px;'>
                    Si no solicitaste este cambio, puedes ignorar este correo con seguridad. 
                    El enlace expirará automáticamente en 1 hora.
                </p>
            </div>

            <div style='background-color: #f8f9fa; padding: 15px; text-align: center; border-top: 1px solid #eeeeee;'>
                <p style='color: #aaaaaa; font-size: 11px; margin: 0;'>
                    © " . date('Y') . " Escuela de Natación - Panel Administrativo
                </p>
            </div>
        </div> </div>";

            // $mail->SMTPDebug = 3;
            // Nivel 3 es más detallado
            //   $mail->Debugoutput = 'html';
            // Para que se vea bien en el navegador
            $mail->send();
            return true;
        } catch (Exception $e) {
            //error_log( $e->getMessage() );
            echo 'Error de PHPMailer: ' . $mail->ErrorInfo;
            die();
            return false;
        }
    }

    public function sendEmailCompleteProfile($toEmail, $token)
    {
        $colorPrincipal = '#0077b6';
        $colorAcento    = '#00b4d8';
        $colorFondo     = '#f0f7fa';

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = Env::get('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = Env::get('MAIL_USERNAME');
            $mail->Password   = Env::get('MAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = Env::get('MAIL_PORT');

            $mail->setFrom(Env::get('MAIL_FROM'), 'Soporte Escuela de Natación');
            $mail->addAddress($toEmail);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Completá tu perfil de profesor';

            $baseUrl      = Env::get('APP_URL');
            // TODO: reemplazá esta URL con la ruta real de completar perfil
            $profileLink  = rtrim($baseUrl, '/') . '?url=complete-register&token=' . $token;

            $mail->Body = "
<div style='background-color: {$colorFondo}; padding: 40px; font-family: Arial, sans-serif; line-height: 1.6;'>
    <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #cce3ee;'>

        <!-- Header -->
        <div style='background-color: {$colorPrincipal}; padding: 25px 20px; text-align: center;'>
            <h1 style='color: #ffffff; margin: 0; font-size: 22px; letter-spacing: 1px;'>🏊 Escuela de Natación</h1>
            <p style='color: {$colorAcento}; margin: 6px 0 0; font-size: 13px; letter-spacing: 2px; text-transform: uppercase;'>Área de Profesores</p>
        </div>

        <!-- Body -->
        <div style='padding: 35px 30px; text-align: center;'>
            <h2 style='color: #004e7c; margin-top: 0;'>¡Bienvenido al equipo!</h2>
            <p style='color: #555555; font-size: 16px;'>
                Tu cuenta de profesor fue creada exitosamente. Para poder operar en el sistema necesitamos que completes tu perfil con tus datos personales y profesionales.
            </p>

            <div style='background-color: {$colorFondo}; border-left: 4px solid {$colorAcento}; border-radius: 4px; padding: 15px 20px; margin: 25px 0; text-align: left;'>
                <p style='margin: 0; color: #444444; font-size: 14px;'><strong>¿Qué vas a completar?</strong></p>
                <ul style='margin: 10px 0 0; padding-left: 20px; color: #666666; font-size: 14px;'>
                    <li>Datos personales y de contacto</li>
                    <li>Nueva contraseña</li>
                    <li>Especialidad</li>
                </ul>
            </div>

            <div style='margin: 30px 0;'>
                <a href='{$profileLink}'
                   style='background-color: {$colorPrincipal}; color: #ffffff; padding: 15px 30px;
                          text-decoration: none; border-radius: 5px; font-weight: bold;
                          font-size: 15px; display: inline-block;'>
                    Completar mi perfil
                </a>
            </div>

            <p style='color: #999999; font-size: 12px; margin-top: 25px;'>
                Este enlace es personal e intransferible. Expirará en <strong>24 horas</strong>.<br>
                Si no esperabas este correo, podés ignorarlo sin problema.
            </p>
        </div>

        <!-- Footer -->
        <div style='background-color: #f0f4f8; padding: 15px; text-align: center; border-top: 1px solid #dde8f0;'>
            <p style='color: #aaaaaa; font-size: 11px; margin: 0;'>
                © " . date('Y') . " Escuela de Natación — Panel Administrativo
            </p>
        </div>

    </div>
</div>
";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo 'Error de PHPMailer: ' . $mail->ErrorInfo;
            die();
            return false;
        }
    }
}

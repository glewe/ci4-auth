<?php

return [

    //
    // _Global
    //
    'current_user'                   => 'Usuario Actual',
    'description'                    => 'Descripción',
    'for'                            => 'para',
    'getHelpForPage'                 => 'Ayuda para esta página...',
    'name'                           => 'Nombre',
    'no_login'                       => 'Ninguno ha iniciado sesión.',
    'no_selection'                   => 'Seleccionar algo de esta lista no tendrá efectos. Se muestra solo a modo informativo.',
    'user_id'                        => 'ID de usuario',

    //
    // 2FA
    //
    '2fa' => [
        'setup' => [
            '2fa_optional'                => 'Esta aplicación le permite configurar una autenticación de dos factores para iniciar sesión.',
            '2fa_required'                => 'Esta aplicación requiere una autenticación de dos factores para iniciar sesión.',
            'authenticator_code'          => 'Código de autenticación (seis dígitos numéricos)',
            'authenticator_code_desc'     => 'En su aplicación de autenticación, agregue una nueva entrada escaneando el código de barras anterior o ingresando la clave secreta manualmente. Después de agregar la nueva entrada, ingrese el siguiente código generado por su aplicación en el campo a continuación y haga clic en "Verificar".',
            'authenticator_code_missing'  => 'Envíe un código de autenticación para verificarlo.',
            'header'                      => 'Configuración de la autenticación de dos factores',
            'mismatch'                    => 'El código introducido no coincide. Inténtalo de nuevo.',
            'onboarding_comment'          => 'Aquí puedes hacer el proceso de onboarding. Necesitará un dispositivo móvil y una aplicación de autenticación como Google Authenticator o Microsoft Authenticator.',
            'secret_exists'               => 'Ya configuraste tu 2FA. Hacerlo de nuevo sobrescribirá su clave secreta con una nueva. Las entradas de la aplicación Authenticator basadas en el antiguo ya no funcionarán.',
            'success'                     => 'Su configuración de 2FA se ha completado correctamente.',
        ],
        'login' => [
            'header'                      => '2FA Entrar',
            'pin'                         => 'Introduce el PIN desde tu aplicación de autenticación.',
            'pin_login'                   => 'Inicio de sesión con PIN',
            'no_2fa_in_progress'          => 'No hay inicio de sesión 2FA en curso.'
        ],
    ],

    //
    // About
    //
    'about' => [
        'about'                       => 'Acerca',
        'copyright'                   => 'Copyright',
        'credits'                     => 'Créditos',
        'documentation'               => 'Documentación',
        'release_info'                => 'Información de la versión',
        'support'                     => 'Apoyo',
        'version'                     => 'Versión',
    ],

    //
    // Account
    //
    'account' => [
        '2fa'                         => '2FA configurado',
        'active'                      => 'Cuenta activa',
        'banned'                      => 'Cuenta prohibida',
        'inactive'                    => 'Cuenta no activa',
    ],

    //
    // Activation
    //
    'activation' => [
        'no_user'                     => 'No se localiza un usuario con ese código de activación.',
        'resend'                      => 'Reenviar mensaje de reactivación de nuevo.',
        'subject'                     => 'Activa tu cuenta',
        'success'                     => 'Por favor, confirma tu cuenta haciendo click en el enlace que te hemos enviado por correo.',
        'error_sending'               => 'Fallo al enviar mebseje de activación a: {0}',
        'not_activated'               => 'Esta cuenta aún no se ha activado.',
    ],

    //
    // Alerts
    //
    'alert' => [
        'error'                       => 'Error',
        'information'                 => 'Información',
        'no_change'                   => 'Nada ha cambiado.',
        'warning'                     => 'Aviso',
    ],

    //
    // Buttons
    //
    'btn' => [
        'action'                      => 'Acción',
        'cancel'                      => 'Cancelar',
        'create'                      => 'Crear',
        'createGroup'                 => 'Crear Grupo',
        'createPermission'            => 'Crear Permiso',
        'createRole'                  => 'Crear Rol',
        'createUser'                  => 'Crear Usuario',
        'delete'                      => 'Borrar',
        'edit'                        => 'Editar',
        'editGroup'                   => 'Editar Grupo',
        'editPermission'              => 'Editar Permiso',
        'editRole'                    => 'Editar Rol',
        'editUser'                    => 'Editar Usuario',
        'remove_secret'               => 'Quitar Secreto',
        'reset'                       => 'Resetear',
        'search'                      => 'Buscar',
        'submit'                      => 'Enviar',
        'verify'                      => 'Verificar',
        'view'                        => 'Ver',
    ],

    //
    // Exceptions
    //
    'exception' => [
        'invalid_fields'              => 'El campo "{0}" no se puede usar para validar credenciales.',
        'invalid_model'               => 'El modelo {0} se debe cargar antes de usarlo.',
        'no_user_entity'              => 'Se debe dar una Entidad de Usuario para la validación de la contraseña.',
        'not_logged_in'               => 'Tienes que estar logueado para acceder a esta página.',
        'insufficient_permissions'    => 'No tienes los permisos necesarios para acceder a esta página.',
        'too_many_credentials'        => 'Solo puede validar contra 1 credencial que no sea una contraseña.',
        'unknown_error'               => 'Lo siento, ha habido un problema al enviarte el correo. Por favor, inténtalo más tade.',
        'password_length_not_set'     => 'Debes darle un valor a `minimumPasswordLength` en el archivo de configuración de Auth.',
        'user_not_found'              => 'No localizamos un usuario con ID = {0, number}.',
    ],

    //
    // Forgot Password
    //
    'forgot' => [
        'error_email'                 => 'No se ha podido enviar el correo con las instrucciones de reseteo de contraseña a: {0}',
        'error_reset'                 => 'No se han podido enviar instrucciones de reseteo a {0}',
        'disabled'                    => 'La opción de resetear contraseña se ha desactivado.',
        'no_user'                     => 'No se localiza a un usuario con ese correo.',
        'subject'                     => 'Instrucciones de reseteo de Contraseña',
        'email_sent'                  => 'Se te ha enviado por correo un token. Tecléalo abajo para continuar.',
        'reset_success'               => 'tu contraseña se ha modificado correctamente. Logéate con la nueva contraseña.',
    ],

    //
    // Group
    //
    'group'   => [
        'create_success'              => 'Nuevo grupo creado: {0}',
        'delete_confirm'              => '¿Seguro que quieres borrar este grupo?',
        'delete_success'              => 'Grupo borrado: {0}',
        'description'                 => 'Descripción del Grupo',
        'description_desc'            => 'Introduce una descripción para este grupo.',
        'name'                        => 'Nombre del Grupo',
        'name_desc'                   => 'Introduce un nombre para este grupo.',
        'none_found'                  => 'No hay grupos.',
        'not_found'                   => 'No se encuentra el grupo: {0}.',
        'not_unique'                  => 'El nombre de grupo "{0}" ya existe. Los nombres de grupo deben ser únicos.',
        'permissions'                 => 'Permisos de Grupo',
        'permissions_desc'            => 'Selecciona uno o más permisos que se le darán a los usuarios de este grupo.',
        'update_success'              => 'Grupo "{0}" actualizado correctamente.',
        'group'                       => 'Grupo',
        'groups'                      => 'Grupos',
    ],

    //
    // Login
    //
    'login' => [
        'already_registered'          => '¿Ya registrado?',
        'bad_attempt'                 => 'Logueo incorrecto. Por favor, comprueba tus credenciales.',
        'current'                     => 'Actual',
        'home'                        => 'Inicio',
        'enter_email_instructions'    => '¡Sin problema! Dinos tu correo y te enviaremos instrucciones para resetear tu contraseña.',
        'email'                       => 'Correo',
        'email_address'               => 'Dirección de Correo',
        'email_or_username'           => 'Correo o usuario',
        'enter_code_email_password'   => 'Introduce el código que has recibido por correo, tu dirección de correo, y tu nueva contraseña.',
        'forgot_password'             => '¿Has olvidado tu contraseña?',
        'forgot_your_password'        => '¿Has olvidado tu contraseña?',
        'invalid_password'            => 'Logueo incorrecto. Por favor, revisa tu contraseña.',
        'action'                      => 'Entrar',
        'title'                       => 'Entrar',
        'success'                     => '¡Bienvenido de nuevo!',
        'need_an_account'             => '¿Necesitas una cuenta?',
        'new_password'                => 'Nueva contraseña',
        'new_password_repeat'         => 'Repite la nueva contraseña',
        'password'                    => 'Contraseña',
        'remember_me'                 => 'Recordarme',
        'register'                    => 'Registrarte',
        'repeat_password'             => 'Repetir Contraseña',
        'reset_password'              => 'Resetear Contraseña',
        'reset_your_password'         => 'Resetea Tu Contraseña',
        'send_instructions'           => 'Enviar Instrucciones',
        'sign_in'                     => 'Login',
        'token'                       => 'Token',
        'too_many_requests'           => 'Demasiados intentos. Por favor, espera {0, number} segundos.',
        'username'                    => 'Usuario',
        'we_never_share'              => 'Nunca compartiremos tu correo con nadie.',
    ],

    //
    // Modal Dialogs
    //
    'modal' => [
        'confirm'                     => 'Por favor, Confirma',
    ],

    //
    // Navbar
    //
    'nav' => [
        'home'                        => 'Inicio',
        'authorization'   => [
            'self'                     => 'Autorización',
            'groups'                   => 'Grupos',
            'permissions'              => 'Permisos',
            'roles'                    => 'Roles',
            'users'                    => 'Usuarios',
        ],
        'authentication'   => [
            'self'                     => 'Autenticación',
            'login'                    => 'Login',
            'logout'                   => 'Logout',
            'forgot_password'          => 'Recordar Contraseña',
            'reset_password'           => 'Resetear Contraseña',
            'register'                 => 'Registrarse',
            'setup2fa'                 => 'Configuración 2FA',
            'whoami'                   => '¿Quién Soy?',
        ],
        'settings'                     => 'Configuración',
    ],

    //
    // Password
    //
    'password' => [
        'error_common'                => 'La contraseña no debe ser una contraseña común.',
        'error_empty'                 => 'Se necesita una contraseña.',
        'error_length'                => 'La contraseña debe tener al menos {0, number} caracteres.',
        'error_personal'              => 'Las contraseñas no pueden contener información personal modificada.',
        'error_pwned'                 => 'La contraseña {0} ha quedado expuesta debido a una violación de datos y se ha visto comprometida {1, número} veces en {2} contraseñas.',
        'error_pwned_database'        => 'una base de datos',
        'error_pwned_databases'       => 'bases de datos',
        'error_similar'               => 'La contraseña es demasiado parecida al usuario.',
        'change_success'              => 'Contraseña modificada correctamente',
        'reset_token_expired'         => 'Lo sentimos. Tu token de reseteo ha caducado.',
        'suggest_length'              => 'Las claves de acceso, de hasta 255 caracteres, crean contraseñas más seguras y fáciles de recordar.',
        'suggest_common'              => 'La contraseña se comparó con más de 65.000 contraseñas de uso común o contraseñas que se filtraron a través de hacks.',
        'suggest_personal'            => 'No deben usarse variaciones de tu dirección de correo electrónico o nombre de usuario para contraseñas.',
        'suggest_similar'             => 'No uses partes de tu usuario en tu contraseña.',
        'suggest_pwned'               => '{0} no se debe usar nunca como contraseña. Si la estás usando en algún sitio, cámbiala inmediatamente.',
        'user_not_exist'              => 'No se ha cambiado la contraseña. No existe el usuario',
    ],

    //
    // Permission
    //
    'permission'   => [
        'create_success'              => 'Creado nuevo permiso: {0}',
        'delete_confirm'              => '¿Seguro que quieres borrar este permiso?',
        'delete_success'              => 'Permiso eliminado: {0}',
        'description'                 => 'Descripción del Permiso',
        'description_desc'            => 'Introduce una descripción para este permiso.',
        'name'                        => 'Nombre del Permiso',
        'name_desc'                   => 'Introduce un nombre para este permiso.',
        'none_found'                  => 'No hay permisos.',
        'not_found'                   => 'No se encuentra el permiso: {0}.',
        'not_unique'                  => 'El nombre del permiso "{0}" ya existe. Los nombres de permisos deben ser únicos.',
        'update_success'              => 'permiso "{0}" actualizado correctamente.',
        'permission'                  => 'Permiso',
        'permissions'                 => 'Permisos',
        'perm_groups'                 => 'Permisos de Grupos',
        'perm_groups_desc'            => 'Estos grupos tienen este permiso.',
        'perm_roles'                  => 'Permisos de Roles',
        'perm_roles_desc'             => 'Estos roles tienen este permiso.',
        'perm_users'                  => 'Permisos de Usuarios',
        'perm_users_desc'             => 'Estos usuarios tienen este permiso como un permiso personal.',
        'tab_details'                 => 'Detalles',
        'tab_usage'                   => 'Uso',
    ],

    //
    // Register
    //
    'register' => [
        'create_success'              => 'Nuevo usuario creado: {0}, #{1}',
        'disabled'                    => 'Lo sentimmos, no se permiten nuevas cuentas de usuario en estos momentos.',
        'registerSuccess'             => '¡Bienvenido! Por favor, logéate con tus nuevas credenciales.',
    ],

    //
    // Role
    //
    'role'   => [
        'create_success'              => 'Nuevo rol creado: {0}',
        'delete_confirm'              => '¿Seguro que quieres borrar este rol?',
        'delete_success'              => 'Rol borrado: {0}',
        'description'                 => 'Descripción del Rol',
        'description_desc'            => 'Introduce una descripción para este rol.',
        'name'                        => 'Nombre del Rol',
        'name_desc'                   => 'Introduce un nombre para este rol.',
        'none_found'                  => 'No hay roles.',
        'not_found'                   => 'No se encuentra el rol: {0}.',
        'permissions'                 => 'permisos de Rol',
        'permissions_desc'            => 'Selecciona uno o más permisos que se otorgarán a los usuarios de este rol.',
        'update_success'              => 'Role "{0}" actualizado correctamente.',
        'role'                        => 'Rol',
        'roles'                       => 'Roles',
    ],

    //
    // User
    //
    'user'   => [
        'active'                      => 'Activo',
        'active_desc'                 => 'Activado o desactivado el usario.',
        'banned'                      => 'Proscrito',
        'banned_desc'                 => 'Prohibir o anular la prohibición de este usuario.',
        'create_success'              => 'Nuevo usuario creado: {0} ({1})',
        'delete_confirm'              => '¿Seguro que quieres borrar este usuario?',
        'delete_success'              => 'Usuario borrado: {0} ({1})',
        'displayname'                 => 'Nombre a mostrar',
        'displayname_desc'            => 'Introduce el nombre del usuario a mostrar en la aplicación.',
        'email'                       => 'Correo',
        'email_desc'                  => 'Introduce una dirección de correo válida.',
        'firstname'                   => 'Nombre',
        'firstname_desc'              => 'Introduce el nombre del usuario.',
        'fullname'                    => 'Apellidos',
        'groups'                      => 'Grupos de Usuarios',
        'groups_desc'                 => 'Selecciona uno o más grupos a los que asignar este usuario.',
        'is_banned'                   => 'Usuario bloqueado. Contacta con el administrador',
        'lastname'                    => 'Apellido',
        'lastname_desc'               => 'Introduce el apellido del usuario.',
        'none_found'                  => 'No hay usuarios.',
        'not_found'                   => 'No se encuentra al usuario: {0}.',
        'password'                    => 'Contraseña',
        'password_desc'               => 'Introduce una nueva contraseña. Si no quieres cambiar tu contraseña, deja este campo en blanco.',
        'pass_confirm'                => 'Repite Contraseña',
        'pass_confirm_desc'           => 'Repite tu nueva contraseña. Si no quieres cambiar tu contraseña, deja este campo en blanco.',
        'pass_resetmail'              => 'Enviar correo de restablecimiento de contraseña',
        'pass_resetmail_desc'         => 'Con este interruptor activado, se envía un correo de restablecimiento de contraseña al usuario después de la creación.',
        'permissions'                 => 'Permisos Personales',
        'permissions_desc'            => 'Seleccione uno o más permisos individuales que se otorgarán a este usuario. Estos permisos se sumarán a los heredados de grupos y roles.',
        'permissions_all'             => 'Todos los Permisos',
        'permissions_all_desc'        => 'Este es el listado de todos los permisos que tiene este usuario. Incluyen los permisos personales de arriba más los permisos heredados de los grupos y roles a los que pertenece.',
        'remove_secret_confirm'       => 'Eliminar la clave secreta 2FA',
        'remove_secret_confirm_desc'  => '¿Está seguro de que desea eliminar el secreto 2FA para este usuario? El usuario puede crear uno nuevo pasando por el proceso de onboarding (Configuración 2FA) nuevamente.',
        'remove_secret_success'       => 'Se ha eliminado la clave secreta del usuario: {0} ({1})',
        'roles'                       => 'Roles de Usuario',
        'roles_desc'                  => 'Selecciona uno o más roles a asignar a este usuario.',
        'status'                      => 'Estado',
        'tab_account'                 => 'Cuenta',
        'tab_groups'                  => 'Grupos',
        'tab_permissions'             => 'Permisos',
        'tab_roles'                   => 'Roles',
        'update_success'              => 'Usuario "{0} ({1})" actualizado correctamente.',
        'user'                        => 'Usuario',
        'users'                       => 'Usuarios',
        'username'                    => 'Nombre de Usuario',
        'username_desc'               => 'Introduce un nombre de usuario.',
    ],
];

php artisan --version

# SENA Autónoma v10

## Descripción del Proyecto
Proyecto de automatización agrícola desarrollado para el SENA. Este sistema permite la gestión y monitoreo de cultivos automatizados, incluyendo un dashboard administrativo para el control y visualización de datos.

## Características Principales
- Sistema de autenticación y autorización
- Dashboard administrativo con múltiples vistas
- Monitoreo de cultivos en tiempo real
- Gestión de usuarios y roles
- Automatización de procesos agrícolas
- Interfaz responsive y moderna

## Tecnologías Utilizadas
### Backend
- PHP 8.0+
- Laravel Framework
- MySQL Database

### Frontend
- HTML5, CSS3, JavaScript
- Bootstrap (Framework CSS)
- Chart.js (Visualización de datos)
- Blade Templates
- Font Awesome (Iconos)

## Requisitos Previos
- PHP >= 8.0
- Composer
- Node.js y NPM (opcional, para compilar assets)
- XAMPP o servidor Apache similar
- MySQL

## Instrucciones de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/sena-autonona-v10.git
cd sena-autonona-v10
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Configurar el archivo de entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar la base de datos**
- Crear una base de datos en MySQL
- Actualizar las credenciales de la base de datos en el archivo `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_base_de_datos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

5. **Ejecutar las migraciones**
```bash
php artisan migrate
```

6. **Iniciar el servidor de desarrollo**
```bash
php artisan serve
```

## Estructura del Proyecto
### Directorios Principales
- `/app`: Lógica principal de la aplicación
  - `/Http/Controllers`: Controladores
  - `/Models`: Modelos de datos
  - `/Services`: Servicios de la aplicación
- `/config`: Archivos de configuración
- `/database`: Migraciones y seeders
- `/public`: Archivos públicos
- `/resources`: Vistas y assets
  - `/views`: Plantillas Blade
    - `/dashboard`: Componentes del dashboard
    - `/auth`: Vistas de autenticación
- `/routes`: Definición de rutas
- `/tests`: Tests automatizados

### Módulos Principales
1. **Sistema de Autenticación**
   - Login/Registro de usuarios
   - Recuperación de contraseña
   - Gestión de sesiones

2. **Dashboard Administrativo**
   - Panel de control principal
   - Widgets informativos
   - Gráficos y estadísticas
   - Tablas de datos
   - Formularios
   - Componentes UI

3. **Gestión de Cultivos**
   - Monitoreo de variables ambientales
   - Control de riego
   - Programación de tareas
   - Alertas y notificaciones

## Configuración Adicional
### Permisos de Archivos
```bash
chmod -R 775 storage bootstrap/cache
```

### Variables de Entorno Importantes
```env
APP_NAME=SENA-Autonoma
APP_ENV=local
APP_DEBUG=true
QUEUE_CONNECTION=database
MAIL_MAILER=smtp
```

## Documentación Adicional
- [Manual de Usuario](docs/manual-usuario.md)
- [Documentación Técnica](docs/documentacion-tecnica.md)
- [Guía de Contribución](CONTRIBUTING.md)

## Equipo de Desarrollo
- Desarrolladores principales
- Colaboradores
- Mentores SENA

## Licencia
Este proyecto está bajo la licencia [MIT](LICENSE).

## Soporte
Si encuentras algún problema o tienes sugerencias:
1. Revisa la [documentación](docs/)
2. Crea un issue en el repositorio
3. Contacta al equipo de desarrollo

## Estado del Proyecto
- Versión actual: 1.0
- Estado: En desarrollo activo
- Última actualización: [Fecha]

## Agradecimientos
- SENA
- Instructores y mentores
- Comunidad de desarrollo

## Acceso al Dashboard
Una vez instalado, puedes acceder al dashboard en:
```
http://localhost:8000/dashboard
```

## Notas Importantes
- Asegúrate de tener los permisos correctos en las carpetas `storage` y `bootstrap/cache`
- Para entornos de producción, configura correctamente los valores en el archivo `.env`
- Las librerías frontend (Bootstrap, Chart.js) se cargan desde CDN, por lo que necesitarás conexión a internet

## Rama de Desarrollo: Sebas
Esta es la rama de desarrollo para las características específicas de Sebas.

## Características principales:
- Sistema de gestión de municipios
- Control de usuarios y roles
- Gestión de granjas y sensores
- Monitoreo en tiempo real

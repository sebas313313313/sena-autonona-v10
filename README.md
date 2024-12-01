php artisan --version

# SENA Autónoma Dashboard

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

## Librerías y Dependencias Incluidas
- **Bootstrap**: Framework CSS para el diseño responsive
- **Chart.js**: Librería para gráficos y visualización de datos
- **Font Awesome**: Iconos y fuentes

## Estructura del Proyecto
- `/resources/views/dashboard`: Contiene todas las vistas del dashboard
  - `/layouts`: Layouts principales
  - `/components`: Componentes reutilizables
  - `/ui`: Componentes de interfaz de usuario
  - `/forms`: Formularios
  - `/charts`: Gráficos
  - `/tables`: Tablas

## Acceso al Dashboard
Una vez instalado, puedes acceder al dashboard en:
```
http://localhost:8000/dashboard
```

## Notas Importantes
- Asegúrate de tener los permisos correctos en las carpetas `storage` y `bootstrap/cache`
- Para entornos de producción, configura correctamente los valores en el archivo `.env`
- Las librerías frontend (Bootstrap, Chart.js) se cargan desde CDN, por lo que necesitarás conexión a internet

## Soporte
Si encuentras algún problema durante la instalación, por favor crea un issue en el repositorio.

## Rama de Desarrollo: Sebas
Esta es la rama de desarrollo para las características específicas de Sebas.

## Características principales:
- Sistema de gestión de municipios
- Control de usuarios y roles
- Gestión de granjas y sensores
- Monitoreo en tiempo real

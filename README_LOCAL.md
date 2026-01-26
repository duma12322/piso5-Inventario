
---

# Proyecto Inventario – Piso 5

**Documentación Técnica Completa**
Link del proyecto: https://github.com/duma12322/piso5-Inventario.git
---

## 1. Descripción General

El proyecto es una aplicación web desarrollada en **Laravel 12** para la gestión de inventario de componentes de hardware (CPU, RAM, discos, tarjetas, unidades ópticas, etc.) y software (Windows, Office, etc).

Permite:

* Gestión de usuarios
* Registro y edición de equipos
* Registro y edición de componentes
* Clasificación por tipo de componente y componentes opcionales
* Registro y edición de direcciones, divisiones y coordinaciones
* Registros Logs
* Exportación de datos
* Generación de documentos PDF
* Importación/exportación Excel
* Interfaz moderna con Vite

---

## 2. Stack Tecnológico

### Backend

* PHP 8.4.14
* Laravel Framework 12.36.1
* MariaDB 10.4.28 - MySQL 5.7
* TCPDF 6.10.0
* Laravel Excel (maatwebsite/excel)

### Frontend

* Blade
* CSS personalizado
* JavaScript modular
* Vite
* Node.js + npm

### Entorno

* Windows
* XAMPP v3.3.0
* Composer 2.7.8

---

## 3. Requisitos del Sistema

### Software requerido

* XAMPP v3.3.0
* PHP 8.4.14 (CLI y Apache)
* Composer 2.7.8
* Node.js LTS 20+
* Git

### Verificación

```
php -v
composer -V
node -v
npm -v
```

---

## 4. Instalación del Proyecto

### 4.1 Clonar repositorio

Ubicación recomendada:

```
C:\xampp\htdocs\
```

Comando:

```
git clone https://github.com/duma12322/piso5-Inventario.git
```

---

### 4.2 Variables de entorno (.env)

Crear archivo `.env` en la raíz:

```
APP_NAME=InventarioPiso5
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=piso5_inventario
DB_USERNAME=root
DB_PASSWORD=
```

Generar APP_KEY:

```
composer install
php artisan key:generate
```

---

## 5. Base de Datos

### Método recomendado (SQL)

1. Crear base de datos:

```
piso5_inventario
```

2. Importar:

```
piso5_inventario.sql
```

**No ejecutar migraciones si usas el SQL.**

---

### Método alternativo (Migraciones)

```
php artisan migrate
php artisan db:seed
```

---

## 6. Ejecución de la Aplicación

### 6.1 Servidor Laravel

```
php artisan serve
```

Acceso:

```
http://127.0.0.1:8000/login
```

---

### 6.2 Apache (VirtualHost)

**httpd-vhosts.conf**

```
<VirtualHost *:80>
    ServerName piso5-inventario.local
    DocumentRoot "C:/xampp/htdocs/piso5-Inventario/public"
    <Directory "C:/xampp/htdocs/piso5-Inventario/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**hosts**

```
127.0.0.1 piso5-inventario.local
```

---

## 7. Frontend (Vite)

### Instalación

```
npm install
```

### Desarrollo

```
npm run dev
```

### Producción

```
npm run build
```

**Si Vite no está corriendo, el sistema NO se ve bien.**

---

## 8. Composer Scripts

### Setup completo

```
composer setup
```

### Desarrollo completo

```
composer dev
```

Ejecuta:

* Servidor Laravel
* Cola
* Logs
* Vite

---

## 9. Autoload y Helpers

Archivo:

```
app/Helpers/helpers.php
```

Cargado vía:

```json
"files": ["app/Helpers/helpers.php"]
```

Actualizar:

```
composer dump-autoload
```

---

## 10. Gestión de Usuarios

* Autenticación estándar Laravel
* Login en `/login`
* Roles gestionados desde base de datos
* Registro, edición y eliminación de usuarios
* Acciones registradas mediante logs

---

## 11. Componentes del Inventario

Tipos soportados:

* CPU
* RAM
* Disco Duro
* SSD
* Tarjeta Gráfica
* Tarjeta de Red
* Unidad Óptica
* Fuente de Poder
* Tarjeta Madre
* Fan Cooler

Cada componente puede tener:

* Marca
* Modelo
* Tipo
* Estado
* Detalles
* Campos dinámicos según tipo

---

## 12. Formularios Dinámicos

* Campos que aparecen según tipo de componente
* Checkboxes múltiples
* Selects con valores preseleccionados en edición
* Conversión automática de arrays a strings (`implode`)

---

## 13. JavaScript del Sistema

Ubicación:

```
public/js/
```

Archivos:

* `componente1.js` → lógica general de componentes
* `unicos.js` → validaciones de unicidad
* `unidad.js` → tipos de unidades ópticas
* `tipoRam.js` → tipos de RAM
* `componenteOpcionales.js` → lógica general de componentes opcionales
* `coordinacion.js` → Actualizar el select "division" según
* `equipos.js` → Dinámicas de selección de direcciones, divisiones, coordinaciones, niveles de equipo y software adicional
* `inactivos.js` → Manejo dinámico de selects de Dirección, División y Coordinación
* `inactivos2.js` → Toggle de visibilidad de secciones/divs
* `porEquipo.js` → Toggle de visibilidad del div "opcionales"
* `software.js` → Agregar y eliminar campos dinámicos de software


Cargados en:

```blade
@section('scripts')
```

---

## 14. Estilos CSS

* CSS personalizado dentro de Blade
* Variables CSS (`:root`)
* Diseño responsivo
* Componentes reutilizables

---

## 15. PDFs

* Generación mediante **TCPDF**
* Reportes exportables
* Documentos técnicos
* Sin dependencias externas adicionales

---

## 16. Excel

* Importación de datos
* Exportación de inventario
* Librería: `maatwebsite/excel`

---

## 17. Colas (Queue)

Uso de Laravel Queue:

```
php artisan queue:listen
```

Necesario para tareas en segundo plano.

---

## 18. Logs

* Logs de acciones de usuario
* Logs del sistema completo 

---

## 19. Testing

Framework:

* PHPUnit 11.5.3

Ejecutar:

```
php artisan test
```

---

## 20. Limpieza y Mantenimiento

```
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

---

## 21. Errores Comunes

### Pantalla blanca

* Apache no apunta a `public`
* `APP_DEBUG=false`

### DB error

* MySQL apagado
* Credenciales incorrectas

### CSS/JS no cargan

* Vite apagado
* Ejecutar `npm run dev`

---

## 22. Seguridad

* Protección CSRF
* Validaciones backend
* Autenticación Laravel
* Acceso controlado por sesión

---

## 23. Estructura del Proyecto

```
app/
 ├── Models
 ├── Http/Controllers
 ├── Helpers
routes/
 ├── web.php
resources/
 ├── views
public/
 ├── js
 ├── css
```

---

## 24. Versiones Clave

| Componente | Versión |
| ---------- | ------- |
| PHP        | 8.4.14  |
| Laravel    | 12.36.1 |
| Composer   | 2.7.8   |
| TCPDF      | 6.10.0  |
| Node       | 20+     |

---

## 25. Estado del Proyecto

✅ Funcional
✅ Documentado
✅ Listo para desarrollo y mantenimiento
✅ Compatible con XAMPP

------------------------------------------------------------------------------------------------------------------

## Opción Alternativa: Uso de PostgreSQL

El proyecto es compatible con **PostgreSQL** gracias a la abstracción de base de datos de Laravel (Eloquent y Query Builder).

Esta alternativa es recomendable si se requiere:

* Mayor robustez en concurrencia
* Cumplimiento estricto del estándar SQL
* Mejor escalabilidad en producción
* Uso de tipos avanzados (JSONB, UUID, etc.)

---

### 1.Requisitos adicionales

* PostgreSQL **13 o superior**
* pgAdmin (opcional, recomendado)
* PHP con soporte para PostgreSQL

---

### 2.Extensiones PHP requeridas (php.ini)

Abrir el archivo:

```
C:\xampp\php\php.ini
```

Verificar que las siguientes extensiones estén **habilitadas (sin ;)**:

```
extension=pdo_pgsql
extension=pgsql
```

Opcionales pero recomendadas (ya suelen estar activas):

```
extension=mbstring
extension=openssl
extension=curl
extension=fileinfo
```

Después de guardar cambios:

**Reiniciar Apache desde el panel de XAMPP**

---

### 3.Configuración del archivo `.env`

Reemplazar la sección de base de datos por:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=piso5_inventario
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```

---

### 4.Creación de la base de datos

Desde **pgAdmin** o consola de PostgreSQL:

```
CREATE DATABASE piso5_inventario;
```

---

### 5.Migraciones y Seeders (Recomendado)

Perfecto, aquí tienes la sección **completada y clara**, incluyendo dónde colocar el código de cada tabla y de los registros iniciales usando **migraciones y seeders**:

---

### 6.Migraciones y Seeders (Recomendado)

**No utilizar el archivo `.sql` de MySQL/MariaDB con PostgreSQL**, debido a diferencias de sintaxis y tipos de datos.
Usar exclusivamente migraciones y seeders de Laravel:

```bash
php artisan migrate
php artisan db:seed
```

Laravel se encarga automáticamente de adaptar:

* `AUTO_INCREMENT` → `SERIAL / BIGSERIAL`
* `ENUM` → `VARCHAR`
* `TINYINT` → `SMALLINT`
* `DATETIME` → `TIMESTAMP`

---

#### 7.Migraciones (definición de tablas)

Todas las tablas deben definirse en **archivos de migración**, ubicados en:

```
database/migrations/
```

Ejemplo para la tabla `usuarios`:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('usuario', 50);
            $table->string('password', 255);
            $table->enum('rol', ['Administrador', 'Usuario']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
```

* Cada tabla tiene su propia migración.
* Para crear una nueva migración:

```bash
php artisan make:migration create_usuarios_table
```

* Luego ejecutar todas las migraciones:

```bash
php artisan migrate
```

---

#### 8.Seeders (datos iniciales)

Los **seeders** insertan datos iniciales en cada tabla y se ubican en:

```
database/seeders/
```

Ejemplo para `UsuariosSeeder`:

```php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'usuario' => 'admin',
                'password' => md5('123456'),
                'rol' => 'Administrador',
            ],
            [
                'usuario' => 'usuario',
                'password' => md5('123456'),
                'rol' => 'Usuario',
            ],
        ]);
    }
}
```

* Crear un seeder con:

```bash
php artisan make:seeder UsuariosSeeder
```

* Registrar todos los seeders en `DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call([
        UsuariosSeeder::class,
        RolesSeeder::class,
        EquiposSeeder::class,
        ComponentesSeeder::class,
        // Agregar todos los seeders de cada tabla
    ]);
}
```

* Ejecutar todos los seeders:

```bash
php artisan db:seed
```

---

#### Recomendaciones

* Siempre usar migraciones + seeders para compatibilidad entre **MySQL/MariaDB y PostgreSQL**.
* Evitar SQL crudo con sintaxis específica de MySQL (`ENGINE=InnoDB`, `IFNULL()`, `LIMIT offset,count`).
* Para nuevas tablas, **crear migración + seeder** antes de ejecutar `migrate` y `db:seed`.
* En PostgreSQL, Laravel adapta automáticamente los tipos de datos.

---

Esto garantiza que:

* Tu base de datos se cree automáticamente con todas las tablas necesarias.
* Todos los registros iniciales se inserten correctamente.
* Funciona tanto en **MySQL/MariaDB** como en **PostgreSQL**.

---

### Consideraciones técnicas

* Evitar SQL crudo con sintaxis específica de MySQL:

  * `ENGINE=InnoDB`
  * `IFNULL()`
  * `LIMIT offset,count`
* Usar:

  * Eloquent
  * Query Builder de Laravel

Las siguientes funcionalidades **no requieren cambios**:

* Autenticación
* Gestión de inventario
* Logs
* TCPDF
* Laravel Excel
* Colas (Queue)
* Frontend (Vite)

---

### Recomendación final

* **Desarrollo local con XAMPP:** MariaDB (configuración más simple)
* **Producción / Escalabilidad:** PostgreSQL

---


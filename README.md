# Post-It
¡Bienvenido a Post-It, la red social donde la diversión y la creatividad se fusionan en un mundo dibujado a mano! Conoce personas y sumérgete en un ambiente donde lo importante es divertirse y dejar a un lado lo serio. Únete a nuestra comunidad y explora un espacio lleno de momentos ligeros y creativos. ¡Te esperamos para que dibujes tu propia experiencia en Post-It!

## Instalación

## Explicacion breve
A continuacion se explicara breve y ordenadamente como instalar y ejecutar Post-It en tu equipo.
Estos pasos solo deberas hacerlos la primera vez que ejecutes Post-It en tu equipo

### Ejecuta Xampp
Ejecuta xampp y activa Apache y MySQL

### Clonar repositorio de GIT
Importante clonar el respositorio en la carpeta htdocs de xampp

```bash
git clone https://github.com/BernatFont/Post-It.git
```
### Instalar composer

```bash
composer install
```

### Modificar archivo env
Deberas cambiar el nombre del archivo .env.example a .env en tu directorio donde clonaste el repositorio de Post-It

### Generar la App Key

```bash
php artisan key:generate
```

### Migrar base de datos
Obtiene la base de datos y la crea

```bash
php artisan migrate
```

### Lanzar Seeders
Introduce seeders predefinidos en la BBDD
```bash
php artisan db:seed
```

### Enlazar storage
Este paso se ejecuta para poder almacenar y mostrar las imagenes multimedia en el sistema
```bash
php artisan storage:link
```

### Instalar las dependencias de Node
```bash
npm install
```

### Ejecutar
Cada vez que quieras ejecutar el proyecto una vez realizados todos los pasos anteriores deberas ejecutar los siguientes comandos en consolas independientes
```bash
npm run dev

php artisan serve
```

### Visualizar
Para visualizar la pagina web ve a tu navegador y introduce en la url 127.0.0.1, ya puedes disfrutar Post-It!

## Ejecutar 

### Como ejecutar Post-It
Para ejecutar post-it deberas seguir los siguientes pasos:

Primero deberas entrar a Visual Studio Code con el proyecto Post-It abierto y abrir dos consolas, en cada una de ellas deberas de especificar uno de los siguientes dos comandos.

```bash
npm run dev

php artisan serve
```

Una vez ejecutado el proyecto debemos iniciar los servicios de Apache y MySQL en Xampp.

Accede a Post-It desde tu navegador utilizando la url 127.0.0.1


## Desarrolladores
Post-It a sido desarrollado por:

- Bernat Font
- David Valero

Institut Bernat el Ferrer.



¡Gracias por usar Post-It!
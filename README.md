## Instrucciones de instalacion.

1. Renombar `.env.example` a `.env`
2. Añadir la informacion de conexion de mysql en `.env`
3. Ejecutar `php artisan key:generate`
4. Ejecutar migraciones `php artisan migrate`
5. Correr servidor `php artisan serve`

## Generar `client_secret` y `client_id`
1. Registrar un usuario en la interfaz del sitio.
2. En la parte superior izquierda en el menu desplegable en el menu del usuario, hacer click en `API`
3. Agregar un nombre para el cliente
4. Agregar la redireccion, si se usara postman para probar el api usar como redireccion: `https://oauth.pstmn.io/v1/callback`
5. Una vez creado, anotar el secret y el id ya que no se volveran a mostrar de nuevo.

## Documentos entregables

- Coleccion de postman(ya configurada para probar, solo generar un nuevo secret y id): [Link](postman_files/Working.postman_collection.json)
- Archivo exportado de swagger:[Link](postman_files/swagger-myself418-pruebabackend-1.0.0-resolved.json)
- Documento explicativo: [Link](abstracto)

## Abstracto

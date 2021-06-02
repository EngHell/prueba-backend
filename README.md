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
- Documento explicativo: [Link](#abstracto)


## Abstracto
La resolucion del problema, se ha abordado con la ayuda del framework Laravel, como api de peliculas [Open Movie DB](http://www.omdbapi.com) como resultado de la seleccion de api se han tomado dos deciciones de diseño:
- El api de creacion de comentarios no chequea si un titulo existe, ya que no se encuentra en el database local y no se desea agotar el numero de queries diarias del api.
- La tabla de commentarios `comments` solo posee un string que no es llave foranea para asociar un comentario a un titulo.

Se han seleccionado el paquete jetstream para hacer scalfolding del la ui con el stack inertia basado en vue.

Otros paquetes importantes a mencionar:
- `laravel/Passport`: encargado del servidor oauth2.
- `guzzlehttp/guzzle`: para realizar solicitudes http.
- `laravel/sanctum`: para autenticar usuarios de la interfaz web.

Durante el test, se encontro que el metodo preferido para hacer que el api devuelva siempre `json` llamado `wantsJson` no genera el efecto deseado, por lo que se acudio a crear un middleware que cambia el header para el grupo de rutas del api.

Como resultado de lo anterior, las tecnicas de `dependency injection` para los metodos de los controladores fueron evitados ya que un modelo sin encontrar causaria que la request fuera rechazada antes de alcanzar el middleware.

Se ha escogido un esquema de url para el api basado en versiones, actualmente la version `0.0.1` del api es accesible por la url relativa a la url del servidor `api/v1/` estando todos los controladores del api organizados por un namespace similar `Api/Vi`

## API
Se discutiran las convenciones del api, el archivo de swagger especifica como esquma la devolucion de simples arrays o directamente la data del archivo, pero actualmente hay una convencion de la respuesta:

El `http status code` de la respuesta respeta las convenciones rest, por lo que se veran 404, 401, 422, etc en el error adecuado.

### Estructura de la respuesta
```json
data:{},
error:{},
code:0,
message:""
links:{}
meta:{}
```
Esta es la estructura de la respuesta, donde `data,code,message` estan presente en todas las respuestas `error` en las respuestas con fallo de validacion de parametros y `links,meta` para respuestas con paginacion, que serian el indice de comentarios para una pelicula, siendo este el unico actualmente.

`data` es un array de elementos para las respuestas que son tipo array in el esquema de swagger.

`error` es un objeto con errores con el siguiente formato:
```json
"error":{
    "campo1":[
        "error1",
        "error2"
    ],
    "campo2":[
        "error3"
    ]
}
```

`code` un codigo diferente de 0 indica error, la lista de codigos mas descriptiva:
```php
class ErrorCodes
{
    const NOT_FOUND=1;
    const UNAUTHENTICATED=2;
    const UNAUTHORIZED=3;
    const INVALID_INPUT=4;
    const INVALID_ASSIGNATION=5;
    const INTERNAL_ERROR=6;
}
```

`message` es un string con un pequeño mensaje descriptivo del error o vacio si todo fue bien.

`links` son una serie de links de paginacion actualmente: `first` `last` `prev` `next` son los miembros del campo, y seran `null` si no existen.

`meta` incluye informacion sobre la paginacion:
- current_page
- from: el id donde inicia la pagina,
- last_page
- links: un array con los elementos de la paginacion, con el siguiente formato: `{'url':string|null,'label':string,'active':bool}`
- path: url de la pagina que se esta viendo.
- per_page: numero de modelos por pagina
- to: id del ultimo modelo de la pagina
- toal: total de modelos en disponibles.

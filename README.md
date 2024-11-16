
# Prueba Tecnica Prex

A continuacion se detallan los diagramas de la siguiente aplicacion utilizando el siguiente stack:

- **[Docker](https://www.docker.com)**
- **[PHP 8.3](https://www.php.net/releases/8.3/en.php)**
- **[Laravel 10](https://laravel.com/docs/10.x)**
- **[Laravel Sail](https://laravel.com/docs/10.x/sail)**
- **[Laravel Sancutm](https://laravel.com/docs/10.x/sanctum)**
- **[PostgreSql](https://www.mysql.com/)**

## Comandos utiles:

- Clonar el repositorio
```
git clone git@gitlab.cubiq.link:cubiq/core-v2.git
```
- Instalar las dependecias 
```
composer install
```
- Copiar el .env.example
```
cp .env.example .env
php artisan key:generate
```
- Agregar el siguiente alias
```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```
- Levantar el proyecto
```
sail up -d
```
- Correr las migrations y seeders
```
sail artisan migrate:fresh --seed
```
- Correr tests:
```
sail artisan test
```

## DER
```
+----------------------------------+          +----------------------------+
|      User_Favorite_Gifs          |          |           Users            |
+----------------------------------+          +----------------------------+
| PK id                            |          | PK id                      |
| FK user_id                       |--------->| name                       |
| gif_id                           |          | email                      |
| alias                            |          | email_verified_at          |
| created_at                       |          | password                   |
| updated_at                       |          | remember_token             |
+----------------------------------+          | created_at                 |
                                              | updated_at                 |
                                              +----------------------------+
```
## UML

```UML
@startuml
class User {
    +id: bigint
    +name: string
    +email: string
    +email_verified_at: timestamp
    +password: string
    +remember_token: string
    +created_at: timestamp
    +updated_at: timestamp
    --
    +getFavorites(): List<UserFavoriteGif>
}

class UserFavoriteGif {
    +id: bigint
    +user_id: bigint
    +gif_id: string
    +alias: string
    +created_at: timestamp
    +updated_at: timestamp
    --
    +getUser(): User
}

User "1" -- "0..*" UserFavoriteGif: has_favorites
@enduml
```

## Login

### Casos de uso:
```UML
@startuml
actor Usuario

usecase "Login de Usuario" as UC1
usecase "Error de Login" as UC2

Usuario --> UC1 : Inicia sesión con email y contraseña
UC1 --> Usuario : Devuelve token si el login es correcto
UC2 --> Usuario : Devuelve error si el login es incorrecto

UC2 .> UC1 : <<extend>>  ; El error de login extiende el flujo del login
@enduml
```

### Diagrama de secuencia
```UML
actor Usuario
participant "AuthController" as Controller
participant "User" as User
participant "Hash" as Hash
participant "Token" as Token

Usuario -> Controller: login(email, password)
Controller -> Controller: validate(request)
Controller -> User: findByEmail(email)
User -> Controller: user object
alt User does not exist
    Controller -> Usuario: return error "User does not exist"
else User exists
    Controller -> Hash: check(password, stored_password)
    Hash -> Controller: return true/false
    alt Invalid password
        Controller -> Usuario: return error "Invalid password"
    else Valid password
        Controller -> Token: createToken("auth_token")
        Token -> Controller: return plainTextToken
        Controller -> Usuario: return token
    end
end
```

## Gifs
### Casos de uso:
```UML
@startuml
actor Usuario

usecase "Buscar Gif por palabra clave" as UC1
usecase "Buscar Gif por ID" as UC2
usecase "Marcar Gif como Favorito" as UC3

Usuario --> UC1 : Realiza búsqueda de gif (query, limit, offset)
Usuario --> UC2 : Busca gif por ID
Usuario --> UC3 : Marca gif como favorito (id, alias)

UC1 --> Usuario : Devuelve resultados de búsqueda
UC2 --> Usuario : Devuelve gif por ID
UC3 --> Usuario : Confirma que gif fue marcado como favorito

@enduml
```

### Diagrama de secuencia

```UML
actor Usuario
participant "GifController" as Controller
participant "GiphyService" as Service
participant "Giphy API" as API
participant "UserFavoriteGif" as Favorite

Usuario -> Controller: search(query)
Controller -> Controller: handleGifRequest()
Controller -> Service: giphyService->search()
Service -> Service: Verifica si hay ID o query
Service -> API: Request GIF data(GET /v1/gifs/search or /v1/gifs/{id})
API -> Service: Return JSON data
Service -> Controller: Process Response
Controller -> Usuario: Return results or error

Usuario -> Controller: favorite(gif_id, alias)
Controller -> Favorite: Create UserFavoriteGif
Favorite -> Controller: Return created favorite
Controller -> Usuario: Return success JSON
```


## Postman 
```json
{
	"info": {
		"_postman_id": "9711fc67-d521-466d-aa78-45a3bae959ac",
		"name": "Prex",
		"schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json",
		"_exporter_id": "9986552"
	},
	"item": [
		{
			"name": "Gifs",
			"item": [
				{
					"name": "Search",
					"request": {
						"auth": {
							"type": "bearer",
							"bearer": [
								{
									"key": "token",
									"value": "{{TOKEN}}",
									"type": "string"
								}
							]
						},
						"method": "POST",
						"header": [],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"query\": \"messi\"\n}",
							"options": {
								"raw": {
									"language": "json"
								}
							}
						},
						"url": {
							"raw": "http://localhost:80/api/gifs/search",
							"protocol": "http",
							"host": [
								"localhost"
							],
							"port": "80",
							"path": [
								"api",
								"gifs",
								"search"
							]
						}
					},
					"response": []
				},
				{
					"name": "Find",
					"request": {
						"auth": {
							"type": "bearer",
							"bearer": [
								{
									"key": "token",
									"value": "{{TOKEN}}",
									"type": "string"
								}
							]
						},
						"method": "POST",
						"header": [],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"id\": \"TjAcxImn74uoDYVxFl\"\n}",
							"options": {
								"raw": {
									"language": "json"
								}
							}
						},
						"url": {
							"raw": "http://localhost:80/api/gifs/find",
							"protocol": "http",
							"host": [
								"localhost"
							],
							"port": "80",
							"path": [
								"api",
								"gifs",
								"find"
							]
						}
					},
					"response": []
				},
				{
					"name": "Favorite",
					"protocolProfileBehavior": {
						"followRedirects": false
					},
					"request": {
						"auth": {
							"type": "bearer",
							"bearer": [
								{
									"key": "token",
									"value": "{{TOKEN}}",
									"type": "string"
								}
							]
						},
						"method": "POST",
						"header": [],
						"body": {
							"mode": "raw",
							"raw": "{\n    \"user_id\": 1,\n    \"gif_id\": \"TjAcxImn74uoDYVxFl\",\n    \"alias\": \"messi\"\n}",
							"options": {
								"raw": {
									"language": "json"
								}
							}
						},
						"url": {
							"raw": "http://localhost:80/api/gifs/favorite",
							"protocol": "http",
							"host": [
								"localhost"
							],
							"port": "80",
							"path": [
								"api",
								"gifs",
								"favorite"
							]
						}
					},
					"response": []
				}
			]
		},
		{
			"name": "Login",
			"event": [
				{
					"listen": "test",
					"script": {
						"exec": [
							"pm.environment.set(\"TOKEN\", pm.response.json().data.token)"
						],
						"type": "text/javascript",
						"packages": {}
					}
				}
			],
			"request": {
				"method": "POST",
				"header": [],
				"body": {
					"mode": "raw",
					"raw": "{\n    \"email\": \"test@example.com\",\n    \"password\": \"password\"\n}",
					"options": {
						"raw": {
							"language": "json"
						}
					}
				},
				"url": {
					"raw": "http://localhost:80/api/login",
					"protocol": "http",
					"host": [
						"localhost"
					],
					"port": "80",
					"path": [
						"api",
						"login"
					]
				}
			},
			"response": []
		}
	]
}
```

## Logs

Los logs pasan por el [LoggerMiddleware](app/Http/Middleware/LoggerMiddleware.php) que se encarga de logearlos en [laravel.log](storage/logs/laravel.log) De manera local.

Utilizando el siguiente formato:

```
[2024-11-16 04:16:44] local.INFO: api_gifs_favorite {"path":"/api/gifs/favorite","method":"POST","ip":"192.168.65.1","timestamp":"2024-11-16 04:16:44","user_id":1,"request":{"user_id":1,"gif_id":"TjAcxImn74uoDYVxFl","alias":"messi"},"response":{"status":true,"data":{"user_id":1,"alias":"messi","gif_id":"TjAcxImn74uoDYVxFl","updated_at":"2024-11-16T04:16:44.000000Z","created_at":"2024-11-16T04:16:44.000000Z","id":4}}} 
```
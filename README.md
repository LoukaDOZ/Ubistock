# <img src="app/assets/images/logo.png" width="50"/> Ubistock

A simple website to manage a company physical resources like a file system.

## Usage

Require Docker Compose.

| Command         | Description                                          |
|-----------------|------------------------------------------------------|
| `make build`    | Build Docker images                                  |
| `make start`    | Start Docker Compose                                 |
| `make stop`     | Stop Docker Compose                                  |
| `make clean`    | Clean database data (require root) and docker images |
| `make db-clean` | Clean database data (require root)                   |

Project is then accessible though : http://localhost:8080/.

## Docker containers

| Container | Description                                                       | Public port |
|-----------|-------------------------------------------------------------------|-------------|
| `app`     | App entrypoint. One index.html file working with Riot.js and AJAX | 8080        |
| `api`     | PHP REST API                                                      | 8081        |
| `mysqldb` | MySQL DB storage                                                  |             |
| `proxy`   | NGINX reverse proxy for CORS policies                             | 8082        |

## Screenshots

### Home page
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/53941ba9-6c62-4ba3-b86e-7084d7b8246c" width="1000"/>

### Register a new company
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/be2223e9-57dc-4d52-9c4c-ba5ab2179fa8" width="1000"/>

### Login
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/3a1c5bb1-b745-467e-a117-3b22bc2af7eb" width="1000"/>

### Storage
#### File system (⚠️ = minimum quantities not respected)
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/aadb9234-f474-4744-98fc-1b8c94c36e40" width="1000"/>

#### Create and rename storages
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/10a39d6e-590f-4725-91af-bc086220e5e4" width="1000"/>
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/0b9bb636-c7cb-402f-a1d4-61f0968bb62d" width="1000"/>

#### Storages and resources properties
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/1fddbf3d-f3d3-4c77-8c3a-0b1f018342fd" width="1000"/>
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/f698da0e-cb89-4c05-bb54-7a2f4ffcb5f5" width="1000"/>

### Company information
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/a7e74d45-2485-4304-8f5d-0c2e874eff84" width="1000"/>
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/29e20f00-bf10-4f77-bace-a5d41057b610" width="1000"/>

### Members
#### Add
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/dbcd5dba-4bf9-4b6d-999b-bd805f5c07ec" width="1000"/>

#### List
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/959ab7ed-2343-4787-b528-a12394d6ade5" width="1000"/>

#### Edit
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/c53fea53-00b2-48c1-a92c-faf0d9ceeff5" width="1000"/>

### Groups
#### Add
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/c6b0f778-d1a5-40f2-9e74-11865c0b4c4e" width="1000"/>

#### Storage explorer
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/158bdfc2-4ba5-4399-bb05-134f3bc73e8b" width="1000"/>

#### List
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/e5c4f5f5-9e7a-4bc5-ad34-323b6f7ed89a" width="1000"/>

#### Edit
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/30711a03-489f-43b8-8c62-6a77c1846767" width="1000"/>

### Logs
#### List
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/a7929f68-d8fe-41bf-bfa0-fd6db2f34e50" width="1000"/>

#### More information
<img src="https://github.com/LoukaDOZ/Ubistock/assets/46566140/5f533b88-60cf-4687-9806-4f6116b0e2cf" width="1000"/>

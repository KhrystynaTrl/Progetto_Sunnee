# Progetto Sunnee

## Descrizione

Progetto Sunnee è un backend PHP pensato per la gestione di ordini e prodotti, con particolare attenzione alla sostenibilità (monitorando i kg di materiale riciclato associati a ciascun prodotto).  
Tramite API RESTful, permette di gestire le operazioni CRUD su prodotti e ordini e di effettuare ricerche avanzate sull’impatto ambientale.

---

## Struttura del progetto

```
src/
├── config/
│   ├── container.php         # Dependency Injection Container
│   └── database.php          # Configurazione e connessione PDO a MySQL
├── controllers/
│   ├── OrderController.php   # Gestione richieste per ordini
│   └── ProductController.php # Gestione richieste per prodotti
├── core/
│   ├── bootstrap.php         # Gestione operazioni iniziali e inizializzazione container DI
│   ├── Request.php           # Classe di utilità per prendere i dati della request
│   ├── Response.php          # Classe di utilità per inviare i dati della response
│   └── Router.php            # Routing delle richieste HTTP
├── models/
│   ├── order.php             # Modello Ordine, operazioni CRUD + ricerca
│   └── product.php           # Modello Prodotto, operazioni CRUD
├── routes/
│   └── web.php               # Definizione delle route
└── index.php                 # Entry point principale
```

---

## Struttura delle tabelle SQL

```sql
CREATE DATABASE Sunnee;
USE Sunnee;

CREATE TABLE IF NOT EXISTS Product(
ID INT auto_increment PRIMARY KEY,
`name` varchar(25) NOT NULL UNIQUE,
kg_recycled float(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS `Order`(
ID INT auto_increment PRIMARY KEY,
date_of_sale date DEFAULT (now()),
quantity int check (quantity > 0) NOT NULL,
product int NOT NULL,
foreign key (product) references Product(ID)
);
```

### Scelta della relazione one-to-many tra ordine e prodotto

Ho deciso di adottare una relazione **one-to-many** tra ordine e prodotto nel progetto Sunnee per rispondere efficacemente alle esigenze di gestione degli ordini di costumi da bagno. In questa configurazione, ogni ordine è associato ad un singolo prodotto (modello di costume), mentre ogni prodotto può comparire in molti ordini diversi.

Questa scelta riflette il funzionamento reale dell’applicazione: ogni volta che viene effettuato un ordine, esso riguarda una quantità specifica di un determinato modello di costume. In questo modo è possibile:

- tracciare con precisione quali e quanti costumi di ciascun tipo sono stati ordinati,
- monitorare l’impatto ambientale per ogni modello (grazie ai kg di plastica riciclata associati al prodotto),
- semplificare la struttura del database, evitando complessità inutili nella gestione degli ordini.

**Note:**

- `product.name` è un campo univoco, utile per le ricerche e per evitare duplicati.
- `kg_recycled` tiene traccia del materiale riciclato per prodotto.
- `order.date_of_sale` è valorizzato di default con la data corrente.
- `quantity` deve essere sempre positiva (CHECK).
- La relazione tra ordine e prodotto è gestita tramite chiave esterna (`product`).

## Funzionamento generale

- **index.php**: entry point; carica le variabili d’ambiente, la configurazione, il router e le route, poi effettua il dispatch della richiesta HTTP.
- **Router**: mappa URL come `/product` e `/order` ai rispettivi controller.
- **Response&Request**: gestiscono le richieste e le risposte http.
- **Container**: gestisce la dependency injection all'interno dell'applicazione.
- **Controllers**: in base al metodo HTTP, instradano la richiesta al model corretto (ad esempio: creazione, lettura, aggiornamento, eliminazione, ricerca).
- **Models**: interagiscono direttamente con il database, gestendo le entità prodotto e ordine.

---

## API Endpoints

### Prodotti

| Metodo | Endpoint            | Descrizione       | Payload richiesto/parametro                        |
| ------ | ------------------- | ----------------- | -------------------------------------------------- |
| GET    | `/product`          | Lista prodotti    | -                                                  |
| POST   | `/product`          | Crea prodotto     | `{ "name": "...", "kg_recycled": ... }`            |
| PUT    | `/product`          | Aggiorna prodotto | `{ "ID": ..., "name": "...", "kg_recycled": ... }` |
| DELETE | `/product?name=...` | Elimina prodotto  | Parametro `name` in query string                   |

### Ordini

| Metodo | Endpoint        | Descrizione       | Payload richiesto/parametro                                              |
| ------ | --------------- | ----------------- | ------------------------------------------------------------------------ |
| GET    | `/order`        | Lista ordini      | -                                                                        |
| POST   | `/order`        | Crea ordine       | `{ "product": "...", "quantity": ..."date_of_Sale":"gg/mm/aaaa" }`       |
| PUT    | `/order`        | Aggiorna ordine   | `{ "ID": ..., "product": "...", "quantity": ... }`                       |
| DELETE | `/order?id=...` | Elimina ordine    | Parametro `id` in query string                                           |
| POST   | `/order/search` | Ricerca aggregata | `{ "dateFrom": "gg/mm/aaaa", "fateTo": "gg/mm/aaaa", "product": "..." }` |

---

## Esempi di richieste API

### Creazione prodotto (costume da bagno donna)

```json
POST /product
{
  "name": "beachware for woman",
  "kg_recycled": 1.3
}
```

### Creazione prodotto (costume da bagno uomo)

```json
POST /product
{
  "name": "beachware for men",
  "kg_recycled": 1.0
}
```

### Creazione ordine

```json
POST /order
{
  "product": "beachware for woman",
  "quantita": 5,
  "date_of_sale": "01/09/2025"
}
```

### Ricerca aggregata ordini

```json
POST /order/search
{
  "dateFrom": "01/01/2025",
  "dateTo": "01/09/2025",
  "product": "beachware for woman"
}
```

### Risposta per lista prodotti

```json
[
  {
    "name": "beachware for woman",
    "kg_recycled": 2.5
  }
]
```

---

## Installazione

1. Clona il repository:
   ```bash
   git clone https://github.com/KhrystynaTrl/Progetto_Sunnee.git
   ```
2. Crea un file `.env` nella root del progetto e configura le variabili:
   ```
   DB_HOST=localhost
   DB_NAME=nome_database
   USER=nome_utente
   PASSWORD=la_password
   ```
3. Installa le dipendenze con il comando
   ```bash
    composer install
   ```
4. Crea il pacchetto vendor con autoload
   ```bash
    composer dump-autoload
   ```
5. Avvia il server PHP:
   ```bash
   php -S localhost:8000 -t src/index.php
   ```
6. Le API saranno accessibili su `http://localhost:8000/product` e `http://localhost:8000/order`.

---

## Dipendenze

- PHP >= 7.4
- Estensione PDO per MySQL
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) per la gestione delle variabili d’ambiente (già referenziata in `index.php`)

---

## Autori

- KhrystynaTrl

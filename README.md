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
│   ├── OrdiniController.php  # Gestione richieste per ordini
│   └── ProdottiController.php# Gestione richieste per prodotti
├── core/
│   └── Router.php            # Routing delle richieste HTTP
├── models/
│   ├── ordine.php            # Modello Ordine, operazioni CRUD + ricerca
│   └── prodotto.php          # Modello Prodotto, operazioni CRUD
├── repositories/
│   ├── ordini/               # CRUD e ricerca ordini
│   └── prodotti/             # CRUD prodotti
├── routes/
│   └── web.php               # Definizione delle route
└── index.php                 # Entry point principale
```

---

## Struttura delle tabelle SQL

```sql
CREATE DATABASE Sunnee;
USE Sunnee;

CREATE TABLE IF NOT EXISTS prodotto(
ID INT auto_increment PRIMARY KEY,
nome VARCHAR(25) NOT NULL UNIQUE,
kg_riciclati FLOAT(2) NOT NULL
);

CREATE TABLE IF NOT EXISTS ordine(
ID INT auto_increment PRIMARY KEY,
data_di_vendita DATE DEFAULT (now()),
quantita INT CHECK (quantita > 0) NOT NULL,
prodotto INT NOT NULL,
FOREIGN KEY (prodotto) REFERENCES prodotto(ID)
);
```

### Scelta della relazione one-to-many tra ordine e prodotto

Ho deciso di adottare una relazione **one-to-many** tra ordine e prodotto nel progetto Sunnee per rispondere efficacemente alle esigenze di gestione degli ordini di costumi da bagno. In questa configurazione, ogni ordine è associato ad un singolo prodotto (modello di costume), mentre ogni prodotto può comparire in molti ordini diversi.

Questa scelta riflette il funzionamento reale dell’applicazione: ogni volta che viene effettuato un ordine, esso riguarda una quantità specifica di un determinato modello di costume. In questo modo è possibile:

- tracciare con precisione quali e quanti costumi di ciascun tipo sono stati ordinati,
- monitorare l’impatto ambientale per ogni modello (grazie ai kg di plastica riciclata associati al prodotto),
- semplificare la struttura del database, evitando complessità inutili nella gestione degli ordini.

**Note:**

- `prodotto.nome` è un campo univoco, utile per le ricerche e per evitare duplicati.
- `kg_riciclati` tiene traccia del materiale riciclato per prodotto.
- `ordine.data_di_vendita` è valorizzato di default con la data corrente.
- `quantita` deve essere sempre positiva (CHECK).
- La relazione tra ordine e prodotto è gestita tramite chiave esterna (`prodotto`).

## Funzionamento generale

- **index.php**: entry point; carica le variabili d’ambiente, la configurazione, il router e le route, poi effettua il dispatch della richiesta HTTP.
- **Router**: mappa URL come `/prodotto` e `/ordine` ai rispettivi controller.
- **Controllers**: in base al metodo HTTP, instradano la richiesta al repository corretto (ad esempio: creazione, lettura, aggiornamento, eliminazione, ricerca).
- **Repositories**: gestiscono la logica di accesso ai dati tramite i modelli e restituiscono risposte JSON.
- **Modelli**: interagiscono direttamente con il database, gestendo le entità prodotto e ordine.

---

## API Endpoints

### Prodotti

| Metodo | Endpoint             | Descrizione       | Payload richiesto/parametro                         |
| ------ | -------------------- | ----------------- | --------------------------------------------------- |
| GET    | `/prodotto`          | Lista prodotti    | -                                                   |
| POST   | `/prodotto`          | Crea prodotto     | `{ "nome": "...", "kg_riciclati": ... }`            |
| PUT    | `/prodotto`          | Aggiorna prodotto | `{ "ID": ..., "nome": "...", "kg_riciclati": ... }` |
| DELETE | `/prodotto?nome=...` | Elimina prodotto  | Parametro `nome` in query string                    |

### Ordini

| Metodo | Endpoint          | Descrizione       | Payload richiesto/parametro                                               |
| ------ | ----------------- | ----------------- | ------------------------------------------------------------------------- |
| GET    | `/ordine`         | Lista ordini      | -                                                                         |
| POST   | `/ordine`         | Crea ordine       | `{ "prodotto": "...", "quantita": ..."data_di_vendita":"gg/mm/aaaa" }`    |
| PUT    | `/ordine`         | Aggiorna ordine   | `{ "ID": ..., "prodotto": "...", "quantita": ... }`                       |
| DELETE | `/ordine?ID=...`  | Elimina ordine    | Parametro `ID` in query string                                            |
| POST   | `/ordine/ricerca` | Ricerca aggregata | `{ "dataFrom": "gg/mm/aaaa", "dataTo": "gg/mm/aaaa", "prodotto": "..." }` |

---

## Esempi di richieste API

### Creazione prodotto (costume da bagno donna)

```json
POST /prodotto
{
  "nome": "costume_donna_active",
  "kg_riciclati": 1.3
}
```

### Creazione prodotto (costume da bagno uomo)

```json
POST /prodotto
{
  "nome": "costume_uomo_active",
  "kg_riciclati": 1.0
}
```

### Creazione ordine

```json
POST /ordine
{
  "prodotto": "costume_donna_active",
  "quantita": 5,
  "data_di_vendita": "01/09/2025"
}
```

### Ricerca aggregata ordini

```json
POST /ordine/ricerca
{
  "dataFrom": "01/01/2025",
  "dataTo": "01/09/2025",
  "prodotto": "costume_donna_active"
}
```

### Risposta per lista prodotti

```json
{
  "records": [
    {
      "nome": "costume_donna_active",
      "kg_riciclati": 2.5
    }
  ]
}
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
6. Le API saranno accessibili su `http://localhost:8000/prodotto` e `http://localhost:8000/ordine`.

---

## Dipendenze

- PHP >= 7.4
- Estensione PDO per MySQL
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) per la gestione delle variabili d’ambiente (già referenziata in `index.php`)

---

## Autori

- KhrystynaTrl

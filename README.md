# 🔌 EV Charge Monitor

This project is a simple microservice-based application to **monitor electric vehicle charging state**. It includes a **Vue.js frontend**, a **Laravel API backend**, and a **simulator** that generates synthetic charging data. The application uses **PostgreSQL** as its database.

---

## 📐 Architecture

```mermaid
flowchart TD
    subgraph Front
        FE["Vue"]
    end

    subgraph Back
        API["REST API"]
    end

    subgraph External
        Simulator
    end

    subgraph Storage ["Postgres Pod"]
        DB["PostgreSQL DB"]
    end

    FE <-->|Users management, data collection| API
    Simulator -->|Simulated Charge Data| API
    API -->|DB Read/Write| DB

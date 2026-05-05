# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Portfolio website for Lamosca graphic design studio. Database-driven CMS with project/category management, admin panel, and community mosaic feature.

**Stack:** PHP 8.x (migrated from PHP 5.x), MySQL 8.0, Apache mod_rewrite, Prototype.js 1.5.0

## Running the Application (Docker)

```bash
# Start the environment
docker-compose up -d

# View logs
docker-compose logs -f web

# Access the container
docker-compose exec web bash

# Stop the environment
docker-compose down
```

**URLs:**
- Website: http://localhost:8080
- Admin: http://localhost:8080/admin/
- phpMyAdmin: http://localhost:8081

**Database Setup:**
1. Copy your SQL dump to `.docker/init.sql`
2. Run `docker-compose up -d` (auto-imports on first run)
3. Or import manually via phpMyAdmin

## Configuration

Database credentials in `.env` file (not tracked in git):
```
DB_HOST=db
DB_NAME=weblamosca
DB_USER=mylamosca
DB_PASS=lamosca123
```

## Architecture

### Database Abstraction Layer
`phpincludes/database.php` provides mysqli wrapper functions:
- `db_connect()` - Establish connection
- `db_query($dbname, $sql)` - Execute query
- `db_fetch_row($result)` - Fetch row as indexed array
- `db_fetch_array($result)` - Fetch row as associative array
- `db_num_rows($result)` - Get row count
- `db_escape($string)` - Escape string for SQL
- `db_close()` - Close connection

Backwards compatibility wrappers (deprecated) also available for gradual migration.

### URL Routing (.htaccess)
```
/[category]/[project].html  -> index.php?curl=[category]&purl=[project]
/[category]/                -> index.php?curl=[category]
/mosaic/                    -> /mosaic/index.php
/admin/                     -> /admin/index.php
/rss.xml                    -> /ext/rss.php
```

### Entry Points
- `/index.php` - Main public website
- `/admin/index.php` - Admin interface for managing content
- `/mosaic/index.php` - Community mosaic feature (56-column grid)
- `/ext/rss.php` - RSS feed generator

### Core Files
- `phpincludes/database.php` - Database abstraction layer (mysqli)
- `phpincludes/functions.php` - Main helpers: `prepXml()`, `buildModules()`
- `admin/functions.php` - Admin helpers: `module_listing()`, `project_select_listing()`

### Database Schema
- `categories` - id, title, position, content (comma-separated project IDs)
- `projects` - id, title, active, modules (comma-separated module IDs), inturl, rss_date, text_1-4
- `modules` - id, title, image, imagetype, width, height, text_* fields, thumb, link
- `mosaic` - id, nom, mail, web, comentari, color, data

### Key Directories
- `phpincludes/` - Core application logic and helpers
- `admin/` - Admin panel with WhizzYWig WYSIWYG editor
- `mosaic/` - Community mosaic feature
- `img/projects/` - Project images
- `.docker/` - Docker configuration and SQL init

## Migration Notes (PHP 5.x to 8.x)

The following changes were made:
- Replaced `mysql_*` functions with mysqli via `phpincludes/database.php`
- Replaced `extract($_GET)`/`extract($_POST)` with explicit variable assignment
- Database credentials moved to environment variables

Backup files (index_.php, index2.php, etc.) have NOT been migrated and should be deleted or updated if needed.

## Disseny amb Gemini AI

Per tasques de disseny visual (UI, layouts, components), utilitza Gemini com a col·laborador de disseny.

### Configuració
La clau API de Gemini es guarda al fitxer `.env` (no commitejat):
```
GEMINI_API_KEY=la_teva_clau_aqui
```

Carrega-la abans d'usar:
```bash
source .env 2>/dev/null || export GEMINI_API_KEY=$(grep GEMINI_API_KEY .env | cut -d= -f2)
```

### Workflow de Disseny

1. **Quan necessitis dissenyar** una interfície, component o layout:

```bash
curl -s "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$GEMINI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "contents": [{
      "parts": [{"text": "Ets un dissenyador UI/UX expert. Dissenya: [DESCRIPCIÓ DEL DISSENY]. Proporciona: 1) Estructura HTML semàntica, 2) Estils CSS moderns, 3) Paleta de colors, 4) Consideracions de responsivitat."}]
    }]
  }' | jq -r '.candidates[0].content.parts[0].text'
```

2. **Mostra el disseny** a l'usuari per aprovació
3. **Implementa el codi** basat en la resposta de Gemini

### Exemple d'ús
```bash
# Dissenyar un botó modern
curl -s "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=$GEMINI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "contents": [{
      "parts": [{"text": "Dissenya un botó CTA modern per una web de portfolio de disseny gràfic. Estil minimalista, colors neutres."}]
    }]
  }' | jq -r '.candidates[0].content.parts[0].text'
```

### Notes
- Gemini s'encarrega del disseny visual i decisions estètiques
- Claude s'encarrega de la implementació del codi i la integració
- Sempre mostrar la proposta de disseny a l'usuari abans d'implementar

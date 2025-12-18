# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Legacy PHP 5.x portfolio website for Lamosca graphic design studio. Database-driven CMS with project/category management, admin panel, and community mosaic feature.

**Stack:** PHP 5.x, MySQL, Apache mod_rewrite, Prototype.js 1.5.0, Script.aculo.us

## Running the Application

No build process - direct PHP execution. Requires:
- Apache with mod_rewrite enabled
- PHP 5.x (uses deprecated mysql_* functions - incompatible with PHP 7+)
- MySQL database

**Database credentials** are in:
- `phpincludes/functions.php` (lines 10-27)
- `admin/functions.php` (lines 8-19)

## Architecture

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
- `phpincludes/functions.php` - Main helpers: `prepXml()`, `buildModules()`, database functions
- `admin/functions.php` - Admin helpers: `module_listing()`, `project_select_listing()`, `cleanfilename()`
- `Mobile_Detect.php` - Mobile device detection library

### Database Schema
- `categories` - id, title, position, content (comma-separated project IDs)
- `projects` - id, title, active, modules (comma-separated module IDs), inturl, rss_date, text_1-4
- `modules` - id, title, image, imagetype, width, height, text_* fields, thumb, link
- `mosaic` - id, nom, mail, web, comentari, color, data

### Key Directories
- `phpincludes/` - Core application logic and helpers
- `admin/` - Admin panel with WhizzYWig WYSIWYG editor
- `mosaic/` - Community mosaic feature
- `img/projects/` - Project images (136 subdirectories)
- `tipo/` - Custom webfonts (lmsc family in EOT, TTF, WOFF, WOFF2, SVG)
- `js/` - Prototype.js framework and custom scripts

## Legacy Concerns

This codebase uses deprecated patterns incompatible with modern PHP:
- `mysql_*` functions (removed in PHP 7.0)
- `extract()` on `$_GET`/`$_POST`
- Raw SQL concatenation without prepared statements
- Hardcoded database credentials

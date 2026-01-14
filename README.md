# VTTLib - Modern Library Management System

VTTLib is a robust, modern Library Management System (LMS) built on the Laravel framework. It features a sophisticated bibliographic management system based on the **MARC21 Standard**, providing librarians with a flexible and powerful interface for cataloging and framework management.

## 🚀 Key Features

### 1. MARC21 Framework Manager
- **Dynamic Tag Management**: Register, edit, and delete MARC tags (e.g., 100, 245, 650).
- **Subfield Definition**: Define specific subfields for each tag with custom labels, visibility toggles, and constraints (Mandatory/Repeatable).
- **Smart UI**: Collapsible tag cards with quick search functionality for efficient framework navigation.

### 2. Advanced Bibliographic Cataloging
- **Staggered Form Design**: A minimalist cataloging interface that allows users to add only the subfields they need.
- **Dynamic Rows**: Select subfields from a dropdown and enter data on-the-fly using an Alpine.js-powered dynamic row system.
- **Repeatable Fields**: Effortlessly handle repeatable MARC tags and subfields.

### 3. Approval Workflow
- **Quality Control**: All newly cataloged records are initially saved in a **Pending** status.
- **Administrative Review**: A dedicated review interface allows supervisors to inspect the full MARC21 data with human-readable labels before changing the status to **Approved**.
- **Audit Ready**: Tracks creation and modification timestamps for every record.

### 4. User Experience (UI/UX)
- **Premium Design**: Built with Tailwind CSS for a professional, "fluid" interface that utilizes the full width of the screen.
- **Interactivity**: Powered by Alpine.js for smooth, server-less interactions.
- **Localization**: Full support for both **English** and **Vietnamese** out of the box.

## 🛠 Tech Stack
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: Tailwind CSS, Alpine.js, Blade Templating
- **Standard**: MARC21 Bibliographic Format

## 📂 Project Structure (Data Model)
- `BibliographicRecord`: The main record container (stores Leader and Record Type).
- `MarcField`: Stores the Tag ID (e.g., 245) and Indicators (Ind1, Ind2).
- `MarcSubfield`: Stores the Subfield Code (e.g., $a) and the actual Data Value.
- `MarcTagDefinition`: Stores framework definitions for Tags.
- `MarcSubfieldDefinition`: Stores framework definitions for Subfields within Tags.

## 🚦 Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Installation
1. Clone the repository.
2. Run `composer install` and `npm install`.
3. Configure your `.env` file with database credentials.
4. Run migrations: `php artisan migrate`.
5. Seed the database (optional): `php artisan db:seed`.
6. Start the server: `php artisan serve`.

## 📄 License
This project is proprietary software. All rights reserved.

# Technical Documentation - MARC21 Cataloging System

This document provides a technical overview of how VTTLib handles MARC21 bibliographic data and the cataloging workflow.

## 1. Data Schema Architecture

The system uses a normalized relational structure to represent the flexible nature of MARC21.

### Bibliographic Records
- **Table**: `bibliographic_records`
- **Fields**: `id`, `leader` (24 bytes), `record_type` (e.g., book, thesis), `status` (pending/approved).
- **Model**: `App\Models\BibliographicRecord`

### MARC Fields (Tags)
- **Table**: `marc_fields`
- **Fields**: `id`, `record_id` (FK), `tag` (3 chars), `indicator1` (1 char), `indicator2` (1 char), `sequence`.
- **Model**: `App\Models\MarcField`

### MARC Subfields
- **Table**: `marc_subfields`
- **Fields**: `id`, `field_id` (FK), `code` (1 char), `value` (text).
- **Model**: `App\Models\MarcSubfield`

---

## 2. Dynamic Cataloging Logic

### Frontend (Alpine.js)
The cataloging form (`admin.marc_books.create`) uses an Alpine.js object to manage "staggered" input rows:
- Each MARC tag starts with one empty row.
- A row consists of a `<select>` for subfield codes ($a, $b, $c...) and an `<input>` for the value.
- Subfield definitions are injected into Alpine.js via JSON: `definitions: {{ $tag->subfields->toJson() }}`.
- Users can push new rows dynamically: `@click="rows.push({ code: '', value: '' })"`.

### Backend (Laravel Controller)
The `MarcBookController@store` method uses a robust processing logic:
1. **Validation**: Checks if subfields are provided.
2. **Database Transaction**: Wraps the entire creation process in `DB::beginTransaction()` to ensure atomicity.
3. **Filtering**: Automatically ignores empty rows or tags with no subfield data.
4. **Data Injection**: Creates the `BibliographicRecord` first, then iterates through tags and their subfields to build the hierarchy.

---

## 3. Framework Management

Librarians can configure the "Framework" (schema) without touching the code.
- **Tag Visibility**: Toggling `is_visible` on a Tag will show/hide it in the cataloging form.
- **Subfield Visibility**: Toggling `is_visible` on a Subfield ensures only relevant sub-items are shown in the selection dropdown during cataloging.

---

## 4. Status Transition Workflow

Records follow a strict approval state machine:
- `pending`: Default state after cataloging. Visible in the "Cataloged Records" list but highlighted as needing review.
- `approved`: Updated via the Review page (`admin.marc_books.show`). 

The logic is enforced in `MarcBookController@updateStatus`.

---

## 5. UI/UX Standards
- All containers use `w-full` for a fluid, high-productivity layout.
- Consistent color coding:
    - **Indigo**: Primary actions / Framework branding.
    - **Amber**: Pending status / Warnings.
    - **Emerald**: Approved status / Success.
    - **Rose**: Destructive actions / Critical constraints.

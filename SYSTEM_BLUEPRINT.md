# LGU Burial System: Technical Blueprint & Architecture

This document serves as the "Source of Truth" for the LGU Burial System (Carmen, Davao del Norte). It is designed to be ingested by an AI or human developer to understand the entire system's structure, logic, and nuances.

---

## 🏗️ 1. System Identity & Stack
- **Project Name**: LGU Burial System
- **Organization**: Municipal Civil Registrar, LGU Carmen
- **Core Strategy**: A digital transformation from manual ledger-based burial tracking to a unified Geographic Information System (GIS).
- **Tech Stack**:
    - **Backend**: Laravel 11.x (PHP 8.2+)
    - **Database**: PostgreSQL/MySQL (Production) / SQLite (Local/Dev)
    - **Frontend**: React 19.x, Tailwind CSS 4.x, Framer Motion
    - **GIS/Mapping**: MapLibre GL, Lucide Icons
    - **Reporting**: DomPDF (PDFs), Maatwebsite/Excel (XLSX)

---

## 🗄️ 2. Domain Models & Data Schema

### `DeceasedPerson`
The base identity record.
- **Keys**: `first_name`, `middle_name`, `last_name`, `name_extension`, `name_number`, `phone_number`, `date_of_birth`, `date_of_death`, `nationality`, `age`, `sex`, `civil_status`, `religion`, `kind_of_burial`, `place_of_death`, `cause_of_death`, `address`, `age_at_death`.
- **Logic**: Used as the primary lookup for all permits. Scanned for "swapped names" (short last, long first) via Data Quality logic.

### `BurialPermit`
The life-cycle engine of the system.
- **Keys**: `permit_number`, `deceased_id`, `permit_type`, `kind_of_burial`, `applicant_name`, `applicant_relationship`, `applicant_contact`, `applicant_address`, `issued_date`, `expiry_date`, `status`, `remarks`, `processed_by`, `issued_by`, `or_number`, `renewal_count`.
- **Statuses**: 
    - `active`: Valid permit.
    - `expiring`: Transitions to this state approaching expiry.
    - `expired`: Read-only/Requires renewal.
- **Categorization**: `cemented`, `niche_1st`, `niche_2nd`, `niche_3rd`, `niche_4th`, `bone_niches`.
- **Renewal**: Permits can be renewed, incrementing `renewal_count` and extending `expiry_date` based on permit settings.

### `CemeteryPlot` & `CemeteryGrid`
The physical manifestation.
- **Geomap**: Uses Long/Lat or local coordinates for physical spots.
- **Niche Grid**: Represents vertical structures (Apartment-style niches). Handles multi-floor matrix configurations storing arrays of occupied/available slots.

---

## 🧠 3. Core Business Logic (The "Callouts")

> [!IMPORTANT]
> **[PENDING] Permit Status Engine (Dynamic Calculation)**: The blueprint planned for statuses to be dynamically calculated against current dates. However, the system is currently using a **static database column** (`status`) populated upon creation or manual updates. The dynamic date-based status engine is planned but not currently governing read-time scopes.

> [!NOTE]
> **Data Quality Scanner (11 Point Scan)**:
> All 11 points are fully implemented and functional within `SettingsController@dataQualityScan`:
> 1. Duplicate Permits (Same Deceased).
> 2. Duplicate Permit Numbers.
> 3. Missing Deceased Links.
> 4. Missing Date of Death.
> 5. Placeholder Applicant Names ("Unknown").
> 6. Active Permits missing Expiry Dates.
> 7. Swapped Names (First ↔ Last).
> 8. Invalid Permit Types.
> 9. Invalid Ages (<= 0).
> 10. Future Dates of Death.
> 11. Identical Full Name matches.

> [!TIP]
> **Settings Storage**: Unlike most Laravel apps, configuration (Fees, Municipal Names, Registrar Info) is stored in `storage/app/settings.json` for rapid, database-free retrieval.

---

## 🛠️ 4. Utility Scripts
Several scripts exist at the system root for rapid infrastructure debugging and mapping population:
- **`sync_map.php`**: A database seeder that auto-assigns existing burial permits to newly drawn Map Grid niches with a ~30% slot occupancy distribution. Truncates legacy plot data.
- **`check_grids.php`**: A command-line script to pretty-print or parse complex nested JSON cell arrays embedded inside generated `CemeteryGrid` maps.

---

## 🎨 5. Frontend & UX Patterns
- **SuperAdmin Dashboard**: A "Glassmorphic" command center featuring real-time stats (Chart.js), activity feeds, and quick exports.
- **Geomap Analytics**: A React-driven `main.tsx` engine that mounts on `/cemetery/map`. It visualizes occupancy levels in real-time.
- **Activity Log**: Every `created`, `updated`, `renewed`, and `deleted` action is recorded with "Old vs New" value comparisons for audit trails.

---

## 📡 6. Integrations & Utility
- **SMS System**: Managed by `SmsController`. Used to notify applicants about permit approvals or upcoming expirations.
- **Importer**: Supports bulk Excel imports. Validates data quality during ingestion and records logs in `import_logs`.
- **PDF Engine**: Generates "Premium" reports (`permit.docx` templates converted to PDFs) with official headers.

---

## 📂 7. Folder Architecture Map
- `app/Http/Controllers`: All logic for Mapping, Permits, and Data Quality.
- `app/Models`: Heavy use of Eloquent relations (`BurialPermit -> DeceasedPerson`).
- `database/migrations`: Version-controlled schema including recent Niche Grid geometry additions.
- `resources/js/geomap`: The entire React GIS codebase.
- `resources/views/superadmin` & `resources/views/admin`: Custom Blade architecture using `partials.sidebar` and custom system modals.

---

## 💡 8. AI Context (The "Brain" Prompt)
*If you are an AI working on this system, prioritize:*
1. **Consistency**: Ensure permit statuses follow the `active -> expiring -> expired` flow.
2. **Data Integrity**: Always use the `ActivityLog::record()` method when modifying data.
3. **Roles**: Both `admin` and `super_admin` can access the system, but certain features (like full data quality views or system-wide settings resets) may be restricted.
4. **Geomap**: Any changes to maps must consider the React component boundaries in `resources/js/geomap`. State is managed via `zustand` (`useMapStore.ts`).
5. **Renewal Flow**: Renewing a permit resets its status to `active` and generates a new `expiry_date`.

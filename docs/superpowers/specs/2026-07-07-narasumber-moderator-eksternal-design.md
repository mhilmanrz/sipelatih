# Narasumber & Moderator Eksternal — Design Spec

Date: 2026-07-07

## Problem

Narasumber and moderator on a kegiatan (`ActivitySpeaker`, `ActivityModerator`) currently always reference `users.id`, which implicitly assumes a pegawai (internal staff with `work_unit_id`, `position_id`, `employee_id`, etc.). In practice, narasumber and moderator can be external people — not employees — who still need to be recorded (name, identity numbers, honor payment details) and referenced from kegiatan documents (Surat Tugas, Nota Dinas), but who never log into the system.

## Decision: extend `users`, don't fork the model

Considered a separate `external_persons` table with a polymorphic relation from `activity_speakers`/`activity_moderators`. Rejected: it would touch every place that currently does `->user` (Surat Tugas, Nota Dinas, budget, PDF templates, the `select-user` Tom Select component, `/users/search`), for a problem that's really just "some users don't log in and belong to an outside institution." The existing `users` table already has all the pegawai-specific columns as nullable, so external records fit without schema violence.

Chosen approach: add columns to `users`, tag external rows with `is_external`, and use two new Spatie roles purely as classification tags (no permissions, no login implication).

## Data model changes

Migration on `users`:

| Column | Type | Notes |
|---|---|---|
| `is_external` | boolean, default `false` | Drives conditional rendering in documents and the directory list scope. |
| `nik` | string, nullable | Not previously tracked. |
| `institution` | string, nullable | Instansi asal. |
| `external_position` | string, nullable | Jabatan at the instansi asal (distinct from `position_id`, which refers to internal `positions`). |

Reused, no schema change:
- `employee_id` — already aliased via `User::getNipAttribute()` as NIP. External people who happen to hold a NIP (e.g. staff borrowed from another instansi) can fill it; otherwise left null.
- `npwp` — already exists (added for honor/payment). Reused as-is.
- `password` — set to `Hash::make(Str::random(32))` on create; external rows never authenticate, so no login UI or credential distribution for them.
- `email` — stays `unique`, `not null`. If left blank on the form, auto-generate a unique placeholder (e.g. `slug(name)-{uniqid}@external.local`) so the DB constraint holds.

## Roles

Two new Spatie roles, `narasumber eksternal` and `moderator eksternal`, added in `RolePermissionSeeder`:
- **Zero permissions assigned.** They exist only to classify a person's capacity, not to grant menu/panel access.
- Safe against existing role-based gates: `isPegawai()` and the `hasAnyRole([...])` checks elsewhere use explicit whitelists of the five stage roles, so these new roles never satisfy them. Moot anyway since external rows never log in.
- Assigned manually from the new directory form via two checkboxes ("Narasumber", "Moderator" — not mutually exclusive, a person can hold both).

## New menu: "Narasumber/Moderator Eksternal" directory

A master-data CRUD page, scoped to `users` where `is_external = true`.

- **List**: name, institution, external_position, phone, capacity (role badges).
- **Create/Edit form** fields: nama, NIK, NIP (optional), NPWP, instansi asal, jabatan asal, no HP, email (optional), checkboxes for narasumber/moderator capacity.
- On save: set `is_external = true`, sync the two capacity roles from the checkboxes, generate password/email placeholder as above.
- Permission-gated the same way other master data menus are (new `view external persons` permission, or reuse an existing master-data permission — implementer's call during planning).

## No change to kegiatan selection flow

The narasumber/moderator pickers on the kegiatan detail page (`x-select-user`, `/users/search`) keep searching across all users, internal and external mixed, exactly as today. No new UI branching needed there — admins pick from one list regardless of origin.

## Document rendering (Surat Tugas / Nota Dinas)

Both currently render a table row per speaker/moderator pulling `employee_id`, `rank->name` (pangkat/golongan), `position->name` (jabatan) — all pegawai-only relations that will be null for external rows.

Change: branch on `$user->is_external`:
- **Internal** (current behavior, unchanged): NIP (`employee_id`), Pangkat/Golongan (`rank->name`), Jabatan (`position->name`).
- **External**: NIP (`employee_id`, if filled), Instansi Asal (`institution`), Jabatan (`external_position`).

Wherever the document also surfaces NPWP for honor/tax purposes (Nota Dinas honor section), include it for external rows the same as internal — it's the same column, already populated for both.

## Out of scope

- No login/portal for external narasumber/moderator — confirmed not needed.
- No polymorphic relation / new pivot tables — `activity_speakers`/`activity_moderators` keep their existing `user_id` FK untouched.
- No change to `/users/search` filtering logic (`exclude_roles` param stays as-is; nothing currently passes it as `true` in a blade, so it's inert either way).

## Open items for the implementation plan

- Exact permission name for the new directory menu (new permission vs. reusing an existing master-data one) and which existing roles get it by default (likely `superadmin` + `penyelenggara`, matching who manages narasumber/moderator today).
- Whether the placeholder-email generator needs a helper/trait, or is fine inlined in the controller's store method.
- Test coverage: feature test for the directory CRUD + role sync, and a check that Surat Tugas/Nota Dinas render the external branch correctly for a seeded external speaker.

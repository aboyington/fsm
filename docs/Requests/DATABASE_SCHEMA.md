# Database Schema - Requests Module

## `requests` Table

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | INT(11) | No | | Primary Key |
| `request_number` | VARCHAR(255) | No | | Unique request identifier |
| `request_name` | VARCHAR(255) | No | | Name of the request |
| `company_id` | INT(11) | Yes | NULL | Foreign key to `clients` table |
| `contact_id` | INT(11) | Yes | NULL | Foreign key to `contacts` table |
| `status` | VARCHAR(50) | No | pending | Request status |
| `priority` | VARCHAR(50) | No | medium | Request priority |
| `description` | TEXT | Yes | NULL | Detailed description |
| `created_at` | DATETIME | No | | Timestamp of creation |
| `updated_at` | DATETIME | No | | Timestamp of last update |
| `created_by` | INT(11) | No | | Foreign key to `users` table |
| `territory_id` | INT(11) | Yes | NULL | Foreign key to `territories` table |

## Relationships

- `requests.company_id` -> `clients.id`
- `requests.contact_id` -> `contacts.id`
- `requests.created_by` -> `users.id`
- `requests.territory_id` -> `territories.id`

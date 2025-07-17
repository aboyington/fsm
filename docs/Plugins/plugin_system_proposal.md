# Plugin System Proposal

## Plugin Architecture Design

### Directory Structure
- Create a `plugins/` directory at the root level.
```
fsm/
├── app/
├── plugins/
│   └── examplePlugin/
│       ├── Controllers/
│       ├── Models/
│       ├── Views/
│       ├── config.php
│       └── routes.php
├── public/
├── writable/
├── ...
```

### Plugin Components
- **Controllers, Models, Views**: Each plugin can have its own MVC components which follow CodeIgniter's structure.
- **Config File** (`config.php`):
  - Defines metadata (name, version, dependencies).
  - Specifies initialization logic.

- **Routes File** (`routes.php`):
  - Contains route definitions for plugin-specific endpoints.

### Plugin Lifecycle Management
- **Enabling/Disabling Plugins**:
  - Track enabled plugins in a configuration file (e.g., `plugins.php` in `app/Config/`).
  - Provide an interface in the settings area to toggle plugins on or off.

- **Loading Plugins**:
  - Before routes are loaded, integrate plugin routes dynamically based on enabled status.
  - Use PHP `include` or `require` to incorporate plugin configurations and routes.

### Core Modification for Plugin Support
- Update the **Settings Controller**:
  - Implement logic to manage plugin states (enable/disable).
  - Create a dedicated section for plugin management in the UI.

- **Route Loading**:
  - Modify `/app/Config/Routes.php` to include plugin routes.

- **Public Assets**:
  - Allow plugins to add assets (JS, CSS) by dynamically inserting links in the `<head>` and `<body>` tags as needed.

### API for Plugins
- **Hooks and Events**:
  - Implement a hook system where core events (e.g., login, transaction) can trigger plugin actions.
  - Allow plugins to register listeners for these events.

- **Inter-plugin Communication**:
  - Define APIs for plugins to interact with each other if necessary.

### Example Plugin Use Case
- **Billing Integration** Plugin:
  - Separate from core FSM as plugins compatible with QuickBooks.
  - Manage estimates and invoices through third-party APIs without affecting the main workflow.

- **Mobile Interface** Plugin:
  - Responsive enhancements for field technicians, separate from core FSM.

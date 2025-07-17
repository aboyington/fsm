# FSM Plugin System Implementation Guide

## Overview

This document outlines the implementation of a flexible plugin system for the FSM (Field Service Management) platform, enabling third-party integrations and modular feature additions.

## Architecture Analysis

### Current FSM Architecture
- **Framework**: CodeIgniter 4 with MVC pattern
- **Routing**: Route groups with namespace-based organization
- **Authentication**: Session-based with filter middleware
- **Database**: SQLite/MySQL with CodeIgniter's ORM
- **Frontend**: Bootstrap 5 with jQuery and vanilla JavaScript

### Plugin System Goals
1. **Modularity**: Convert non-essential features (billing, advanced reporting) into plugins
2. **Third-party Integration**: Enable QuickBooks, Stripe, external APIs
3. **Extensibility**: Allow custom features without core modification
4. **Maintainability**: Isolate plugin code from core system

## Implementation Plan

### Phase 1: Core Infrastructure

#### 1.1 Directory Structure
```
fsm/
├── app/
├── plugins/
│   ├── .gitkeep
│   ├── billing-quickbooks/
│   │   ├── Controllers/
│   │   │   └── BillingController.php
│   │   ├── Models/
│   │   │   └── QuickBooksModel.php
│   │   ├── Views/
│   │   │   └── billing/
│   │   ├── Config/
│   │   │   ├── plugin.php
│   │   │   └── routes.php
│   │   ├── Database/
│   │   │   └── Migrations/
│   │   ├── assets/
│   │   │   ├── css/
│   │   │   └── js/
│   │   └── README.md
│   └── estimates-advanced/
│       ├── Controllers/
│       ├── Models/
│       ├── Views/
│       ├── Config/
│       └── assets/
├── public/
│   └── plugins/  # Symlinked plugin assets
└── writable/
    └── plugins/  # Plugin data and logs
```

#### 1.2 Plugin Configuration Structure
Each plugin must have a `Config/plugin.php` file:

```php
<?php
return [
    'name' => 'QuickBooks Billing Integration',
    'slug' => 'billing-quickbooks',
    'version' => '1.0.0',
    'description' => 'Integrates FSM with QuickBooks for billing and invoicing',
    'author' => 'FSM Team',
    'dependencies' => [
        'core' => '2.2.0',
        'plugins' => []
    ],
    'requirements' => [
        'php' => '8.1.0',
        'extensions' => ['curl', 'json']
    ],
    'database' => [
        'migrations' => true,
        'seeders' => false
    ],
    'navigation' => [
        'menu' => 'Billing',
        'items' => [
            'Invoices' => '/billing/invoices',
            'Payments' => '/billing/payments',
            'QuickBooks Sync' => '/billing/quickbooks/sync'
        ]
    ],
    'settings' => [
        'quickbooks_client_id' => '',
        'quickbooks_client_secret' => '',
        'sandbox_mode' => true
    ],
    'hooks' => [
        'work_order_completed' => 'generateInvoice',
        'payment_received' => 'updateQuickBooks'
    ]
];
```

#### 1.3 Plugin Routes Structure
Each plugin has `Config/routes.php`:

```php
<?php
// Plugin routes for billing-quickbooks
$routes->group('billing', ['namespace' => 'Plugins\BillingQuickbooks\Controllers'], function($routes) {
    $routes->get('/', 'BillingController::index');
    $routes->get('invoices', 'BillingController::invoices');
    $routes->get('payments', 'BillingController::payments');
    $routes->post('quickbooks/sync', 'BillingController::syncQuickBooks');
});
```

### Phase 2: Core Plugin Manager

#### 2.1 Plugin Manager Class
Create `app/Libraries/PluginManager.php`:

```php
<?php
namespace App\Libraries;

class PluginManager {
    protected $enabledPlugins = [];
    protected $pluginConfigs = [];
    
    public function __construct() {
        $this->loadEnabledPlugins();
    }
    
    public function loadEnabledPlugins() {
        // Load from config/plugins.php
    }
    
    public function enablePlugin($pluginSlug) {
        // Enable plugin logic
    }
    
    public function disablePlugin($pluginSlug) {
        // Disable plugin logic
    }
    
    public function getAvailablePlugins() {
        // Scan plugins directory
    }
    
    public function validatePlugin($pluginPath) {
        // Validate plugin structure and dependencies
    }
}
```

#### 2.2 Plugin Configuration File
Create `app/Config/Plugins.php`:

```php
<?php
namespace Config;

class Plugins {
    public array $enabledPlugins = [
        'billing-quickbooks' => [
            'enabled' => true,
            'priority' => 10
        ],
        'estimates-advanced' => [
            'enabled' => false,
            'priority' => 20
        ]
    ];
    
    public string $pluginsPath = ROOTPATH . 'plugins/';
    public string $pluginsAssetsPath = FCPATH . 'plugins/';
}
```

### Phase 3: Core Integration

#### 3.1 Route Integration
Modify `app/Config/Routes.php` to include plugin routes:

```php
<?php
// Load plugin routes
$pluginManager = new \App\Libraries\PluginManager();
foreach ($pluginManager->getEnabledPlugins() as $plugin) {
    $routesFile = $pluginManager->getPluginPath($plugin) . 'Config/routes.php';
    if (file_exists($routesFile)) {
        include $routesFile;
    }
}
```

#### 3.2 Navigation Integration
Update `app/Views/layouts/main.php` to include plugin navigation:

```php
<?php
// Add plugin navigation items
$pluginManager = service('pluginManager');
$pluginNavItems = $pluginManager->getNavigationItems();
?>

<!-- Add plugin navigation to existing navbar -->
<?php foreach ($pluginNavItems as $menu => $items): ?>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="<?= strtolower($menu) ?>Dropdown" role="button" data-bs-toggle="dropdown">
        <?= $menu ?>
    </a>
    <ul class="dropdown-menu">
        <?php foreach ($items as $label => $url): ?>
        <li><a class="dropdown-item" href="<?= base_url($url) ?>"><?= $label ?></a></li>
        <?php endforeach; ?>
    </ul>
</li>
<?php endforeach; ?>
```

### Phase 4: Settings Integration

#### 4.1 Plugin Management Interface
Add to Settings controller:

```php
public function plugins() {
    $pluginManager = service('pluginManager');
    
    $data = [
        'title' => 'Plugin Management',
        'activeTab' => 'plugins',
        'availablePlugins' => $pluginManager->getAvailablePlugins(),
        'enabledPlugins' => $pluginManager->getEnabledPlugins()
    ];
    
    return view('settings/plugins', $data);
}

public function togglePlugin() {
    $pluginSlug = $this->request->getPost('plugin');
    $action = $this->request->getPost('action');
    
    $pluginManager = service('pluginManager');
    
    if ($action === 'enable') {
        $result = $pluginManager->enablePlugin($pluginSlug);
    } else {
        $result = $pluginManager->disablePlugin($pluginSlug);
    }
    
    return $this->response->setJSON($result);
}
```

#### 4.2 Settings View
Create `app/Views/settings/plugins.php`:

```php
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Plugin Management</h5>
            </div>
            <div class="card-body">
                <?php foreach ($availablePlugins as $plugin): ?>
                <div class="plugin-item border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6><?= $plugin['name'] ?></h6>
                            <p class="text-muted"><?= $plugin['description'] ?></p>
                            <small>Version: <?= $plugin['version'] ?> | Author: <?= $plugin['author'] ?></small>
                        </div>
                        <div>
                            <div class="form-check form-switch">
                                <input class="form-check-input plugin-toggle" type="checkbox" 
                                       data-plugin="<?= $plugin['slug'] ?>"
                                       <?= in_array($plugin['slug'], array_keys($enabledPlugins)) ? 'checked' : '' ?>>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
```

### Phase 5: Hook System

#### 5.1 Event System
Create `app/Libraries/EventManager.php`:

```php
<?php
namespace App\Libraries;

class EventManager {
    protected $hooks = [];
    
    public function registerHook($event, $callback, $priority = 10) {
        $this->hooks[$event][$priority][] = $callback;
    }
    
    public function triggerHook($event, $data = []) {
        if (!isset($this->hooks[$event])) {
            return $data;
        }
        
        ksort($this->hooks[$event]);
        
        foreach ($this->hooks[$event] as $priority => $callbacks) {
            foreach ($callbacks as $callback) {
                $data = call_user_func($callback, $data);
            }
        }
        
        return $data;
    }
}
```

#### 5.2 Hook Integration
Integrate hooks into core functionality:

```php
// In WorkOrdersController when completing a work order
public function completeWorkOrder($id) {
    $workOrder = $this->workOrderModel->find($id);
    
    // Update status
    $this->workOrderModel->update($id, ['status' => 'completed']);
    
    // Trigger plugin hooks
    $eventManager = service('eventManager');
    $eventManager->triggerHook('work_order_completed', $workOrder);
    
    return $this->response->setJSON(['success' => true]);
}
```

## Migration Strategy

### Phase 1: Infrastructure Setup
1. Create plugin directory structure
2. Implement PluginManager class
3. Add plugin configuration support

### Phase 2: Core Integration
1. Integrate plugin routes
2. Add navigation support
3. Create settings interface

### Phase 3: Convert Existing Features
1. Move billing functionality to plugin
2. Convert estimates to plugin
3. Create mobile interface plugin

### Phase 4: Third-party Integrations
1. QuickBooks integration plugin
2. Stripe payment plugin
3. Mobile app API plugin

## Plugin Development Guidelines

### Plugin Structure Requirements
- Must follow CodeIgniter 4 conventions
- Must include plugin.php configuration
- Must handle database migrations
- Must provide uninstall cleanup

### Security Considerations
- Plugin code sandboxing
- Input validation requirements
- Database access restrictions
- API key management

### Performance Guidelines
- Lazy loading of plugin resources
- Efficient hook system
- Asset optimization
- Caching strategies

## Testing Strategy

### Unit Testing
- Plugin Manager functionality
- Individual plugin components
- Hook system reliability

### Integration Testing
- Plugin activation/deactivation
- Core system compatibility
- Navigation integration

### User Acceptance Testing
- Plugin installation process
- Settings interface usability
- Feature functionality

## Documentation Requirements

### Plugin Documentation
- Installation guide
- Configuration instructions
- API documentation
- Troubleshooting guide

### Developer Documentation
- Plugin development guide
- Hook system reference
- Core API documentation
- Best practices guide

## Future Enhancements

### Plugin Store
- Online plugin repository
- Automated installation
- Version management
- User reviews and ratings

### Advanced Features
- Plugin dependencies resolution
- Automatic updates
- Plugin marketplace
- Developer tools and SDK

This plugin system will transform FSM into a highly extensible platform, allowing for easy third-party integrations and custom feature development while maintaining core stability and performance.

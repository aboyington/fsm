<nav class="settings-nav">
    <div class="nav-section">
        <h3 class="nav-section-title">General</h3>
        <ul class="nav-links">
            <li><a href="/settings/general" class="nav-link <?= $activeTab === 'general' ? 'active' : '' ?>">General</a></li>
            <li><a href="/settings/organization" class="nav-link <?= $activeTab === 'organization' ? 'active' : '' ?>">Organization Profile</a></li>
            <li><a href="/settings/fiscal-year" class="nav-link <?= $activeTab === 'fiscal-year' ? 'active' : '' ?>">Fiscal Year</a></li>
            <li><a href="/settings/currency" class="nav-link <?= $activeTab === 'currency' ? 'active' : '' ?>">Currency</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Workforce</h3>
        <ul class="nav-links">
            <li><a href="/settings/users" class="nav-link <?= $activeTab === 'users' ? 'active' : '' ?>">Users</a></li>
            <li><a href="/settings/territories" class="nav-link <?= $activeTab === 'territories' ? 'active' : '' ?>">Territories</a></li>
            <li><a href="/settings/skills" class="nav-link <?= $activeTab === 'skills' ? 'active' : '' ?>">Skills</a></li>
            <li><a href="/settings/holiday" class="nav-link <?= $activeTab === 'holiday' ? 'active' : '' ?>">Holiday</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Security Control</h3>
        <ul class="nav-links">
            <li><a href="/settings/roles" class="nav-link <?= $activeTab === 'roles' ? 'active' : '' ?>">Roles & Permissions</a></li>
            <li><a href="<?= site_url('settings/pii-fields') ?>" class="nav-link <?= $activeTab === 'pii-fields' ? 'active' : '' ?>">PII Fields</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Field Service Settings</h3>
        <ul class="nav-links">
            <li><a href="/settings/service-types" class="nav-link <?= $activeTab === 'service-types' ? 'active' : '' ?>">Service Types</a></li>
            <li><a href="/settings/parts" class="nav-link <?= $activeTab === 'parts' ? 'active' : '' ?>">Parts</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Channels</h3>
        <ul class="nav-links">
            <li><a href="/settings/email" class="nav-link <?= $activeTab === 'email' ? 'active' : '' ?>">Email</a></li>
            <li><a href="/settings/sms" class="nav-link <?= $activeTab === 'sms' ? 'active' : '' ?>">SMS</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Maintenance Plans</h3>
        <ul class="nav-links">
            <li><a href="/settings/maintenance-plans" class="nav-link <?= $activeTab === 'maintenance-plans' ? 'active' : '' ?>">Plans</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Billing</h3>
        <ul class="nav-links">
            <li><a href="/settings/billing-setup" class="nav-link <?= $activeTab === 'billing' ? 'active' : '' ?>">Billing Setup</a></li>
            <li><a href="/settings/tax-settings" class="nav-link <?= $activeTab === 'tax-settings' ? 'active' : '' ?>">Tax Settings</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Customization</h3>
        <ul class="nav-links">
            <li><a href="/settings/custom-fields" class="nav-link <?= $activeTab === 'custom-fields' ? 'active' : '' ?>">Custom Fields</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Automation</h3>
        <ul class="nav-links">
            <li><a href="/settings/workflows" class="nav-link <?= $activeTab === 'workflows' ? 'active' : '' ?>">Workflows</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Data Administration</h3>
        <ul class="nav-links">
            <li><a href="/settings/data-import" class="nav-link <?= $activeTab === 'data-import' ? 'active' : '' ?>">Import</a></li>
            <li><a href="/settings/data-export" class="nav-link <?= $activeTab === 'data-export' ? 'active' : '' ?>">Export</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Developer Space</h3>
        <ul class="nav-links">
            <li><a href="/settings/api" class="nav-link <?= $activeTab === 'api' ? 'active' : '' ?>">API</a></li>
        </ul>
    </div>
    
    <div class="nav-section">
        <h3 class="nav-section-title">Integration</h3>
        <ul class="nav-links">
            <li><a href="/settings/integrations" class="nav-link <?= $activeTab === 'integrations' ? 'active' : '' ?>">Apps</a></li>
        </ul>
    </div>
</nav>

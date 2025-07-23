# Version Management System

This document describes the automated version management system implemented in the FSM (Field Service Management) application.

## Overview

The FSM application uses an automated version management script that follows [Semantic Versioning](https://semver.org/) principles to maintain consistent version numbering across all application files.

## Table of Contents

- [Overview](#overview)
- [Files Structure](#files-structure)
- [Version Script Features](#version-script-features)
- [Usage Instructions](#usage-instructions)
- [Files Updated](#files-updated)
- [Version Types](#version-types)
- [Examples](#examples)
- [NPM Integration](#npm-integration)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)

## Files Structure

```
fsm/
├── package.json              # Main version tracking file
├── scripts/
│   ├── version.sh            # Version update script
│   └── README.md            # Script-specific documentation
├── app/
│   ├── Views/
│   │   └── layouts/
│   │       └── main.php     # Footer version display
│   └── Config/
│       └── App.php          # Application configuration (if exists)
├── composer.json            # Composer dependencies (if exists)
└── docs/
    └── VERSION_MANAGEMENT.md # This documentation
```

## Version Script Features

### ✅ Automatic Updates
- Updates version numbers across multiple files simultaneously
- Ensures consistency across the entire application
- Atomic updates - all files update or none do

### ✅ Semantic Versioning
- **MAJOR** version: Breaking changes (1.0.0 → 2.0.0)
- **MINOR** version: New features, backwards compatible (1.0.0 → 1.1.0)
- **PATCH** version: Bug fixes, backwards compatible (1.0.0 → 1.0.1)

### ✅ Cross-Platform Support
- Automatically detects macOS/Linux
- Uses appropriate `sed` commands for each platform
- Bash script compatible with most Unix systems

### ✅ User-Friendly Output
- Colorized terminal output for better readability
- Clear success/error messages
- Progress indicators during updates

### ✅ Error Handling
- Validates input parameters
- Provides helpful usage instructions
- Graceful handling of missing files

## Usage Instructions

### Basic Commands

```bash
# Navigate to FSM project root
cd /Users/anthony/Sites/fsm

# Show current version
./scripts/version.sh current

# Update patch version (bug fixes)
./scripts/version.sh patch

# Update minor version (new features)
./scripts/version.sh minor

# Update major version (breaking changes)
./scripts/version.sh major
```

### Make Script Executable

If you encounter permission issues:

```bash
chmod +x scripts/version.sh
```

## Files Updated

The version script automatically updates the following files:

| File | Purpose | Update Pattern |
|------|---------|----------------|
| `package.json` | Main version tracking | `"version": "1.0.0"` |
| `app/Views/layouts/main.php` | Footer version display | `v1.0.0` |
| `composer.json` | Composer dependencies | `"version": "1.0.0"` |
| `app/Config/App.php` | CodeIgniter config | `version = '1.0.0'` |

### Version Display Location

The application version is displayed in the footer of every page:

```php
<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8 text-center text-md-start">
                <span class="text-muted">FSM by Anthony Boyington © 2025 - Integrated with Canvass Global</span>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <span class="text-muted">v1.1.0</span>  <!-- Version appears here -->
            </div>
        </div>
    </div>
</footer>
```

## Version Types

### 📌 Patch Version (`./scripts/version.sh patch`)

**Use for:**
- Bug fixes
- Security patches
- Minor documentation updates
- Small performance improvements

**Example:** `1.0.0` → `1.0.1`

### 📌 Minor Version (`./scripts/version.sh minor`)

**Use for:**
- New features
- New functionality
- UI/UX improvements
- New API endpoints
- Database schema additions (backwards compatible)

**Example:** `1.0.0` → `1.1.0`

### 📌 Major Version (`./scripts/version.sh major`)

**Use for:**
- Breaking changes
- Major architecture changes
- Incompatible API changes
- Database schema changes (breaking)
- Major redesigns

**Example:** `1.0.0` → `2.0.0`

## Examples

### Example 1: Bug Fix Release

```bash
$ ./scripts/version.sh current
📍 Current version: 1.1.0

$ ./scripts/version.sh patch
🔄 Updating FSM version: 1.1.0 → 1.1.1 (patch)

✅ Updated package.json: 1.1.1
✅ Updated app/Views/layouts/main.php: 1.1.1
✅ Updated composer.json: 1.1.1
✅ Updated app/Config/App.php: 1.1.1

🎉 Successfully updated FSM to version 1.1.1

📝 Files updated:
   • package.json
   • app/Views/layouts/main.php (footer)
   • composer.json
   • app/Config/App.php

💡 Remember to:
   • Test the application
   • Clear browser cache if needed
   • Commit changes to git
   • Push to GitHub
```

### Example 2: New Feature Release

```bash
$ ./scripts/version.sh minor
🔄 Updating FSM version: 1.1.1 → 1.2.0 (minor)

✅ Updated package.json: 1.2.0
✅ Updated app/Views/layouts/main.php: 1.2.0
✅ Updated composer.json: 1.2.0
✅ Updated app/Config/App.php: 1.2.0

🎉 Successfully updated FSM to version 1.2.0
```

### Example 3: Error Handling

```bash
$ ./scripts/version.sh
❌ Error: Version type required

Usage:
  ./scripts/version.sh patch    # Bug fixes (1.2.0 → 1.2.1)
  ./scripts/version.sh minor    # New features (1.2.0 → 1.3.0)
  ./scripts/version.sh major    # Breaking changes (1.2.0 → 2.0.0)
  ./scripts/version.sh current  # Show current version
```

## NPM Integration

The version script is integrated with npm scripts for easier access:

```json
{
  "scripts": {
    "version": "./scripts/version.sh",
    "version:patch": "./scripts/version.sh patch",
    "version:minor": "./scripts/version.sh minor", 
    "version:major": "./scripts/version.sh major",
    "version:current": "./scripts/version.sh current"
  }
}
```

### NPM Usage Examples

```bash
# Show current version
npm run version:current

# Update versions
npm run version:patch
npm run version:minor
npm run version:major
```

## Best Practices

### 🔄 Development Workflow

1. **Before Making Changes**
   ```bash
   ./scripts/version.sh current
   ```

2. **After Bug Fixes**
   ```bash
   ./scripts/version.sh patch
   ```

3. **After New Features**
   ```bash
   ./scripts/version.sh minor
   ```

4. **After Breaking Changes**
   ```bash
   ./scripts/version.sh major
   ```

### 📝 Git Integration

Always commit version changes with meaningful messages:

```bash
# After version update
git add package.json app/Views/layouts/main.php composer.json app/Config/App.php
git commit -m "chore: bump version to v1.2.0"
git push origin main
```

### 🧪 Testing

After version updates:

1. **Test the application** to ensure everything works
2. **Check the footer** displays the correct version
3. **Clear browser cache** if needed
4. **Verify all updated files** are correct

### 🚀 Release Process

1. Complete feature development
2. Run tests
3. Update version using script
4. Create git tag
5. Push to GitHub
6. Deploy to production

```bash
# Example release process
./scripts/version.sh minor
git add .
git commit -m "chore: release v1.2.0"
git tag v1.2.0
git push origin main --tags
```

## Troubleshooting

### Common Issues

#### Permission Denied
```bash
# Problem
./scripts/version.sh current
# bash: ./scripts/version.sh: Permission denied

# Solution
chmod +x scripts/version.sh
```

#### File Not Found
```bash
# Problem
./scripts/version.sh current
# ⚠️ File not found: composer.json

# Solution
# This is normal - the script will skip files that don't exist
# No action needed
```

#### Version Not Updating in Footer
```bash
# Problem
# Footer still shows old version after update

# Solution
# Clear browser cache or hard refresh
# Ctrl+F5 (Windows) or Cmd+Shift+R (Mac)
```

### Script Debugging

To debug the version script:

```bash
# Add debug output
bash -x ./scripts/version.sh current
```

### Manual Version Update

If the script fails, you can manually update versions:

1. **Edit `package.json`**
   ```json
   {
     "version": "1.2.0"
   }
   ```

2. **Edit `app/Views/layouts/main.php`**
   ```php
   <span class="text-muted">v1.2.0</span>
   ```

3. **Edit `composer.json`** (if exists)
   ```json
   {
     "version": "1.2.0"
   }
   ```

## Support

For questions or issues with the version management system:

1. Check this documentation
2. Review the script README: `scripts/README.md`
3. Check the script source code: `scripts/version.sh`
4. Test with the `current` command first

---

**Last Updated:** January 23, 2025  
**Version:** 2.9.0-alpha  
**Author:** Anthony Boyington

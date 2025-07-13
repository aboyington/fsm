# FSM Version Management Script

This script automatically updates the version number across all relevant files in the FSM (Field Service Management) application.

## Usage

```bash
# Show current version
./scripts/version.sh current

# Update patch version (1.0.0 → 1.0.1)
./scripts/version.sh patch

# Update minor version (1.0.0 → 1.1.0)
./scripts/version.sh minor

# Update major version (1.0.0 → 2.0.0)  
./scripts/version.sh major
```

## Files Updated

The script automatically updates version numbers in:

- `package.json` - Main version tracking file
- `app/Views/layouts/main.php` - Application footer version display
- `composer.json` - If it exists (Composer dependency management)
- `app/Config/App.php` - If it exists (CodeIgniter app configuration)

## Version Numbering

This script follows [Semantic Versioning](https://semver.org/) principles:

- **MAJOR** version: Incompatible API changes
- **MINOR** version: New functionality in a backwards compatible manner
- **PATCH** version: Backwards compatible bug fixes

## Examples

```bash
# Check current version
$ ./scripts/version.sh current
📍 Current version: 1.0.1

# Apply bug fix
$ ./scripts/version.sh patch
🔄 Updating FSM version: 1.0.1 → 1.0.2 (patch)

# Add new feature
$ ./scripts/version.sh minor
🔄 Updating FSM version: 1.0.2 → 1.1.0 (minor)

# Breaking change
$ ./scripts/version.sh major
🔄 Updating FSM version: 1.1.0 → 2.0.0 (major)
```

## After Running

After running the version script, remember to:

1. Test the application to ensure everything works correctly
2. Clear browser cache if needed
3. Commit the changes to git
4. Push to GitHub

## NPM Scripts

You can also use the predefined npm scripts:

```bash
npm run version:current  # Show current version
npm run version:patch    # Patch version update
npm run version:minor    # Minor version update
npm run version:major    # Major version update
```

## Notes

- The script automatically detects your operating system (macOS/Linux) and uses the appropriate `sed` command
- All version updates are applied atomically - either all files are updated or none are
- The script provides colorized output for better readability
- Make sure the script is executable: `chmod +x scripts/version.sh`

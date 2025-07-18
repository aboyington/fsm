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






🎉 Updated Version Script Features:

New Pre-release Support:
•  Alpha releases: ./scripts/version.sh minor alpha → 1.2.0-alpha
•  Beta releases: ./scripts/version.sh minor beta → 1.2.0-beta 
•  Release candidates: ./scripts/version.sh minor rc → 1.2.0-rc

Key Improvements:

1. Enhanced Usage Instructions:
•  Added pre-release examples in help text
•  Shows proper syntax for alpha, beta, and rc versions
2. New Functions:
•  get_base_version(): Strips pre-release suffixes from current version
•  validate_prerelease(): Validates pre-release type (alpha, beta, rc)
3. Updated Version Logic:
•  Handles existing pre-release versions properly
•  Increments base version then adds pre-release suffix
•  Supports transitioning from pre-release to stable versions
4. Enhanced Output:
•  Shows pre-release type in progress messages
•  Displays pre-release warnings
•  Provides proper git tagging instructions

Usage Examples:
# Standard releases
./scripts/version.sh patch          # 2.4.0-alpha → 2.4.1
./scripts/version.sh minor          # 2.4.0 → 2.5.0  
./scripts/version.sh major          # 2.4.0 → 3.0.0

# Pre-release versions
./scripts/version.sh minor alpha    # 2.4.0 → 2.5.0-alpha
./scripts/version.sh patch beta     # 2.4.0-alpha → 2.4.1-beta
./scripts/version.sh minor rc       # 2.4.0 → 2.5.0-rc
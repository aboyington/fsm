#!/bin/bash

# Version Update Script for FSM - Field Service Management
# Usage: ./scripts/version.sh [patch|minor|major|current] [alpha|beta|rc]

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if argument provided
if [ $# -eq 0 ]; then
    echo -e "${RED}❌ Error: Version type required${NC}"
    echo ""
    echo "Usage:"
    echo "  ./scripts/version.sh patch           # Bug fixes (1.1.0 → 1.1.1)"
    echo "  ./scripts/version.sh minor           # New features (1.1.0 → 1.2.0)"
    echo "  ./scripts/version.sh major           # Breaking changes (1.1.0 → 2.0.0)"
    echo "  ./scripts/version.sh current         # Show current version"
    echo ""
    echo "Pre-release versions:"
    echo "  ./scripts/version.sh minor alpha     # Alpha release (1.1.0 → 1.2.0-alpha)"
    echo "  ./scripts/version.sh minor beta      # Beta release (1.1.0 → 1.2.0-beta)"
    echo "  ./scripts/version.sh minor rc        # Release candidate (1.1.0 → 1.2.0-rc)"
    exit 1
fi

TYPE=$1
PRERELEASE=$2

# Get current version from package.json
get_current_version() {
    if [ -f "package.json" ]; then
        grep '"version"' package.json | head -1 | sed 's/.*"version": "\(.*\)".*/\1/'
    else
        echo "1.0.0"
    fi
}

# Extract base version (remove pre-release suffix)
get_base_version() {
    local version=$1
    echo "$version" | sed 's/-.*//'
}

# Validate pre-release type
validate_prerelease() {
    local prerelease=$1
    if [ -n "$prerelease" ] && [ "$prerelease" != "alpha" ] && [ "$prerelease" != "beta" ] && [ "$prerelease" != "rc" ]; then
        echo -e "${RED}❌ Invalid pre-release type: $prerelease${NC}"
        echo "Valid options: alpha, beta, rc"
        exit 1
    fi
}

# Increment version based on type
increment_version() {
    local version=$1
    local type=$2
    local prerelease=$3
    
    # Extract base version (remove any pre-release suffix)
    local base_version=$(get_base_version "$version")
    IFS='.' read -r major minor patch <<< "$base_version"
    
    case $type in
        "major")
            major=$((major + 1))
            minor=0
            patch=0
            ;;
        "minor")
            minor=$((minor + 1))
            patch=0
            ;;
        "patch")
            patch=$((patch + 1))
            ;;
        *)
            echo -e "${RED}❌ Invalid version type: $type${NC}"
            exit 1
            ;;
    esac
    
    local new_version="$major.$minor.$patch"
    
    # Add pre-release suffix if specified
    if [ -n "$prerelease" ]; then
        new_version="$new_version-$prerelease"
    fi
    
    echo "$new_version"
}

# Update file with new version
update_file() {
    local file=$1
    local old_version=$2
    local new_version=$3
    local pattern=$4
    
    if [ -f "$file" ]; then
        if [[ "$OSTYPE" == "darwin"* ]]; then
            # macOS
            sed -i '' "$pattern" "$file"
        else
            # Linux
            sed -i "$pattern" "$file"
        fi
        echo -e "${GREEN}✅ Updated $file: $new_version${NC}"
    else
        echo -e "${YELLOW}⚠️  File not found: $file${NC}"
    fi
}

# Main function
main() {
    current_version=$(get_current_version)
    
    if [ "$TYPE" = "current" ]; then
        echo -e "${BLUE}📍 Current version: $current_version${NC}"
        exit 0
    fi
    
    # Validate pre-release type if provided
    if [ -n "$PRERELEASE" ]; then
        validate_prerelease "$PRERELEASE"
    fi
    
    new_version=$(increment_version "$current_version" "$TYPE" "$PRERELEASE")
    timestamp=$(date +%Y-%m-%d)
    
    if [ -n "$PRERELEASE" ]; then
        echo -e "${BLUE}🔄 Updating FSM version: $current_version → $new_version ($TYPE $PRERELEASE)${NC}"
    else
        echo -e "${BLUE}🔄 Updating FSM version: $current_version → $new_version ($TYPE)${NC}"
    fi
    echo ""
    
    # Update package.json
    if [ -f "package.json" ]; then
        update_file "package.json" "$current_version" "$new_version" "s/\"version\": \"$current_version\"/\"version\": \"$new_version\"/"
    fi
    
    # Update main layout footer
    if [ -f "app/Views/layouts/main.php" ]; then
        update_file "app/Views/layouts/main.php" "$current_version" "$new_version" "s/v$current_version/v$new_version/"
    fi
    
    # Update composer.json if it exists
    if [ -f "composer.json" ]; then
        update_file "composer.json" "$current_version" "$new_version" "s/\"version\": \"$current_version\"/\"version\": \"$new_version\"/"
    fi
    
    # Update any config files that might contain version
    if [ -f "app/Config/App.php" ]; then
        update_file "app/Config/App.php" "$current_version" "$new_version" "s/version.*=.*'$current_version'/version = '$new_version'/"
    fi
    
    echo ""
    if [ -n "$PRERELEASE" ]; then
        echo -e "${GREEN}🎉 Successfully updated FSM to version $new_version ($PRERELEASE release)${NC}"
    else
        echo -e "${GREEN}🎉 Successfully updated FSM to version $new_version${NC}"
    fi
    echo ""
    echo -e "${BLUE}📝 Files updated:${NC}"
    echo "   • package.json"
    echo "   • app/Views/layouts/main.php (footer)"
    if [ -f "composer.json" ]; then
        echo "   • composer.json"
    fi
    if [ -f "app/Config/App.php" ]; then
        echo "   • app/Config/App.php"
    fi
    echo ""
    echo -e "${YELLOW}💡 Remember to:${NC}"
    echo "   • Test the application"
    echo "   • Clear browser cache if needed"
    echo "   • Commit changes to git"
    if [ -n "$PRERELEASE" ]; then
        echo "   • Create git tag: git tag v$new_version"
        echo "   • Push tag to GitHub: git push origin v$new_version"
        echo -e "${YELLOW}⚠️  Pre-release warning: This is $PRERELEASE software${NC}"
    else
        echo "   • Create git tag: git tag v$new_version"
        echo "   • Push to GitHub: git push origin v$new_version"
    fi
}

# Change to project root directory
cd "$(dirname "$0")/.."

# Run main function
main

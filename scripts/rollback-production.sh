#!/bin/sh

# Emergency Rollback Script for Production
# WARNING: This script performs a hard reset of the git repository
# It does NOT affect database, storage, or .env files

set -e

echo "=========================================="
echo "  PRODUCTION EMERGENCY ROLLBACK SCRIPT"
echo "=========================================="
echo ""
echo "WARNING: This script will:"
echo "  - Reset the git repository to a previous commit or tag"
echo "  - DISCARD all uncommitted changes"
echo "  - NOT affect database, storage, or .env files"
echo ""
echo "This action is IRREVERSIBLE for uncommitted changes."
echo ""

# Confirmation
echo "Do you want to proceed? Type YES (all caps) to continue:"
read -r CONFIRMATION

if [ "$CONFIRMATION" != "YES" ]; then
    echo ""
    echo "Rollback cancelled."
    exit 0
fi

echo ""
echo "=========================================="
echo "  Recent Commits"
echo "=========================================="
git log --oneline -5
echo ""

# Get target commit/tag
echo "Enter the commit hash OR tag to rollback to:"
read -r TARGET

if [ -z "$TARGET" ]; then
    echo ""
    echo "ERROR: No commit hash or tag provided."
    echo "Rollback cancelled."
    exit 1
fi

# Validate target exists
if ! git rev-parse --verify "$TARGET" > /dev/null 2>&1; then
    echo ""
    echo "ERROR: '$TARGET' is not a valid commit hash or tag."
    echo "Rollback cancelled."
    exit 1
fi

echo ""
echo "You are about to rollback to: $TARGET"
echo "Type YES again to confirm:"
read -r FINAL_CONFIRMATION

if [ "$FINAL_CONFIRMATION" != "YES" ]; then
    echo ""
    echo "Rollback cancelled."
    exit 0
fi

echo ""
echo "Performing rollback..."
git reset --hard "$TARGET"

echo ""
echo "=========================================="
echo "  ROLLBACK SUCCESSFUL"
echo "=========================================="
echo "Repository has been reset to: $TARGET"
echo ""
echo "Next steps:"
echo "  1. Verify the application is working correctly"
echo "  2. Run: php artisan optimize:clear"
echo "  3. Run: php artisan optimize"
echo ""

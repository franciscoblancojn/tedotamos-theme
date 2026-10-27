#!/usr/bin/env bash
# Igual que en el plugin aveonline: sube la versión de style.css y publica (npm run push-tag).
# Uso: npm run push-v -- major|minor|patch
set -e

if [ $# -ne 1 ] || [[ ! "$1" =~ ^(major|minor|patch)$ ]]; then
    echo "Uso: npm run push-v -- major|minor|patch"
    exit 1
fi

VERSION=$(grep -m1 "^Version:" style.css | sed 's/.*Version:[[:space:]]*//' | tr -d '\r')
MAJOR=$(echo "$VERSION" | cut -d. -f1)
MINOR=$(echo "$VERSION" | cut -d. -f2)
PATCH=$(echo "$VERSION" | cut -d. -f3)

case "$1" in
    major)
        MAJOR=$((MAJOR + 1))
        MINOR=0
        PATCH=0
        ;;
    minor)
        MINOR=$((MINOR + 1))
        PATCH=0
        ;;
    patch)
        PATCH=$((PATCH + 1))
        ;;
esac

NEW_VERSION="$MAJOR.$MINOR.$PATCH"

if git rev-parse -q --verify "refs/tags/$NEW_VERSION" > /dev/null; then
    echo "✗ El tag $NEW_VERSION ya existe"
    exit 1
fi

sed -i "s/^\(Version: *\)[0-9.]\+/\1$NEW_VERSION/" style.css

echo "✓ Versión: $VERSION → $NEW_VERSION"

# A diferencia del plugin, sin "|| true" ni "2>/dev/null": si el commit o el push fallan, se ve el error.
npm run push-tag --silent

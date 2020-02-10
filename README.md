# Content Module

Define an API to create content.

## Content types

Each content has a content type, which is an entity and a bundle, so fields can be attached to them.
When a content type is created, permissions (add, edit etc), a menu item and default fields (title, slug, content) will be created automatically.

## Content

`Content` is a bundled entity

## Blocks

Provides a block provider to add save content as blocks

## Events
- `ContentCreated`
- `ContentDeleted`
- `ContentTypeCreated`
- `ContentTypeDeleted`
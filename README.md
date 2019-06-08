# Content Module

## v1.0.0 First commit

### Facades
- Content : helpers to create content, content forms, content fields.

### Content types

They are regular entries in the database and hold only a name. Once created fields can be added to them.

When they are created, the permissions for access/edit/delete will be automatically created as well (see Content config) and given to the role 4 (Admin).

### Fields

To add a type of field to the content api, you'll need to add a class that implements `Pingu\Content\Contracts\ContentField`, use the trait `Pingu\Content\Traits\ContentField` and implements the remaining methods.

A Field will define `formFefinitions()`, used when adding a field to a content type, and a `fieldDefinition()` when added to a piece of content. Validation rules and messages must be defined as well, for both situations.

You'll need your own table when creating a new field. That table will contain instances of field associated to a content type.
You can any field you want in this table, the only required field is `default` (default value for that field).

You will need to register your fields through the facade `Content::registerContentField` in your service provider.

In the field table you'll find all instances of field for all content types, when retrieving a Field from that table, to access the actual field (Text, Boolean etc), call `$field->instance`.
Those generic fields can be set as `editable`, `deletable` and `visible` and hold the `name` and `machineName` fields.

`title` and `published` and `slug` are added automatically to every content. see Content::reservedFieldnames for field names you can't use on content types.

You can create fields that are not editable and/or not editable for any content type as any other field, not through the ui but manually in another module for example. `DeletableContentField` and `EditableContentField` middlewares are used to protect the routes.

### Field Values
Content values for each field are stored in the field_values table, they are relationned to a content and a field (generic).

If you create a new field to content type for which there is content, values will be created automatically through an event/listener.

### Events
- `CreatedContent`
- `ContentCreated`
- `ContentFieldCreated`
- `ContentTypeCreated` listened by `CreateContentTypePermissions` : will create permissions as defined in config and add a menu item
- `ContentTypeDeleted` listened by DeleteContentTypePermissions : will delete those permissions and menu item
- `ContentTypeCreated`
- `ContentFieldStoreValidator` thrown when the validator after a content type field store validator is created
- `ContentFieldUpdateValidator` thrown when the validator after a content type field update validator is created

### Policies
Contents have a special policy (`ContentPolicy`) to check view/edit/delete on them.
They also have a `ContentTypePolicy` policy to check the create perms on contents.

They are registered on the gate in the `AuthServiceProvider`.
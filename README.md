# Content Module

## Content types

They are regular entries in the database and hold only a name. Once created fields can be added to them.

When they are created, the permissions for access/edit/delete will be automatically created as well (see Content config).

## Fields

To add a type of field to the content api, you'll need to add a class that implements `Pingu\Content\Contracts\ContentField`, use the trait `Pingu\Content\Traits\ContentField` and implements the remaining methods. you'll need your own table for that field. That table will contain instances of field associated to a content type.

You can any field you want in this table, the only required field is `default` (default value for that field).

To make this field available in the front end for users to add this field to a content type, add a line to the table `fields_available` (linked to the model `ContentField`).

In the field table you'll find all instances of field for all content types, when retrieving a Field from that table, to access the actual field (Text, Boolean etc), call `$field->instance`.
Those generic fields can be set as `editable`, `deletable` and `visible` and hold the `name` and `machineName` fields.

`title` and `published` are added automatically to every content, so you can't use them as machine names for content type fields (or it will mess up with the form).

##Events
- `ContentTypeCreated` listened by `CreateContentTypePermissions` : will create permissions as defined in config and add a menu item
- `ContentTypeDeleted` listened by DeleteContentTypePermissions : will delete those permissions and menu item
- `ContentTypeCreated`
- `ContentFieldStoreValidator` thrown when the validator after a content type field store validator is created
- `ContentFieldUpdateValidator` thrown when the validator after a content type field update validator is created
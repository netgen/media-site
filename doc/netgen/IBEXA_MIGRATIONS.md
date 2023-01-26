Tano Consulting Ibexa Migration Bundle
======================================

For migrating Ibexa content types, users, user groups, permissions and other between installations it is advised that Tano Consulting Ibexa Migration Bundle is used. It is a direct replacement of [https://github.com/kaliop-uk/ezmigrationbundle](kaliop/ezmigrationbundle) for Ibexa 4.

For example, when implementing a feature that requires adding a field to some content type, it would be best to include migration file into the commit.

That way, there is little chance that the change won't be propagated to other servers, plus it is clear when, who and why added the field.


Usage
-----

For generating new migration file, just a simple command needs to be run.

For example, if you have added a new field to the `ng_article` content type, you would run:

```console
php kaliop:migration:generate --type=content_type --match-type=content_type_identifier --match-value=ng_article --mode=update

```

This command will generate the migration file which can then be used to run the migration and update the content type to match yours.

The command for running the migration is rather simple:

```console
php kaliop:migration:migrate
```

For more instructions you can check [GitHub repository](https://github.com/tanoconsulting/ibexa-migration-bundle) and this [blog post](https://netgen.io/blog/ez-migrations-made-easy-kaliop-migration-bundle).

Deployment
----------

During deployment, migrations will be run automatically, so the changes get applied to the production automatically.

**NOTE: THERE IS NO ROLLBACK OPTION**

Please take care, as there is no rollback option, and the only way to rollback is to restore the database from earlier version.

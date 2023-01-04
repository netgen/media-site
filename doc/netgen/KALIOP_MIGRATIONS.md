Kaliop eZ Migrations
=============================

For migrating eZ content types, users, user groups, permissions and other between installations it is advised that Kaliop eZ migration to be used.

For example, when implementing a feature that requires adding a field to some content type, it would be best to include migration file into the commit.

That way there is little chance that the change won't be propagated to other servers, plus it is clear when, who and why added the field. 


Usage
----------------------------

For generating new migration file, just a simple command need to be run.

For example, if you have added a new field to the `ng_article` content type, you would run:

```console
php kaliop:migration:generate --type=content_type --match-type=content_type_identifier --match-value=ng_article --mode=update AppBundle TASK-123-ng_article_author_field_added

```

This command will generate the migration file which can then be used to run the migration and update the content type to match yours.

The command for running the migration is rather simple:
```console
php kaliop:migration:migrate
```

For more instructions you can check [Github Repository](https://github.com/kaliop-uk/ezmigrationbundle) or this [blog post](https://netgen.io/blog/ez-migrations-made-easy-kaliop-migration-bundle).

Deployment
----------------------------

During deployment, migrations will be run automatically, so the changes get applied to the production automatically.

NOTE: THERE IS NO ROLLBACK OPTION
Please take care, as there is no rollback option, and the only way to rollback is to restore the database from earlier version.

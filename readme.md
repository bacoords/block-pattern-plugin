# Block Pattern Plugin (Experimental)

Experimental plugin to store some pattern management features that I'm testing out.

This should not be used in production and **features may be changed/removed at any time**.

## Concepts

This plugin assumes that you have patterns that you want to manage in a theme. It allows you to create patterns in the Site Editor and then automatically exports them to your theme when you save changes.

The slug convention is `[theme-slug]/[pattern-name]`, so a pattern in TwentyTwentyFour named "Hero" would be `"slug":"twentytwentyfour/hero"` and saved as `twentytwentyfour/patterns/hero.php`. For now, the pattern's `post_name` value is what generates the slug, and those two need to stay matching exactly for the sync connection to exist.

There is no real 'import' yet for pulling in changes or new patterns in the theme back into the Site Editor. This is a one-way sync for now. Also, the plugin exports your pattern metadata to the theme, but it doesn't do anything else with it yet.

## Features

### Pattern WP-Admin Screen

Shows the `wp_block` post edit screen that lists all of your patterns and allows you to edit them. Includes a direct link to edit in the pattern in the Site Editor as well as a 'synced' status to show if the pattern is in sync with the theme (this looks at content only, not other pattern metadata).

### Patterns export to theme on save

When you save a pattern, it will automatically export the pattern to your theme's `./patterns` directory.

### Import patterns from current active theme to database

For now there's just a wp-cli command to grab all patterns in the theme and import them into the database. This is useful for when you're first setting up the plugin and want to get all of your theme's patterns into the database.

```bash
wp wpdev block-pattern import
```

## Advanced Content Locking

This has been pulled out of this repo and into its own plugin: [Block Locking Plugin](https://github.com/bacoords/block-locking-plugin)

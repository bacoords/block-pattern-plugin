# Block Pattern Plugin (Experimental)

Experimental plugin to store some pattern management features that I'm testing out.

This should not be used in production and **features may be changed/removed at any time**.

## Concepts

This plugin assumes that you have patterns that you want to manage in a theme. It allows you to create patterns in the Site Editor and then automatically exports them to your theme when you save changes.

The slug convention is `[theme-slug]/[pattern-name]`, so a pattern in TwentyTwentyFour named "Hero" would be `"slug":"twentytwentyfour/hero"` and saved as `twentytwentyfour/patterns/hero.php`. For now, the pattern name and the post_name need to match exactly for the sync connection to exist.

There is no real 'import' yet for pulling in changes or new patterns in the theme back into the Site Editor. This is a one-way sync for now.

## Features

### Pattern WP-Admin Screen

Shows the `wp_block` post edit screen that lists all of your patterns and allows you to edit them. Includes a direct link to edit in the pattern in the Site Editor as well as a 'synced' status to show if the pattern is in sync with the theme (this looks at content only, not other pattern metadata).

### Patterns export to theme on save

When you save a pattern, it will automatically export the pattern to your theme's `patterns` directory..

### Theme Patterns Hidden from Site Editor

Patterns that are exported to the theme are hidden from the site editor. This is to prevent you from being shown duplicate patterns with the "locked" icon that you can't edit.

### ContentOnly Lock Toggle

Adds a setting on Group blocks that allows you to show an "Advanced Editing" button on the block toolbar. The button allows you to quickly toggle the `contentOnly` lock on and off instead of using the "lock" feature.

## Blocks / Block Variations

### "Pattern Container" Group Block Variation

Group block that has a full-width container, some padding in it, and then an inner group with an advanced "content locking" toggle.

### "View All Patterns" Custom Block

A block that can show you all (or a select) block pattern on the front end. Useful for when you want to test a pattern on your site without having it as a synced.

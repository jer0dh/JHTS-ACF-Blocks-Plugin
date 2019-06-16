# JHTS ACF Blocks Plugin

A starting point for a Wordpress plugin to add custom Advanced Custom Fields blocks (ACF Blocks) to a project.

## Sample Plugin
This is found in the theme_src folder.  It has two sample blocks.  A block called Basic Content and a block called Raw HTML.

## Sample ACF import file
This is found on the root of this project under `acf-export.json'`

## Gulp task runner
Contains a Gulp task runner with some helpful tasks, but it's a group of tasks that have been used for awhile for many projects so be warned that it's not very DRY and could use an update.

The gulp tasks do some nice things.  Like the following:

  > Oh, use node.js version 8.x or the SASS process will error out.

### Javascript tasks
The package.json has a few arrays to allow you to concatenate javascript files together in the order of the array.  There are two javascript files created.  One for admin scripts and the other for front end scripts.
The admin and front end scripts are each separated into vendor script arrays and your own homegrown scripts. The Vendor arrays are for javascript that is from a third-party that is already ready to go and doesn't need babel or any other process besides minification.  Vendor arrays are always concatenated first.
The tasks also concatenate, babel, and minifiy any javascript files under the template_parts directory so that each block can have it's own separate javascript file during development.

### Rsync task
Add a file to under the root of the project called `rsync.json` and it will use this in the watch process to upload changes as you work.  Here is an example of what this file should contain:
```json
{
  "active"     : true,
  "hostname"  : "ftp.jhtechservices.com",
  "username"  : "username",
  "port"      : 55555,
  "destination" : "~/staging/1/wp-content/plugins/"
}

```

This assumes you have setup ssh certificates with your host and your development workstation.

There is an sFTP process I created awhile back that could also be used.  I'll document this later, maybe, as I have rarely used it.  I do remember you must create the root directory of your project on the host using your favorite FTP client before it will work.



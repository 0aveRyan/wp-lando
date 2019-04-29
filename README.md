# wp-lando example for WordCamp Orange County 2019

Example custom command in a WP-CLI Package for automating WordPress setup in [Lando](https://github.com/lando/lando) using WP-CLI.

:link: [Slides](https://github.com/0aveRyan/wp-lando/blob/master/take-command-with-custom-wp-cli-commands.pdf)

## Install This Package

At present, I recommend forking this repo and modifying it to your liking.

Or, install this repo as a package.

1. `lando init --recipe=wordpress`
2. `lando start`
3. `lando wp package install git@github.com:0aveRyan/wp-lando.git`

_See below for usage of the `wp lando install` command_

```
WordPress Credentials
User: lando
Password: lando
```

I recommend forking this, because it installs a bunch of personal preferences and names the `lando` user `Dave Ryan`. In the future, I may expand this command to be more reusable or add additional commands as I try to automate more of my Lando workflows.

## Usage

#### Install Command

`wp lando install [subdomain-string/site_url]`

##### Parameters

* `wp lando install $url` or `--url=` [string]

Lando puts sites at `https://{slug}.lndo.site`, so you can type either the slug or the full URL 

* `wp lando install $url $title` or `--title=` [string]

This allows you to set the Site Title.

--

I realized that for months I was spending about 30-60 minutes each week -- or a total of 48 hours annually -- configuring environments, installing the tools I needed as-needed, etc.

So instead of writing a script, I spent an hour writing a custom WP-CLI Command I can install as a WP-CLI Package.

I'll break even on my time investment in a week or two.

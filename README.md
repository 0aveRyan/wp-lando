## Install This Package

At present, I recommend using this repo for inspiration or forking and modding to your liking.

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

## Usage

#### Install Command

`wp lando install [subdomain-string/site_url]`

_(if you're using this with Lando, you're probably typing `lando wp lando install ...`)_

##### Parameters

* `wp lando install $url` or `--url=` [string]

Lando puts sites at `https://{slug}.lndo.site`, so you can type either the slug or the full URL 

* `wp lando install $url $title` or `--title=` [string]

This allows you to set the Site Title.

--

I realized that for months I was spending about 30-60 minutes each week -- or a total of 48 hours annually -- configuring environments, installing the tools I needed as-needed, etc.

So instead of writing a script, I spent an hour writing a custom WP-CLI Command I can install as a WP-CLI Package.

I'll break even on my time investment in a week or two.

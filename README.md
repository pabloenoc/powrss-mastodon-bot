# Mastodon Bot

This is the source for for the powRSS Mastodon account.

It automates the process of fetching blog posts from the [powRSS](https://powrss.com) database and sharing these to Mastodon.

The bot uses the cURL library for PHP. It is deployed to a Dokku instance on the same VPS hosting powRSS, executed via a cronjob.

## Prerequisites

- A Mastodon account
- A Mastodon access token

I followed [this guide](https://lefevre.dev/posts/easily-publish-post-mastodon-php/) from Franck Lefèvre to get the account set up.

## Deployment

To use Dokku for deployment, it is necessary to have `composer.json` and `composer.lock` files even if the project has no dependencies. This makes it easy for Dokku to configure the corresponding environment for the container.

## Environment Variables

The bot relies on two environment variables as defined in `config.php`:

- MASTODON_INSTANCE
- MASTODON_ACCESS_TOKEN

To set these in the Dokku container, use the following commands:

```bash
$ dokku config:set [app-name] MASTODON_INSTANCE="[instance URL]"
```

Similarly for the access token:

```bash
$ dokku config:set [app-name] MASTODON_ACCESS_TOKEN="[access token]"
```


## Dokku Cron Tasks

Per the Dokku [documentation](https://dokku.com/docs/processes/scheduled-cron-tasks/):

> Dokku automates scheduled `dokku run` commands via it's `app.json` cron integration.

For the sake of simplicity, the current task executes `index.php`.

```
{
  "cron": [
    {
      "command": "php index.php",
      "schedule": "0 */8 * * *"
    }
  ]
}
```




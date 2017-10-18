UKMresponsive
=============
Design for UKM Norges nettsteder. Kjører både på wordpress og symfony out-of-the-box

## Symfony
Installer med composer "ukmnorge/designbundle"
```
   "repositories":[
	    {
            "url": "https://github.com/UKMNorge/UKMresponsive.git",
            "type": "git"
        }
    ],
    "require": {
        "ukmnorge/designbundle": "dev-master"
    },
``` 
### Sitemap auto-update
Det er viktig at sitemap til enhver tid er synkronisert mellom alle installasjoner.
Sett derfor opp en cron-jobb som åpner APP_URL/cron/designbundle/sync_sitemap/ så ofte som ønskelig (anbefalt minst 1 gang per døgn)
Cron-jobben skriver så sitemap.yml som funnet på GitHub til en lokal cache uten nedetid. Skulle henting feile, bruker den sitemap.yml fra installasjonstidspunktet.

## Wordpress
Klon repoet inn i wp-content/themes
### Sitemap auto-update
Sett opp GitHub-hook og auto-pull temaet for hver push

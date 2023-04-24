## Pokestrap Test Project

This is a test project.

## Development Environment
Development is ran locally using DDEV.

**Prerequisites before setup**
1. Install [Docker](https://www.docker.com/) (Required by DDEV)
2. Install [DDEV](https://ddev.com/) (To setup a local env)
3. Install [mkcert](https://github.com/FiloSottile/mkcert) (To create local SSL certificates)

**Setup Your Local Environment**:
1. Run the command `mkcert -install` to set up your local SSL certificate.
2. Clone the repository.
3. Create the file `/wp-config.php` with the contents provided by the team leader.
4. Run the command `ddev start` to set up the local env
5. Import the database using `ddev import-db --src=database.sql` (you will have to ask for a copy of the database, it's not in the repository)
6. Install dependencies with `ddev composer install --working-dir=./wp-content/themes/pokestrap`
7. Save the media archives file to `/httpdocs/wp-content/uploads` on your host machine.
8. Run `ddev npm install` inside the `pokestrap` theme folder (you can also run npm without ddev but using ddev ensures you use the specified node version in the container)
9. Run `npm run watch` and start developing.

**Managing Your Local Environment**:
```bash
# All commands must be ran from the repository root

# To start the local env
ddev start

# To stop it
ddev stop

# To get useful information
ddev describe
```

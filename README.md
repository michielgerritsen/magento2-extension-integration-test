# Magento 2 Docker images for extension testing

This repository builds Docker images that can be used to run Magento unit/integration/browser tests for your Magento 2 extensions. It contains a complete Magento 2 installation with all configuration done so you can start testing right away.

The goal of this repository was to build an easy to use image that needs no configuration and has everything on board and limit the amount of configuration as much as possible. 

The base for this image is `srcoder/development-php`, which provides an environment that is compatible with Magento. It has Composer, Imagemagick, Blackfire and more out of the box.

On top of that MySQL is installed as it is required as Magento does a `mysqldump` when running integration tests. As of Magento 2.4 Elasticsearch is also a requirement so we're also installing that. After this Magento is downloaded and installed.

## Usage

The images can be found at the Docker Hub: https://hub.docker.com/r/michielgerritsen/magento-project-community-edition, you can choose between all Magento community versions and the compatible PHP versions. For each combination there is also an image with the `-sample-data` suffix with the Magento sample data installed.

### Docker CLI

```
docker run -d --rm \
    --name=magento \
    -p 1234:80 \
    -p 3307:3306 \
    -e URL=http://localhost:1234/ \
    -e FLAT_TABLES=false \
    michielgerritsen/magento-project-community-edition:php74-fpm-magento2.4.2
```

### Docker Compose

```
version: '3'
services:
  magento:
    container_name: magento
    image: michielgerritsen/magento-project-community-edition:php74-fpm-magento2.4.2
    ports:
      - 1234:80
      - 3307:3306
    environment:
      - URL=http://localhost:1234/
      - FLAT_TABLES=false
```

## Parameters

These are all optional and only required when you want to run the container to access the web interface.

| Parameter | Info  |
| --- | --- |
| `-p 1234:80` | Make the webserver available at port 1234. Optional if you do not need to visit Magento in the browser. |
| `-p 3307:3306` | Make the MySQL database available at port 3307. Optional if you do not need to access the database. |
| `-e URL=http://localhost:1234/` | Should match the port mapped to port 80 |
| `-e FLAT_TABLES=false` | Enable the flat tables on startup? |

## Commands

There are a few special command that you can run. 

> Note: you need to have the container started before running any of these commands.

| Info | Command | Note |
| --- | --- | --- |
| bin/magento | `docker exec magento bin/magento <your:command:is:my:wish>` |
| magerun2 | `docker exec magento magerun2 <your:command:is:my:wish>` |
| Enable flat catalog | `docker exec magento ./enable-flat-catalog` | If you did not enabled it with the `FLAT_TABLES` variable |
| Change the base URL | `docker exec magento ./change-base-url <domain-name>` | 

## How to use in your own project

In theory this can be used in any pipeline, but I've only integrated this in Github Actions at this moment.

### Github Actions

In the examples folder you can find 2 examples for integration and unit tests. View the [`readme.md`](examples/github) to see how you can start with this in your own project (hint: copy 2 files, add the composer name of your package and you are good to go).

## Thanks

Special thanks to Fooman for providing a Magento 2 mirror that does not requires any authentication:
- https://store.fooman.co.nz/blog/no-authentication-needed-magento-2-mirror.html

## Version matrix

| Magento version | PHP 8.4 | PHP 8.3 | PHP 8.2 | PHP 8.1 | PHP 7.4 | PHP 7.3 | PHP 7.2 |
|-----------------|---------|---------|---------|---------|---------|---------|---------|
| 2.4.8-beta2     | x       | x       | -       | -       | -       | -       | -       |
| 2.4.7-p4        | -       | x       | x       | -       | -       | -       | -       |
| 2.4.7-p3        | -       | x       | x       | -       | -       | -       | -       |
| 2.4.7-p2        | -       | x       | x       | -       | -       | -       | -       |
| 2.4.7-p1        | -       | x       | x       | -       | -       | -       | -       |
| 2.4.7           | -       | x       | x       | -       | -       | -       | -       |
| 2.4.6-p9        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6-p8        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6-p7        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6-p6        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6-p5        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6-p4        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6-p3        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6-p2        | -       | -       | -       | -       | -       | -       | -       |
| 2.4.6-p1        | -       | -       | x       | x       | -       | -       | -       |
| 2.4.6           | -       | -       | x       | x       | -       | -       | -       |
| 2.4.5-p11       | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p10       | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p9        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p8        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p7        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p6        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p5        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p4        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p3        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p2        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5-p1        | -       | -       | -       | x       | -       | -       | -       |
| 2.4.5           | -       | -       | -       | x       | -       | -       | -       |
| 2.4.4-p12       | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p11       | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p10       | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p9        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p8        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p7        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p6        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p5        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p4        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p3        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p2        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4-p1        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.4           | -       | -       | -       | -       | x       | -       | -       |
| 2.4.3-p2        | -       | -       | -       | -       | x       | -       | -       |
| 2.4.3-p1        | -       | -       | -       | -       | x       | x       | -       |
| 2.4.3           | -       | -       | -       | -       | x       | x       | -       |
| 2.4.2-p2        | -       | -       | -       | -       | x       | x       | -       |
| 2.4.2-p1        | -       | -       | -       | -       | x       | x       | -       |
| 2.4.2           | -       | -       | -       | -       | x       | x       | -       |
| 2.4.1-p1        | -       | -       | -       | -       | x       | x       | -       |
| 2.4.1           | -       | -       | -       | -       | x       | x       | -       |
| 2.4.0-p1        | -       | -       | -       | -       | x       | x       | -       |
| 2.4.0           | -       | -       | -       | -       | x       | x       | -       |
| 2.3.7-p4        | -       | -       | -       | -       | x       | x       | -       |
| 2.3.7-p3        | -       | -       | -       | -       | x       | x       | -       |
| 2.3.7-p2        | -       | -       | -       | -       | x       | x       | -       |
| 2.3.7-p1        | -       | -       | -       | -       | x       | x       | -       |
| 2.3.7           | -       | -       | -       | -       | x       | x       | -       |
| 2.3.6-p1        | -       | -       | -       | -       | -       | x       | x       |
| 2.3.6           | -       | -       | -       | -       | -       | x       | x       |
| 2.3.5-p2        | -       | -       | -       | -       | -       | x       | x       |
| 2.3.5-p1        | -       | -       | -       | -       | -       | x       | x       |
| 2.3.4           | -       | -       | -       | -       | -       | x       | x       |
| 2.3.3           | -       | -       | -       | -       | -       | x       | x       |
| 2.3.2           | -       | -       | -       | -       | -       | -       | x       |
| 2.3.1           | -       | -       | -       | -       | -       | -       | x       |
| 2.3.0           | -       | -       | -       | -       | -       | -       | x       |

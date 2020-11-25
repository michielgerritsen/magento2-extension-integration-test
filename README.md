# Magento 2 Docker images for extension testing

This repository builds Docker images that can be used to run Magento unit/integration/browser tests for your Magento 2 extensions. It contains a complete Magento 2 installation with all configuration done so you can start testing right away.

The goal of this repository was to build an easy to use image that needs no configuration and has everything on board. The goal was also to limit the amount of configuration as much as possible. 

The base for this image is `srcoder/development-php`, which provides an environment that is compatible with Magento. It has Composer, Imagemagick, Blackfire and more out of the box.

On top of that MySQL is installed as it is required as Magento does a `mysqldump` when running integration tests. As of Magento 2.4 Elasticsearch is also a requirement so we're also installing that. After this Magento is downloaded and installed.

**Warning:** I'm not sure why, but the `deploy:mode:set developer` step is required. If you skip this you may end up with loads and loads of failing tests. While building the docker container this step is already done but it is still required for some reason. You may end up with errors like these:
```
ReflectionException: Class Magento\Catalog\Api\Data\ProductExtensionInterfaceFactory does not exist
```

Special thanks to Fooman for providing a Magento 2 mirror that does not requires any authentication:
- https://store.fooman.co.nz/blog/no-authentication-needed-magento-2-mirror.html

Docker Hub link:
- https://hub.docker.com/r/michielgerritsen/magento-project-community-edition

## Browser testing

A webserver is running and the Magento sample data is included. To show Magento in the browser you've to expose port 80, but as you're most likely a developer; port 80 is probably in use so you can map that to another port:
```
docker run --rm -p 1234:80 -p 3307:3306 -d --name magento michielgerritsen/magento-project-community-edition:php74-fpm-magento2.4.0
```

After that you've to change the base url with:
```
docker exec magento ./change-base-url <domain-name>
```
And then you should see the installation in your browser at http://127.0.0.1:1234/

## Commands
There are a few special command that you can run. Please note: you need to have the container started before running any of these commands.

### bin/magento
You can run bin/magento like this:
```
docker exec magento bin/magento <your:command:is:my:wish>
```

### Magerun
```
docker exec magento magerun2 <your:command:is:my:wish>
```

### Installing sample data
```
docker exec magento ./install-sample-data
```

## Enable flat catalog
```
docker exec magento ./enable-flat-catalog
```

## Change the base URL
Make sure to include the trailing slash (/).
```
docker exec magento ./change-base-url <domain-name>
docker exec magento ./change-base-url http://domain-name.test/
```

## How to use in your own project

In theory this can be used in any pipeline, but i've only integrated this in Github Actions at this moment.

## Github Actions

In the examples folder you can find 2 examples for Integration and Unit tests. View the [`readme.md`](examples/github) to see how you can start with this in your own project. (Hint: Copy 2 files, add the composer name of your package and you are good to go)

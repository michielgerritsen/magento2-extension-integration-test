This repository builds a Docker image that can be used to run Magento unit/integration tests for your Magento 2 extensions. It contains a complete Magento 2 installation with all configuration done so you can start testing right away.

The goal of this repository was to build an easy to use image that needs no configuration and has everything on board. The goal was also to limit the amount of configuration as much as possible. 

The base for this image is `srcoder/development-php`, which provides an environment that is compatible with Magento. It has Composer, Imagemagick, Xdebug, Blackfire and more out of the box.

On top of that mysql is installed as it is required as Magento does a `mysqldump` when running integration tests. After this Magento is downloaded and installed.

**Warning:** I'm not sure why, but the `deploy:mode:set developer` step is required. If you skip this you may end up with loads and loads of failing tests. While building the docker container this step is already done but it is still required for some reason. You may end up with errors like these:
```
ReflectionException: Class Magento\Catalog\Api\Data\ProductExtensionInterfaceFactory does not exist
```

Special thanks to Fooman for providing a Magento 2 mirror that does not requires any authentication:
- https://store.fooman.co.nz/blog/no-authentication-needed-magento-2-mirror.html

# How to use in your own project

In theory this can be used in any pipeline, but i've only integrated this in Github Actions at this moment.

## Github Actions

In the examples folder you can find 2 examples for Integration and Unit tests. View the [`readme.md`](examples/github) to see how you can start with this in your own project. (Hint: Copy 2 files, add the composer name of your package and you are good to go)